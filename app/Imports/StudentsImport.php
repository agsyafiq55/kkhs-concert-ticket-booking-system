<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterChunk;
use Maatwebsite\Excel\Events\BeforeImport;

class StudentsImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToModel, WithHeadingRow, WithMapping, WithValidation, WithChunkReading, WithEvents
{
    use Importable, SkipsErrors, SkipsFailures;

    private $successCount = 0;

    private $errorCount = 0;

    private $createdUsers = [];

    private $skippedUsers = [];

    private $classCache = [];

    private $studentRoleId = null;

    private $createdUserIds = [];

    // Progress tracking variables
    private $progressKey = null;
    private $totalRows = 0;
    private $processedRows = 0;

    /**
     * Process data in chunks for better memory management
     */
    public function chunkSize(): int
    {
        return 200; // Reduce chunk size for better performance with password hashing
    }

    /**
     * Register events for batch processing and progress tracking
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $this->initializeProgress($event);
            },
            AfterChunk::class => function (AfterChunk $event) {
                $this->batchAssignStudentRole();
                $this->updateProgress($event);
            },
        ];
    }

    /**
     * Set the progress key for tracking import progress
     */
    public function setProgressKey(string $progressKey): void
    {
        $this->progressKey = $progressKey;
    }

    /**
     * Initialize progress tracking with estimated total rows
     */
    private function initializeProgress(BeforeImport $event): void
    {
        if (!$this->progressKey) {
            return;
        }

        // Get current progress from cache (should already have total rows)
        $currentProgress = Cache::get($this->progressKey);
        
        if ($currentProgress) {
            $this->totalRows = $currentProgress['total_rows'] ?? 0;
        } else {
            // Fallback - try to get total rows from the import
            try {
                $this->totalRows = $event->getReader()->getTotalRows()[0] ?? 0;
                
                // Subtract header row if it exists
                if ($this->totalRows > 0) {
                    $this->totalRows -= 1; // Remove header row from count
                }
            } catch (\Exception $e) {
                // If we can't get the total, start with 0 and estimate as we go
                $this->totalRows = 0;
            }
        }
        
        // Update progress to show processing has started
        Cache::put($this->progressKey, [
            'total_rows' => $this->totalRows,
            'processed_rows' => 0,
            'success_count' => 0,
            'error_count' => 0,
            'percentage' => 20, // Import has started
            'status' => 'processing',
            'current_chunk' => 0,
            'total_chunks' => $this->totalRows > 0 ? ceil($this->totalRows / $this->chunkSize()) : 0,
        ], 600); // Store for 10 minutes
    }

    /**
     * Update progress after each chunk
     */
    private function updateProgress(AfterChunk $event): void
    {
        if (!$this->progressKey) {
            return;
        }

        // Track actual processed rows based on success/error/skipped counts
        $actualProcessedRows = $this->successCount + $this->errorCount + count($this->skippedUsers);
        
        // Calculate progress based on what we know
        $currentChunk = ceil($actualProcessedRows / $this->chunkSize());
        
        // Calculate percentage based on actual totals if known
        if ($this->totalRows > 0) {
            $percentage = min(95, ($actualProcessedRows / $this->totalRows) * 100); // Cap at 95% until complete
            $totalChunks = ceil($this->totalRows / $this->chunkSize());
        } else {
            // Fallback for when we don't know the total - use chunk-based estimation
            $estimatedTotalChunks = max($currentChunk + 1, ceil($actualProcessedRows / $this->chunkSize()));
            $percentage = min(90, ($currentChunk / max($estimatedTotalChunks, 1)) * 100);
            $totalChunks = $estimatedTotalChunks;
        }

        // Update progress in cache
        Cache::put($this->progressKey, [
            'total_rows' => $this->totalRows > 0 ? $this->totalRows : $actualProcessedRows,
            'processed_rows' => $actualProcessedRows,
            'success_count' => $this->successCount,
            'error_count' => $this->errorCount,
            'percentage' => round($percentage, 1),
            'status' => 'processing',
            'current_chunk' => $currentChunk,
            'total_chunks' => $totalChunks,
        ], 600);
    }

