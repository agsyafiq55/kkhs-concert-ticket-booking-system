<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithMapping, SkipsEmptyRows
{
    use Importable, SkipsErrors, SkipsFailures;

    private $successCount = 0;
    private $errorCount = 0;
    private $createdUsers = [];
    private $skippedUsers = [];

    /**
     * Transform the row data before validation and model creation
     */
    public function map($row): array
    {
        return [
            'name' => $row['name'] ?? '',
            'email' => $row['email'] ?? '',
            'ic_number' => (string) ($row['ic_number'] ?? ''), // Ensure IC number is always a string
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            // Clean and prepare the data
            $name = trim($row['name'] ?? '');
            $email = trim($row['email'] ?? '');
            $icNumber = trim($row['ic_number'] ?? '');
            
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
                    'reason' => 'Email already exists'
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

            // Assign student role
            $user->assignRole('student');

            $this->successCount++;
            $this->createdUsers[] = [
                'name' => $user->name,
                'email' => $user->email,
                'ic_number' => $icNumber,
            ];

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
            'ic_number' => ['required', 'string', 'min:6'],  // Now always string due to mapping
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
            'ic_number.required' => 'IC Number is required',
            'ic_number.min' => 'IC Number must be at least 6 characters',
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
}
