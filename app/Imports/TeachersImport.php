<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeachersImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToModel, WithHeadingRow, WithMapping, WithValidation
{
    use Importable, SkipsErrors, SkipsFailures;

    private $successCount = 0;

    private $errorCount = 0;

    private $createdUsers = [];

    private $skippedUsers = [];

    private $classAssignmentErrors = [];

    /**
     * Transform the row data before validation and model creation
     */
    public function map($row): array
    {
        return [
            'name' => $row['name'] ?? '',
            'email' => $row['email'] ?? '',
            'ic_number' => (string) ($row['ic_number'] ?? ''), // Ensure IC number is always a string
            'assigned_classes' => $row['assigned_classes'] ?? '', // Comma-separated class names
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
            $icNumber = trim($row['ic_number'] ?? '');
            $assignedClasses = trim($row['assigned_classes'] ?? '');

            // Check if the row has any meaningful data (not completely empty)
            if (empty($name) && empty($email) && empty($icNumber)) {
                // Skip rows with no meaningful data - don't count as error or success
                return null;
            }

            // Convert email to lowercase
            $email = strtolower($email);

            // Check if user already exists with this email
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                $this->skippedUsers[] = [
                    'name' => $name,
                    'email' => $email,
                    'reason' => 'Email already exists',
                ];

                return null; // Skip this user - already exists
            }

            // Create the user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($icNumber), // Use IC number as password
                'email_verified_at' => now(), // Mark as verified for bulk uploads
            ]);

            // Assign teacher role
            $user->assignRole('teacher');

            // Handle class assignments if provided
            $assignedClassInfo = [];
            if (!empty($assignedClasses)) {
                $assignedClassInfo = $this->assignClassesToTeacher($user, $assignedClasses);
            }

            $this->successCount++;
            $this->createdUsers[] = [
                'name' => $user->name,
                'email' => $user->email,
                'ic_number' => $icNumber,
                'assigned_classes' => $assignedClassInfo,
            ];

            return $user;
        } catch (\Exception $e) {
            $this->errorCount++;

            return null;
        }
    }

    /**
     * Assign classes to a teacher based on comma-separated class names
     */
    private function assignClassesToTeacher(User $teacher, string $classNames): array
    {
        $assignedClasses = [];
        $errors = [];

        // Split by comma and clean up
        $classNameArray = array_map('trim', explode(',', $classNames));
        $classNameArray = array_filter($classNameArray); // Remove empty values

        foreach ($classNameArray as $className) {
            // Try to find the class by parsing the full name (e.g., "1 AMANAH")
            $schoolClass = null;
            
            // First try to match the exact class name as provided
            $schoolClass = SchoolClass::where('class_name', $className)->first();
            
            // If not found, try to parse it as a full name (e.g., "1 AMANAH")
            if (!$schoolClass && preg_match('/^(.+?)\s+(.+)$/', $className, $matches)) {
                $formLevel = trim($matches[1]);
                $classNameOnly = trim($matches[2]);
                
                $schoolClass = SchoolClass::where('form_level', $formLevel)
                    ->where('class_name', $classNameOnly)
                    ->first();
            }
            
            // If still not found, try to search all classes and match against their full name
            if (!$schoolClass) {
                $allClasses = SchoolClass::all();
                foreach ($allClasses as $class) {
                    if ($class->full_name === $className) {
                        $schoolClass = $class;
                        break;
                    }
                }
            }

            if ($schoolClass) {
                // Check if teacher is already assigned to this class
                if (!$teacher->assignedClasses()->where('class_id', $schoolClass->id)->exists()) {
                    $teacher->assignedClasses()->attach($schoolClass->id);
                    $assignedClasses[] = $schoolClass->full_name;
                } else {
                    $assignedClasses[] = $schoolClass->full_name . ' (already assigned)';
                }
            } else {
                $errors[] = "Class '{$className}' not found";
            }
        }

        // Store errors for later reporting
        if (!empty($errors)) {
            $this->classAssignmentErrors[] = [
                'teacher_name' => $teacher->name,
                'teacher_email' => $teacher->email,
                'errors' => $errors,
            ];
        }

        return [
            'assigned' => $assignedClasses,
            'errors' => $errors,
        ];
    }

    /**
     * Define validation rules for each row
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'], // Removed unique constraint - handled in model()
            'ic_number' => ['required', 'string', 'min:6'],  // Now always string due to mapping
            'assigned_classes' => ['nullable', 'string'], // Optional comma-separated class names
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Teacher name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists in the system',
            'ic_number.required' => 'IC Number is required',
            'ic_number.min' => 'IC Number must be at least 6 characters',
            'assigned_classes.string' => 'Assigned classes must be text (comma-separated)',
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
     * Get the list of class assignment errors
     */
    public function getClassAssignmentErrors(): array
    {
        return $this->classAssignmentErrors;
    }
} 