    /**
     * Mark progress as completed
     */
    public function markProgressCompleted(): void
    {
        if (!$this->progressKey) {
            return;
        }

        $actualProcessedRows = $this->successCount + $this->errorCount + count($this->skippedUsers);

        Cache::put($this->progressKey, [
            'total_rows' => $this->totalRows > 0 ? $this->totalRows : $actualProcessedRows,
            'processed_rows' => $actualProcessedRows,
            'success_count' => $this->successCount,
            'error_count' => $this->errorCount,
            'percentage' => 100,
            'status' => 'completed',
            'current_chunk' => ceil($actualProcessedRows / $this->chunkSize()),
            'total_chunks' => ceil($actualProcessedRows / $this->chunkSize()),
        ], 600);
    }

    /**
     * Update progress during individual row processing
     */
    private function updateRowProgress(): void
    {
        if (!$this->progressKey) {
            return;
        }

        // Only update progress every 10 rows to avoid excessive cache writes
        static $lastUpdate = 0;
        $currentRowCount = $this->successCount + $this->errorCount + count($this->skippedUsers);
        
        if ($currentRowCount % 10 === 0 && $currentRowCount !== $lastUpdate) {
            $lastUpdate = $currentRowCount;
            
            // Get current progress from cache and update counts
            $currentProgress = Cache::get($this->progressKey);
            if ($currentProgress) {
                $currentProgress['success_count'] = $this->successCount;
                $currentProgress['error_count'] = $this->errorCount;
                $currentProgress['processed_rows'] = $currentRowCount;
                
                // Update percentage based on actual progress
                if ($this->totalRows > 0) {
                    $currentProgress['percentage'] = min(95, ($currentRowCount / $this->totalRows) * 100);
                }
                
                // Update cache with new counts
                Cache::put($this->progressKey, $currentProgress, 600);
            }
        }
    }

    /**
     * Transform the row data before validation and model creation
     */
    public function map($row): array
    {
        return [
            'name' => $row['name'] ?? '',
            'email' => $row['email'] ?? '',
            'daftar_no' => (string) ($row['daftar_no'] ?? ''), // Ensure Daftar No is always a string
            'class' => trim($row['class'] ?? ''), // Class information
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Clean and prepare the data
            $name = trim($row['name'] ?? '');
            $email = trim($row['email'] ?? '');
            $daftarNo = trim($row['daftar_no'] ?? '');
            $classInfo = trim($row['class'] ?? '');

            // Check if the row has any meaningful data (not completely empty)
            if (empty($name) && empty($email) && empty($daftarNo)) {
                // Skip rows with no meaningful data - don't count as error or success
                return null;
            }

            // Update progress to show we're processing rows
            $this->updateRowProgress();
            
            // Log that we're processing a row (for debugging)
            if ($this->progressKey && ($this->successCount + $this->errorCount) === 0) {
                // First row being processed
                Cache::put($this->progressKey, array_merge(Cache::get($this->progressKey, []), [
                    'status' => 'processing_rows',
                    'percentage' => 25,
                ]), 600);
            }

            // Convert email to lowercase
            $email = strtolower($email);

            // Check if user already exists with this email or Daftar No (single query for efficiency)
            $existingUser = User::where('email', $email)
                ->orWhere('daftar_no', $daftarNo)
                ->first();
                
            if ($existingUser) {
                $reason = $existingUser->email === $email ? 'Email already exists' : 'Daftar No already exists';
                
                // For large imports, only store first 100 skipped users to prevent memory issues
                if (count($this->skippedUsers) < 100) {
                    $this->skippedUsers[] = [
                        'name' => $name,
                        'email' => $email,
                        'reason' => $reason,
                    ];
                }

                return null; // Skip this user - already exists
            }

            // Find the class
            $classId = null;
            if (!empty($classInfo)) {
                $classId = $this->findClassByInfo($classInfo);
            }

            // Create the user with optimized password hashing for bulk imports
            // Use md5 for bulk imports (faster) - users can change password later if needed
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($daftarNo, ['rounds' => 4]), // Reduced rounds for faster bulk import
                'daftar_no' => $daftarNo, // Store Daftar No in database
                'class_id' => $classId, // Assign to class
                'email_verified_at' => now(), // Mark as verified for bulk uploads
            ]);

