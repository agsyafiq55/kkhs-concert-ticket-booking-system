<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanResetPasswordContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use CanResetPassword, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'class_id',
        'daftar_no',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the ticket purchases where this user is the student.
     */
    public function ticketPurchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class, 'student_id');
    }

    /**
     * Get the ticket purchases assigned by this user as a teacher.
     */
    public function assignedTickets(): HasMany
    {
        return $this->hasMany(TicketPurchase::class, 'teacher_id');
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if the user is a teacher.
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if the user has admin-level access (admin or super-admin).
     */
    public function hasAdminAccess(): bool
    {
        return $this->hasRole(['admin', 'super-admin']);
    }

    /**
     * Check if the user can manage roles and permissions.
     */
    public function canManageRoles(): bool
    {
        return $this->hasPermissionTo('manage roles') || $this->isSuperAdmin();
    }

    /**
     * Get the class that this student belongs to.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the classes that this teacher is assigned to.
     */
    public function assignedClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'teacher_classes', 'teacher_id', 'class_id')
            ->withTimestamps();
    }

    /**
     * Check if the user has a class assigned (for students).
     */
    public function hasClass(): bool
    {
        return $this->class_id !== null;
    }

    /**
     * Get the class display name for students.
     */
    public function getClassDisplayNameAttribute(): ?string
    {
        return $this->schoolClass?->display_name;
    }

    /**
     * Check if this teacher is assigned to a specific class.
     */
    public function isAssignedToClass(int $classId): bool
    {
        return $this->assignedClasses()->where('classes.id', $classId)->exists();
    }

    /**
     * Check if this teacher can sell tickets to a specific student.
     * Teachers can only sell to students in their assigned classes.
     */
    public function canSellTicketsToStudent(User $student): bool
    {
        // Only teachers can sell tickets
        if (!$this->isTeacher()) {
            return false;
        }

        // Student must have a class assigned
        if (!$student->hasClass()) {
            return false;
        }

        // Check if teacher is assigned to the student's class
        return $this->isAssignedToClass($student->class_id);
    }

    /**
     * Get students that this teacher can sell tickets to.
     * Returns students from all classes assigned to this teacher.
     */
    public function getAssignableStudents()
    {
        if (!$this->isTeacher()) {
            return collect();
        }

        $assignedClassIds = $this->assignedClasses()->pluck('classes.id');

        return User::role('student')
            ->whereIn('class_id', $assignedClassIds)
            ->with('schoolClass')
            ->orderBy('name');
    }

    /**
     * Assign this teacher to a class.
     */
    public function assignToClass(int $classId): bool
    {
        if (!$this->isTeacher()) {
            return false;
        }

        if (!$this->isAssignedToClass($classId)) {
            $this->assignedClasses()->attach($classId);
            return true;
        }

        return false; // Already assigned
    }

    /**
     * Remove this teacher from a class.
     */
    public function removeFromClass(int $classId): bool
    {
        if (!$this->isTeacher()) {
            return false;
        }

        if ($this->isAssignedToClass($classId)) {
            $this->assignedClasses()->detach($classId);
            return true;
        }

        return false; // Not assigned
    }

    /**
     * Assign a student to a class.
     */
    public function assignStudentToClass(int $classId): bool
    {
        if (!$this->isStudent()) {
            return false;
        }

        $this->update(['class_id' => $classId]);
        return true;
    }

    /**
     * Remove a student from their current class.
     */
    public function removeStudentFromClass(): bool
    {
        if (!$this->isStudent()) {
            return false;
        }

        $this->update(['class_id' => null]);
        return true;
    }

    /**
     * Get the count of classes assigned to this teacher.
     */
    public function getAssignedClassCountAttribute(): int
    {
        return $this->assignedClasses()->count();
    }

    /**
     * Scope to get students from specific classes.
     */
    public function scopeInClasses($query, array $classIds)
    {
        return $query->whereIn('class_id', $classIds);
    }

    /**
     * Scope to get students without a class assigned.
     */
    public function scopeWithoutClass($query)
    {
        return $query->whereNull('class_id');
    }

    /**
     * Scope to get teachers assigned to specific classes.
     */
    public function scopeAssignedToClasses($query, array $classIds)
    {
        return $query->whereHas('assignedClasses', function ($q) use ($classIds) {
            $q->whereIn('classes.id', $classIds);
        });
    }
}
