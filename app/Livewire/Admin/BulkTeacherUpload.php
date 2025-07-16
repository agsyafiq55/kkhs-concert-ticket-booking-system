<?php

namespace App\Livewire\Admin;

use App\Imports\TeachersImport;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BulkTeacherUpload extends Component
{
    use WithFileUploads;

    public $file;

    public $importing = false;

    public $importResult = null;

    public $previewData = [];

    public $showPreview = false;

    public $validationErrors = [];

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
        // Check if user has permission to bulk upload teachers
        if (! Gate::allows('bulk upload teachers')) {
            abort(403, 'You do not have permission to bulk upload teacher accounts.');
        }
    }

    public function updatedFile()
    {
        $this->validate(['file' => $this->rules['file']]);
        $this->previewFile();
    }

    /**
     * Validate Malaysian IC number format
     * Format: YYMMDDPPNNNN
     * - YYMMDD: Birth date (6 digits)
     * - PP: Birth place code (01-12)
     * - NNNN: Random numbers (4 digits)
     */
    public function validateMalaysianIC($icNumber)
    {
        // Remove any spaces or dashes
        $icNumber = preg_replace('/[\s-]/', '', $icNumber);

        // Check if it's exactly 12 digits
        if (! preg_match('/^\d{12}$/', $icNumber)) {
            return [
                'valid' => false,
                'error' => 'IC number must be exactly 12 digits',
            ];
        }

        // Extract parts
        $year = substr($icNumber, 0, 2);
        $month = substr($icNumber, 2, 2);
        $day = substr($icNumber, 4, 2);
        $birthPlaceCode = substr($icNumber, 6, 2);

        // Validate birth place code (01-12)
        if (intval($birthPlaceCode) < 1 || intval($birthPlaceCode) > 12) {
            return [
                'valid' => false,
                'error' => 'Birth place code must be between 01-12',
            ];
        }

        // Validate month (01-12)
        if (intval($month) < 1 || intval($month) > 12) {
            return [
                'valid' => false,
                'error' => 'Invalid month in birth date',
            ];
        }

        // Validate day (01-31)
        if (intval($day) < 1 || intval($day) > 31) {
            return [
                'valid' => false,
                'error' => 'Invalid day in birth date',
            ];
        }

        // Additional date validation
        $currentYear = date('y');
        $fullYear = intval($year) <= $currentYear ? 2000 + intval($year) : 1900 + intval($year);

        if (! checkdate(intval($month), intval($day), $fullYear)) {
            return [
                'valid' => false,
                'error' => 'Invalid birth date',
            ];
        }

        return [
            'valid' => true,
            'error' => null,
            'formatted_date' => sprintf('%02d/%02d/%04d', intval($day), intval($month), $fullYear),
        ];
    }

    public function previewFile()
    {
        if (! $this->file) {
            return;
        }

        try {
            // Read all rows to show complete preview using a simple anonymous import class
            $collection = Excel::toCollection(new class {
                use \Maatwebsite\Excel\Concerns\Importable;
            }, $this->file)->first();

            // Get headers
            $headers = $collection->first();

            // Get all data rows for preview
            $dataRows = $collection->skip(1);

            $this->previewData = [
                'headers' => $headers ? $headers->toArray() : [],
                'rows' => $dataRows ? $dataRows->toArray() : [],
                'total_rows' => $collection->count() - 1, // Subtract header row
            ];

            // Validate IC numbers and class assignments in preview data
            $this->validationErrors = [];
            if (! empty($this->previewData['headers']) && ! empty($this->previewData['rows'])) {
                $this->validatePreviewData();
            }

            $this->showPreview = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Error reading file: '.$e->getMessage());
            $this->showPreview = false;
        }
    }

    protected function validatePreviewData()
    {
        $headers = $this->previewData['headers'];
        $rows = $this->previewData['rows'];

        // Find column indexes
        $icColumnIndex = null;
        $assignedClassesColumnIndex = null;
        
        $possibleICColumns = ['ic_number', 'ic number', 'ic', 'mykad', 'identification', 'nric'];
        $possibleClassColumns = ['assigned_classes', 'assigned classes', 'classes', 'class_assignments'];

        foreach ($headers as $index => $header) {
            $headerLower = strtolower(trim($header));
            if (in_array($headerLower, $possibleICColumns)) {
                $icColumnIndex = $index;
            }
            if (in_array($headerLower, $possibleClassColumns)) {
                $assignedClassesColumnIndex = $index;
            }
        }

        if ($icColumnIndex === null) {
            $this->validationErrors[] = [
                'row' => 'Header',
                'error' => 'IC number column not found. Please ensure your file has a column named "ic_number", "ic number", "ic", "mykad", "identification", or "nric".',
            ];
        }

        // Validate each row
        foreach ($rows as $rowIndex => $row) {
            $actualRowNumber = $rowIndex + 2; // +2 because array is 0-indexed and we skip header row

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Validate IC number if column exists
            if ($icColumnIndex !== null) {
                $icNumber = isset($row[$icColumnIndex]) ? trim($row[$icColumnIndex]) : '';

                if (empty($icNumber)) {
                    $this->validationErrors[] = [
                        'row' => $actualRowNumber,
                        'error' => 'IC number is required',
                    ];
                } else {
                    $validation = $this->validateMalaysianIC($icNumber);
                    if (! $validation['valid']) {
                        $this->validationErrors[] = [
                            'row' => $actualRowNumber,
                            'error' => "IC number '{$icNumber}': ".$validation['error'],
                        ];
                    }
                }
            }

            // Validate assigned classes format if column exists
            if ($assignedClassesColumnIndex !== null) {
                $assignedClasses = isset($row[$assignedClassesColumnIndex]) ? trim($row[$assignedClassesColumnIndex]) : '';
                
                if (!empty($assignedClasses)) {
                    // Basic validation - check if it contains reasonable class names
                    $classNames = array_map('trim', explode(',', $assignedClasses));
                    foreach ($classNames as $className) {
                        if (strlen($className) < 2) {
                            $this->validationErrors[] = [
                                'row' => $actualRowNumber,
                                'error' => "Invalid class name '{$className}' in assigned classes",
                            ];
                        }
                    }
                }
            }
        }
    }

    public function import()
    {
        $this->validate();

        if (! $this->file) {
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
        $classAssignmentErrors = [];

        try {
            $import = new TeachersImport;
            Excel::import($import, $this->file);

            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $createdUsers = $import->getCreatedUsers();
            $skippedUsers = $import->getSkippedUsers();
            $classAssignmentErrors = $import->getClassAssignmentErrors();

            // Initialize safe defaults
            $failuresArray = [];
            $errorsArray = [];

            try {
                // Get validation errors and convert to arrays for Livewire compatibility
                $failures = $import->failures();
                $errors = $import->errors();

                // Convert failure objects to simple arrays
                foreach ($failures as $failure) {
                    $failuresArray[] = [
                        'row' => $failure->row(),
                        'errors' => $failure->errors(),
                    ];
                }

                // Convert error objects to simple arrays
                foreach ($errors as $error) {
                    $errorsArray[] = (string) $error;
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
                'class_assignment_errors' => $classAssignmentErrors,
                'failures' => $failuresArray,
                'errors' => $errorsArray,
                'total_processed' => $successCount + $errorCount + count($skippedUsers),
            ];

            if ($successCount > 0) {
                session()->flash('message', "Successfully created {$successCount} teacher accounts!");
            }

            if (count($skippedUsers) > 0) {
                session()->flash('warning', 'Skipped '.count($skippedUsers).' users (already exist in system).');
            }

            if ($errorCount > 0) {
                session()->flash('error', "Failed to create {$errorCount} accounts. Please check the errors below.");
            }

            if (count($classAssignmentErrors) > 0) {
                session()->flash('warning', 'Some class assignments failed. Check the class assignment errors below.');
            }

            // Clear the file after successful import
            $this->reset(['file', 'showPreview', 'previewData', 'validationErrors']);

        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: '.$e->getMessage());
        } finally {
            $this->importing = false;
        }
    }

    public function clearResults()
    {
        $this->importResult = null;
        $this->reset(['file', 'showPreview', 'previewData', 'validationErrors']);
    }

    public function render()
    {
        return view('livewire.admin.bulk-teacher-upload');
    }
} 