            // Collect user ID for batch role assignment
            $this->createdUserIds[] = $user->id;

            $this->successCount++;
            
            // For large imports, only store first 100 created users to prevent memory issues
            if (count($this->createdUsers) < 100) {
                $this->createdUsers[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'daftar_no' => $daftarNo,
                    'class' => $classInfo,
                    'class_id' => $classId,
                ];
            }

            return $user;
        } catch (\Exception $e) {
            $this->errorCount++;

            return null;
        }
    }

    /**
     * Define validation rules for each row
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'], // Removed unique constraint - handled in model()
            'daftar_no' => ['required', 'string', 'regex:/^\d{5}$/', 'unique:users,daftar_no'],  // Exactly 5 digits and unique
            'class' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Student name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists in the system',
            'daftar_no.required' => 'Daftar No is required',
            'daftar_no.regex' => 'Daftar No must be exactly 5 digits',
            'daftar_no.unique' => 'Daftar No already exists in the system',
            'class.required' => 'Class is required',
            'class.string' => 'Class must be a valid text format',
        ];
    }

    /**
     * Get the count of successfully created users
     */
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    /**
     * Get the count of errors
     */
    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    /**
     * Get the list of created users
     */
    public function getCreatedUsers(): array
    {
        return $this->createdUsers;
    }

    /**
     * Get the list of skipped users
     */
    public function getSkippedUsers(): array
    {
        return $this->skippedUsers;
    }

    /**
     * Find SchoolClass by class info string with caching for performance
     * Expected format: "Form Level Class Name" (e.g., "1 AMANAH", "6 ATAS 1", "Peralihan CERIA 1")
     */
    private function findClassByInfo(string $classInfo): ?int
    {
        if (empty($classInfo)) {
            return null;
        }

        // Check cache first
        if (isset($this->classCache[$classInfo])) {
            return $this->classCache[$classInfo];
        }

        // Parse the class info (e.g., "1 AMANAH" -> form_level: "1", class_name: "AMANAH")
        $parts = explode(' ', $classInfo, 2);
        
        if (count($parts) < 2) {
            $this->classCache[$classInfo] = null;
            return null;
        }

        $formLevel = $parts[0];
        $className = $parts[1];

        // Find the class
        $schoolClass = SchoolClass::where('form_level', $formLevel)
            ->where('class_name', $className)
            ->where('is_active', true)
            ->first();

        $result = $schoolClass ? $schoolClass->id : null;
        
        // Cache the result
        $this->classCache[$classInfo] = $result;
        
        return $result;
    }

    /**
     * Batch assign student role to all created users
     */
    public function batchAssignStudentRole()
    {
        if (empty($this->createdUserIds) || !$this->studentRoleId) {
            return;
        }

        // Batch insert role assignments
        $roleAssignments = [];
        foreach ($this->createdUserIds as $userId) {
            $roleAssignments[] = [
                'role_id' => $this->studentRoleId,
                'model_type' => 'App\Models\User',
                'model_id' => $userId,
            ];
        }

        if (!empty($roleAssignments)) {
            DB::table('model_has_roles')->insert($roleAssignments);
        }

        // Clear the array for next chunk
        $this->createdUserIds = [];
    }

    /**
     * Pre-load all active classes and student role to reduce database queries
     */
    public function __construct()
    {
        // Pre-cache all active classes for better performance
        $classes = SchoolClass::where('is_active', true)->get();
        foreach ($classes as $class) {
            $classInfo = $class->form_level . ' ' . $class->class_name;
            $this->classCache[$classInfo] = $class->id;
        }

        // Cache student role ID
        $this->studentRoleId = Role::where('name', 'student')->where('guard_name', 'web')->first()?->id;
    }

    /**
     * Handle any remaining role assignments when import is finished
     */
    public function __destruct()
    {
        $this->batchAssignStudentRole();
    }
}
