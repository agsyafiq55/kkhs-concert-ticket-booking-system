<?php

namespace App\Livewire\Admin;

use App\Imports\StudentsImport;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public $validationErrors = [];

    // Progress tracking properties
    public $progressKey = null;
    public $importProgress = null;
    public $showProgressBar = false;

    protected $rules = [
        'file' => 'required|file|mimes:csv,xlsx,xls|max:20480', // 20MB max for large imports
    ];

    protected $messages = [
        'file.required' => 'Please select a file to upload',
        'file.mimes' => 'File must be a CSV or Excel file (.csv, .xlsx, .xls)',
        'file.max' => 'File size must not exceed 20MB',
    ];

    public function mount()
    {
        // Check if user has permission to bulk upload students
        if (! Gate::allows('bulk upload students')) {
            abort(403, 'You do not have permission to bulk upload student accounts.');
        }
    }

    public function updatedFile()
    {
        $this->validate(['file' => $this->rules['file']]);
        $this->previewFile();
    }

    /**
     * Get the current import progress from cache
     */
    public function getProgress()
    {
        if (!$this->progressKey) {
            return null;
        }

        $this->importProgress = Cache::get($this->progressKey);
        
        // If progress is completed, stop showing progress bar
        if ($this->importProgress && $this->importProgress['status'] === 'completed') {
            $this->showProgressBar = false;
            $this->importing = false;
        }
        
        // Add timeout handling - if stuck for too long, show error
        if ($this->importProgress && $this->importing) {
            $progressStartTime = Cache::get($this->progressKey . '_start_time');
            if (!$progressStartTime) {
                // First time checking, record start time
                Cache::put($this->progressKey . '_start_time', time(), 600);
            } else {
                $elapsedMinutes = (time() - $progressStartTime) / 60;
                
                // If stuck at 0% for more than 2 minutes, something went wrong
                if ($elapsedMinutes > 2 && ($this->importProgress['percentage'] ?? 0) <= 5) {
                    $this->importing = false;
                    $this->showProgressBar = false;
                    session()->flash('error', 'Import appears to be stuck. Please try again with a smaller file or check the file format.');
                    $this->clearProgress();
                }
            }
        }

        return $this->importProgress;
    }

    /**
     * Clear progress data from cache
     */
    public function clearProgress()
    {
        if ($this->progressKey) {
            Cache::forget($this->progressKey);
            Cache::forget($this->progressKey . '_start_time');
        }
        $this->progressKey = null;
        $this->importProgress = null;
        $this->showProgressBar = false;
    }

    public function previewFile()
    {
        if (! $this->file) {
            return;
        }

        try {
            // Read all rows to validate everything comprehensively
            $collection = Excel::toCollection(new class {
                use \Maatwebsite\Excel\Concerns\Importable;
            }, $this->file)->first();

            // Get headers
            $headers = $collection->first();

            // Get all data rows (no limit for validation)
            $dataRows = $collection->skip(1);
            $totalRows = $dataRows->count();
            $previewRows = $dataRows->take(100); // Only show first 100 rows in preview

            $this->previewData = [
                'headers' => $headers ? $headers->toArray() : [],
                'rows' => $previewRows ? $previewRows->toArray() : [],
                'total_rows' => $totalRows,
                'preview_limit' => min(100, $totalRows),
                'is_large_import' => $totalRows > 100,
            ];

            // COMPREHENSIVE VALIDATION - Validate ALL rows, not just preview
            $this->validationErrors = [];
            if (! empty($this->previewData['headers']) && $totalRows > 0) {
                $this->comprehensiveValidation($headers->toArray(), $dataRows->toArray());
            }

            $this->showPreview = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Error reading file: '.$e->getMessage());
            $this->showPreview = false;
        }
    }

    /**
     * Comprehensive validation of ALL rows and columns
     */
    protected function comprehensiveValidation($headers, $allRows)
    {
        $this->validationErrors = [];
        
        // Step 1: Validate headers
        $this->validateHeaders($headers);
        
        // If headers are invalid, stop here
        if (!empty($this->validationErrors)) {
            return;
        }
        
        // Get column indexes
        $columnIndexes = $this->getColumnIndexes($headers);
        
        // Step 2: Validate all data rows
        $this->validateAllRows($allRows, $columnIndexes);
        
        // Step 3: Check for duplicates within the file
        $this->checkDuplicatesInFile($allRows, $columnIndexes);
        
        // Step 4: Check for duplicates against existing database records
        $this->checkDuplicatesAgainstDatabase($allRows, $columnIndexes);
    }

    /**
     * Validate that all required headers are present
     */
    protected function validateHeaders($headers)
    {
        $requiredColumns = ['name', 'email', 'daftar_no', 'class'];
        $foundColumns = [];
        
        foreach ($requiredColumns as $required) {
            $found = false;
            $possibleNames = $this->getPossibleColumnNames($required);
            
            foreach ($headers as $header) {
                if (in_array(strtolower(trim($header)), $possibleNames)) {
                    $foundColumns[$required] = $header;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $this->validationErrors[] = [
                    'row' => 'Header',
                    'field' => $required,
                    'error' => "Required column '{$required}' not found. Expected one of: " . implode(', ', $possibleNames),
                ];
            }
        }
    }

    /**
     * Get possible column names for each required field
     */
    protected function getPossibleColumnNames($field)
    {
        $mapping = [
            'name' => ['name', 'student_name', 'full_name', 'nama'],
            'email' => ['email', 'email_address', 'e-mail', 'emel'],
            'daftar_no' => ['daftar_no', 'daftar no', 'daftar', 'registration_no', 'registration no', 'reg_no', 'student_id'],
            'class' => ['class', 'kelas', 'tingkatan', 'form', 'classroom'],
        ];
        
        return $mapping[$field] ?? [$field];
    }

    /**
     * Get column indexes for all required fields
     */
    protected function getColumnIndexes($headers)
    {
        $indexes = [];
        $requiredColumns = ['name', 'email', 'daftar_no', 'class'];
        
        foreach ($requiredColumns as $required) {
            $possibleNames = $this->getPossibleColumnNames($required);
            
            foreach ($headers as $index => $header) {
                if (in_array(strtolower(trim($header)), $possibleNames)) {
                    $indexes[$required] = $index;
                    break;
                }
            }
        }
        
        return $indexes;
    }

    /**
     * Validate all rows for format and required fields
     */
    protected function validateAllRows($allRows, $columnIndexes)
    {
        foreach ($allRows as $rowIndex => $row) {
            $actualRowNumber = $rowIndex + 2; // +2 because array is 0-indexed and we skip header row
            
            // Skip completely empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Validate each required field
            $this->validateRowField($row, $actualRowNumber, 'name', $columnIndexes);
            $this->validateRowField($row, $actualRowNumber, 'email', $columnIndexes);
            $this->validateRowField($row, $actualRowNumber, 'daftar_no', $columnIndexes);
            $this->validateRowField($row, $actualRowNumber, 'class', $columnIndexes);
        }
    }

    /**
     * Validate individual field in a row
     */
    protected function validateRowField($row, $rowNumber, $field, $columnIndexes)
    {
        if (!isset($columnIndexes[$field])) {
            return; // Column not found, already reported in header validation
        }
        
        $columnIndex = $columnIndexes[$field];
        $value = isset($row[$columnIndex]) ? trim($row[$columnIndex]) : '';
        
        // Check if required field is empty
        if (empty($value)) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => $field,
                'error' => ucfirst($field) . ' is required and cannot be empty',
                'value' => $value,
            ];
            return;
        }
        
        // Field-specific validation
        switch ($field) {
            case 'name':
                $this->validateName($value, $rowNumber);
                break;
            case 'email':
                $this->validateEmail($value, $rowNumber);
                break;
            case 'daftar_no':
                $this->validateDaftarNoFormat($value, $rowNumber);
                break;
            case 'class':
                $this->validateClassFormat($value, $rowNumber);
                break;
        }
    }

    /**
     * Validate name format
     */
    protected function validateName($name, $rowNumber)
    {
        if (strlen($name) < 2) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => 'name',
                'error' => 'Name must be at least 2 characters long',
                'value' => $name,
            ];
        }
        
        if (strlen($name) > 255) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => 'name',
                'error' => 'Name cannot exceed 255 characters',
                'value' => $name,
            ];
        }
    }

    /**
     * Validate email format
     */
    protected function validateEmail($email, $rowNumber)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => 'email',
                'error' => 'Invalid email format',
                'value' => $email,
            ];
        }
        
        if (strlen($email) > 255) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => 'email',
                'error' => 'Email cannot exceed 255 characters',
                'value' => $email,
            ];
        }
    }

    /**
     * Validate Daftar No format
     */
    protected function validateDaftarNoFormat($daftarNo, $rowNumber)
    {
        // Remove any spaces or dashes
        $cleanDaftarNo = preg_replace('/[\s-]/', '', $daftarNo);
        
        // Check if it's exactly 5 digits
        if (!preg_match('/^\d{5}$/', $cleanDaftarNo)) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => 'daftar_no',
                'error' => 'Daftar No must be exactly 5 digits',
                'value' => $daftarNo,
            ];
        }
    }

    /**
     * Validate class format and existence
     */
    protected function validateClassFormat($classInfo, $rowNumber)
    {
        // Parse the class info (e.g., "1 AMANAH" -> form_level: "1", class_name: "AMANAH")
        $parts = explode(' ', $classInfo, 2);
        
        if (count($parts) < 2) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => 'class',
                'error' => 'Class format should be "Form Level Class Name" (e.g., "1 AMANAH", "6 ATAS 1", "Peralihan CERIA 1")',
                'value' => $classInfo,
            ];
            return;
        }

        $formLevel = $parts[0];
        $className = $parts[1];

        // Check if the class exists in the database
        $schoolClass = SchoolClass::where('form_level', $formLevel)
            ->where('class_name', $className)
            ->where('is_active', true)
            ->first();

        if (!$schoolClass) {
            $this->validationErrors[] = [
                'row' => $rowNumber,
                'field' => 'class',
                'error' => 'Class does not exist in the system or is not active',
                'value' => $classInfo,
            ];
        }
    }

    /**
     * Check for duplicates within the file
     */
    protected function checkDuplicatesInFile($allRows, $columnIndexes)
    {
        $emailsSeen = [];
        $daftarNosSeen = [];
        
        foreach ($allRows as $rowIndex => $row) {
            $actualRowNumber = $rowIndex + 2;
            
            // Skip completely empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Check email duplicates
            if (isset($columnIndexes['email'])) {
                $email = isset($row[$columnIndexes['email']]) ? strtolower(trim($row[$columnIndexes['email']])) : '';
                if (!empty($email)) {
                    if (isset($emailsSeen[$email])) {
                        $this->validationErrors[] = [
                            'row' => $actualRowNumber,
                            'field' => 'email',
                            'error' => "Duplicate email in file (first seen in row {$emailsSeen[$email]})",
                            'value' => $email,
                        ];
                    } else {
                        $emailsSeen[$email] = $actualRowNumber;
                    }
                }
            }
            
            // Check Daftar No duplicates
            if (isset($columnIndexes['daftar_no'])) {
                $daftarNo = isset($row[$columnIndexes['daftar_no']]) ? trim($row[$columnIndexes['daftar_no']]) : '';
                if (!empty($daftarNo)) {
                    if (isset($daftarNosSeen[$daftarNo])) {
                        $this->validationErrors[] = [
                            'row' => $actualRowNumber,
                            'field' => 'daftar_no',
                            'error' => "Duplicate Daftar No in file (first seen in row {$daftarNosSeen[$daftarNo]})",
                            'value' => $daftarNo,
                        ];
                    } else {
                        $daftarNosSeen[$daftarNo] = $actualRowNumber;
                    }
                }
            }
        }
    }

    /**
     * Check for duplicates against existing database records
     */
    protected function checkDuplicatesAgainstDatabase($allRows, $columnIndexes)
    {
        $emailsToCheck = [];
        $daftarNosToCheck = [];
        $rowMapping = [];
        
        // Collect all emails and daftar nos to check
        foreach ($allRows as $rowIndex => $row) {
            $actualRowNumber = $rowIndex + 2;
            
            // Skip completely empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            if (isset($columnIndexes['email'])) {
                $email = isset($row[$columnIndexes['email']]) ? strtolower(trim($row[$columnIndexes['email']])) : '';
                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailsToCheck[] = $email;
                    $rowMapping['email'][$email] = $actualRowNumber;
                }
            }
            
            if (isset($columnIndexes['daftar_no'])) {
                $daftarNo = isset($row[$columnIndexes['daftar_no']]) ? trim($row[$columnIndexes['daftar_no']]) : '';
                if (!empty($daftarNo) && preg_match('/^\d{5}$/', preg_replace('/[\s-]/', '', $daftarNo))) {
                    $daftarNosToCheck[] = $daftarNo;
                    $rowMapping['daftar_no'][$daftarNo] = $actualRowNumber;
                }
            }
        }
        
        // Check emails against database
        if (!empty($emailsToCheck)) {
            $existingEmails = \App\Models\User::whereIn('email', $emailsToCheck)->pluck('email')->toArray();
            foreach ($existingEmails as $existingEmail) {
                $rowNumber = $rowMapping['email'][$existingEmail];
                $this->validationErrors[] = [
                    'row' => $rowNumber,
                    'field' => 'email',
                    'error' => 'Email already exists in the system',
                    'value' => $existingEmail,
                ];
            }
        }
        
        // Check daftar nos against database
        if (!empty($daftarNosToCheck)) {
            $existingDaftarNos = \App\Models\User::whereIn('daftar_no', $daftarNosToCheck)->pluck('daftar_no')->toArray();
            foreach ($existingDaftarNos as $existingDaftarNo) {
                $rowNumber = $rowMapping['daftar_no'][$existingDaftarNo];
                $this->validationErrors[] = [
                    'row' => $rowNumber,
                    'field' => 'daftar_no',
                    'error' => 'Daftar No already exists in the system',
                    'value' => $existingDaftarNo,
                ];
            }
        }
    }

    // Keep the legacy methods for backward compatibility but they're not used anymore
    protected function validateDaftarNumbers() { /* Legacy method - not used */ }
    protected function validateClasses() { /* Legacy method - not used */ }

    /**
     * Validate Malaysian Daftar No format - legacy method
     */
    public function validateDaftarNo($daftarNo)
    {
        // Remove any spaces or dashes
        $daftarNo = preg_replace('/[\s-]/', '', $daftarNo);

        // Check if it's exactly 5 digits
        if (!preg_match('/^\d{5}$/', $daftarNo)) {
            return [
                'valid' => false,
                'error' => 'Daftar No must be exactly 5 digits',
            ];
        }

        return [
            'valid' => true,
            'error' => null,
        ];
    }

    /**
     * Check if there are any validation errors that should block import
     */
    public function hasValidationErrors()
    {
        return !empty($this->validationErrors);
    }

    public function import()
    {
        $this->validate();

        if (! $this->file) {
            session()->flash('error', 'Please select a file to upload.');
            return;
        }

        // Initialize progress tracking immediately
        $this->importing = true;
        $this->importResult = null;
        $this->progressKey = 'student_import_' . uniqid() . '_' . time();
        $this->showProgressBar = true;
        $this->importProgress = null;

        // Initialize progress in cache immediately
        Cache::put($this->progressKey, [
            'total_rows' => 0,
            'processed_rows' => 0,
            'success_count' => 0,
            'error_count' => 0,
            'percentage' => 0,
            'status' => 'initializing',
            'current_chunk' => 0,
            'total_chunks' => 0,
        ], 600);

        // Force Livewire to re-render to show progress bar
        $this->dispatch('$refresh');
        
        // Call performImport directly instead of using JavaScript dispatch
        $this->performImport();
    }

    public function performImport()
    {
        // Set unlimited execution time for large imports
        set_time_limit(0);

        // Update progress to show we're starting
        Cache::put($this->progressKey, [
            'total_rows' => 0,
            'processed_rows' => 0,
            'success_count' => 0,
            'error_count' => 0,
            'percentage' => 5,
            'status' => 'starting',
            'current_chunk' => 0,
            'total_chunks' => 0,
        ], 600);
        
        // Record start time for timeout tracking
        Cache::put($this->progressKey . '_start_time', time(), 600);

        // Initialize variables
        $successCount = 0;
        $errorCount = 0;
        $createdUsers = [];
        $skippedUsers = [];

        try {
            // Log file info for debugging
            Log::info('File info:', [
                'file_exists' => !is_null($this->file),
                'file_type' => $this->file ? get_class($this->file) : 'null',
                'progress_key' => $this->progressKey
            ]);

            // First, try to read the file to get basic info
            Cache::put($this->progressKey, [
                'total_rows' => 0,
                'processed_rows' => 0,
                'success_count' => 0,
                'error_count' => 0,
                'percentage' => 10,
                'status' => 'reading_file',
                'current_chunk' => 0,
                'total_chunks' => 0,
            ], 600);

            // Get file info first
            $collection = Excel::toCollection(new class {
                use \Maatwebsite\Excel\Concerns\Importable;
            }, $this->file)->first();

            $totalDataRows = $collection->count() - 1; // Subtract header row
            
            Log::info('File read successfully', [
                'total_data_rows' => $totalDataRows,
                'collection_count' => $collection->count()
            ]);
            
            // Update progress with actual row count
            Cache::put($this->progressKey, [
                'total_rows' => $totalDataRows,
                'processed_rows' => 0,
                'success_count' => 0,
                'error_count' => 0,
                'percentage' => 15,
                'status' => 'initializing_import',
                'current_chunk' => 0,
                'total_chunks' => ceil($totalDataRows / 200),
            ], 600);

            // Now start the actual import
            $import = new StudentsImport;
            $import->setProgressKey($this->progressKey);
            
            // Log before starting Excel import
            Log::info('Starting Excel import', [
                'progress_key' => $this->progressKey,
                'total_rows' => $totalDataRows,
                'file_path' => $this->file->getPathname()
            ]);
            
            Excel::import($import, $this->file);
            
            // Log after Excel import
            Log::info('Excel import completed', [
                'success_count' => $import->getSuccessCount(),
                'error_count' => $import->getErrorCount(),
                'skipped_count' => count($import->getSkippedUsers())
            ]);
            
            // Mark progress as completed
            $import->markProgressCompleted();

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
                            'values' => $failure->values(),
                        ];
                    } catch (\Exception $e) {
                        // If a specific failure can't be serialized, add a generic error
                        $failuresArray[] = [
                            'row' => 'Unknown',
                            'attribute' => 'Unknown',
                            'errors' => ['Error processing row data'],
                            'values' => [],
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
                session()->flash('warning', 'Skipped '.count($skippedUsers).' users (already exist in system).');
            }

            if ($errorCount > 0) {
                session()->flash('error', "Failed to create {$errorCount} accounts. Please check the errors below.");
            }

            // Clear the file after successful import
            $this->reset(['file', 'showPreview', 'previewData', 'validationErrors']);

        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: '.$e->getMessage());
            
            // Mark progress as failed
            if ($this->progressKey) {
                Cache::put($this->progressKey, [
                    'total_rows' => 0,
                    'processed_rows' => 0,
                    'success_count' => 0,
                    'error_count' => 1,
                    'percentage' => 0,
                    'status' => 'failed',
                    'current_chunk' => 0,
                    'total_chunks' => 0,
                    'error_message' => $e->getMessage(),
                ], 600);
            }
        } finally {
            $this->importing = false;
            $this->showProgressBar = false;
        }
    }

    public function clearResults()
    {
        $this->importResult = null;
        $this->clearProgress();
        $this->reset(['file', 'showPreview', 'previewData', 'validationErrors']);
    }

    public function render()
    {
        return view('livewire.admin.bulk-student-upload');
    }
}
