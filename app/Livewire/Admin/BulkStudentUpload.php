<?php

namespace App\Livewire\Admin;

use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BulkStudentUpload extends Component
{
    use WithFileUploads;

    public $file;
    public $importing = false;
    public $importResult = null;
    public $previewData = [];
    public $showPreview = false;

    protected $rules = [
        'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // 10MB max
    ];

    protected $messages = [
        'file.required' => 'Please select a file to upload',
        'file.mimes' => 'File must be a CSV or Excel file (.csv, .xlsx, .xls)',
        'file.max' => 'File size must not exceed 10MB',
    ];

    public function mount()
    {
        // Check if user has permission to bulk upload students
        if (!Gate::allows('bulk upload students')) {
            abort(403, 'You do not have permission to bulk upload student accounts.');
        }
    }

    public function updatedFile()
    {
        $this->validate(['file' => $this->rules['file']]);
        $this->previewFile();
    }

    public function previewFile()
    {
        if (!$this->file) {
            return;
        }

        try {
            // Read first 5 rows to show preview
            $collection = Excel::toCollection(null, $this->file)->first();
            
            // Get headers
            $headers = $collection->first();
            
            // Get first few data rows for preview
            $dataRows = $collection->skip(1)->take(5);
            
            $this->previewData = [
                'headers' => $headers ? $headers->toArray() : [],
                'rows' => $dataRows ? $dataRows->toArray() : [],
                'total_rows' => $collection->count() - 1, // Subtract header row
            ];
            
            $this->showPreview = true;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error reading file: ' . $e->getMessage());
            $this->showPreview = false;
        }
    }

    public function import()
    {
        $this->validate();

        if (!$this->file) {
            session()->flash('error', 'Please select a file to upload.');
            return;
        }

        $this->importing = true;
        $this->importResult = null;

        // Initialize variables
        $successCount = 0;
        $errorCount = 0;
        $createdUsers = [];
        $skippedUsers = [];
        
        try {
            $import = new StudentsImport();
            Excel::import($import, $this->file);

            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $createdUsers = $import->getCreatedUsers();
            $skippedUsers = $import->getSkippedUsers();
            
            // Initialize safe defaults
            $failuresArray = [];
            $errorsArray = [];
            
            try {
                // Get validation errors and convert to arrays for Livewire compatibility
                $failures = $import->failures();
                $errors = $import->errors();
                
                // Convert failure objects to simple arrays
                foreach ($failures as $failure) {
                    try {
                        $failuresArray[] = [
                            'row' => $failure->row(),
                            'attribute' => $failure->attribute(),
                            'errors' => $failure->errors(),
                            'values' => $failure->values()
                        ];
                    } catch (\Exception $e) {
                        // If a specific failure can't be serialized, add a generic error
                        $failuresArray[] = [
                            'row' => 'Unknown',
                            'attribute' => 'Unknown',
                            'errors' => ['Error processing row data'],
                            'values' => []
                        ];
                    }
                }

                // Convert error objects to simple strings for Livewire compatibility
                foreach ($errors as $error) {
                    try {
                        if (is_string($error)) {
                            $errorsArray[] = $error;
                        } elseif (is_object($error) && method_exists($error, '__toString')) {
                            $errorsArray[] = (string) $error;
                        } elseif (is_array($error)) {
                            $errorsArray[] = json_encode($error);
                        } else {
                            $errorsArray[] = 'Unknown error occurred';
                        }
                    } catch (\Exception $e) {
                        $errorsArray[] = 'Error processing error data';
                    }
                }
            } catch (\Exception $e) {
                // If we can't process errors at all, just use empty arrays
                $failuresArray = [];
                $errorsArray = ['Could not retrieve detailed error information'];
            }

            // Safely create the result array
            $this->importResult = [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'skipped_count' => count($skippedUsers),
                'created_users' => $createdUsers,
                'skipped_users' => $skippedUsers,
                'failures' => $failuresArray,
                'errors' => $errorsArray,
                'total_processed' => $successCount + $errorCount + count($skippedUsers),
            ];

            if ($successCount > 0) {
                session()->flash('message', "Successfully created {$successCount} student accounts!");
            }

            if (count($skippedUsers) > 0) {
                session()->flash('warning', "Skipped " . count($skippedUsers) . " users (already exist in system).");
            }

            if ($errorCount > 0) {
                session()->flash('error', "Failed to create {$errorCount} accounts. Please check the errors below.");
            }

            // Clear the file after successful import
            $this->reset(['file', 'showPreview', 'previewData']);

        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: ' . $e->getMessage());
        } finally {
            $this->importing = false;
        }
    }

    public function clearResults()
    {
        $this->importResult = null;
        $this->reset(['file', 'showPreview', 'previewData']);
    }

    public function render()
    {
        return view('livewire.admin.bulk-student-upload');
    }
}
