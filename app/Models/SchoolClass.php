<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_level',
        'class_name',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the students that belong to this class.
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'class_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'student');
            });
    }

    /**
     * Get the teachers that are assigned to this class.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'teacher_classes', 'class_id', 'teacher_id')
            ->withTimestamps()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'teacher');
            });
    }

    /**
     * Get the full class name (e.g., "Form 1 AMANAH").
     */
    public function getFullNameAttribute(): string
    {
        return $this->form_level . ' ' . $this->class_name;
    }

    /**
     * Get a formatted display name for the class.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name;
    }

    /**
     * Check if this class is for Form 1-5.
     */
    public function isLowerForm(): bool
    {
        return in_array($this->form_level, ['1', '2', '3', '4', '5']);
    }

    /**
     * Check if this class is for Form 6.
     */
    public function isUpperForm(): bool
    {
        return $this->form_level === '6';
    }

    /**
     * Check if this class is for Peralihan.
     */
    public function isPeralihanForm(): bool
    {
        return $this->form_level === 'Peralihan';
    }

    /**
     * Get the count of students in this class.
     */
    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Get the count of teachers assigned to this class.
     */
    public function getTeacherCountAttribute(): int
    {
        return $this->teachers()->count();
    }

    /**
     * Scope to get only active classes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get classes by form level.
     */
    public function scopeByFormLevel($query, string $formLevel)
    {
        return $query->where('form_level', $formLevel);
    }

    /**
     * Scope to get lower form classes (Form 1-5).
     */
    public function scopeLowerForms($query)
    {
        return $query->whereIn('form_level', ['1', '2', '3', '4', '5']);
    }

    /**
     * Scope to get upper form classes (Form 6).
     */
    public function scopeUpperForms($query)
    {
        return $query->where('form_level', '6');
    }

    /**
     * Scope to get Peralihan classes.
     */
    public function scopePeralihanForms($query)
    {
        return $query->where('form_level', 'Peralihan');
    }

    /**
     * Get all form levels available in the system.
     */
    public static function getFormLevels(): array
    {
        return [
            '1',
            '2', 
            '3',
            '4',
            '5',
            '6',
            'Peralihan',
        ];
    }

    /**
     * Get class names for lower forms (Form 1-5).
     */
    public static function getLowerFormClassNames(): array
    {
        return [
            'AMANAH',
            'BESTARI',
            'CERIA',
            'DINAMIK',
            'INOVATIF',
            'KREATIF',
            'MULIA',
            'RAJIN',
            'SABAR',
            'TEKUN',
        ];
    }

    /**
     * Get class names for Form 6.
     */
    public static function getUpperFormClassNames(): array
    {
        return [
            'ATAS 1',
            'ATAS 2',
            'ATAS SAINS',
            'RENDAH 1',
            'RENDAH 2',
            'RENDAH SAINS',
        ];
    }

    /**
     * Get class names for Peralihan.
     */
    public static function getPeralihanClassNames(): array
    {
        return [
            'CERIA 1',
            'CERIA 2',
        ];
    }

    /**
     * Get class names for a specific form level.
     */
    public static function getClassNamesForFormLevel(string $formLevel): array
    {
        return match ($formLevel) {
            '1', '2', '3', '4', '5' => self::getLowerFormClassNames(),
            '6' => self::getUpperFormClassNames(),
            'Peralihan' => self::getPeralihanClassNames(),
            default => [],
        };
    }
} 