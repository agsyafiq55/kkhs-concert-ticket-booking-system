<?php

namespace App\Livewire\Admin;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ClassManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $formLevelFilter = '';
    
    // Teacher assignment properties
    public $selectedTeacherId = null;
    public $teacherSearch = '';
    public $selectedTeacherClasses = [];

    // Student assignment properties  
    public $selectedStudentId = null;
    public $studentSearch = '';
    public $selectedStudentClass = null;

    // Class filter for student assignment
    public $studentClassFilter = '';

    public function mount()
    {
        // Check if user has permission to manage classes
        if (!Gate::allows('manage classes')) {
            abort(403, 'You do not have permission to manage classes.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTeacherSearch()
    {
        $this->resetPage();
    }

    public function updatingStudentSearch()
    {
        $this->resetPage();
    }

    public function updatingFormLevelFilter()
    {
        $this->resetPage();
    }

    public function updatingStudentClassFilter()
    {
        $this->resetPage();
    }

    public function resetSelection()
    {
        $this->selectedTeacherId = null;
        $this->selectedTeacherClasses = [];
        $this->selectedStudentId = null;
        $this->selectedStudentClass = null;
        $this->resetValidation();
    }

    public function selectTeacher($teacherId)
    {
        $this->selectedTeacherId = $teacherId;
        
        // Load teacher's current class assignments
        $teacher = User::with('assignedClasses')->find($teacherId);
        if ($teacher) {
            $this->selectedTeacherClasses = $teacher->assignedClasses->pluck('id')->toArray();
        }
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudentId = $studentId;
        
        // Load student's current class assignment
        $student = User::find($studentId);
        if ($student) {
            $this->selectedStudentClass = $student->class_id;
        }
    }

    public function updateTeacherClasses()
    {
        if (!Gate::allows('assign teacher classes')) {
            session()->flash('error', 'You do not have permission to assign teacher classes.');
            return;
        }

        if (!$this->selectedTeacherId) {
            $this->addError('teacher', 'Please select a teacher first.');
            return;
        }

        try {
            $teacher = User::findOrFail($this->selectedTeacherId);
            
            if (!$teacher->isTeacher()) {
                $this->addError('teacher', 'Selected user is not a teacher.');
                return;
            }

            // Sync the teacher's class assignments
            $teacher->assignedClasses()->sync($this->selectedTeacherClasses);

            session()->flash('message', 'Teacher class assignments updated successfully for ' . $teacher->name . '.');
            
            $this->resetSelection();

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating teacher class assignments: ' . $e->getMessage());
        }
    }

    public function updateStudentClass()
    {
        if (!Gate::allows('assign student classes')) {
            session()->flash('error', 'You do not have permission to assign student classes.');
            return;
        }

        if (!$this->selectedStudentId) {
            $this->addError('student', 'Please select a student first.');
            return;
        }

        try {
            $student = User::findOrFail($this->selectedStudentId);
            
            if (!$student->isStudent()) {
                $this->addError('student', 'Selected user is not a student.');
                return;
            }

            // Update the student's class assignment
            $student->update(['class_id' => $this->selectedStudentClass]);

            $className = $this->selectedStudentClass ? 
                SchoolClass::find($this->selectedStudentClass)->display_name : 'No Class';

            session()->flash('message', 'Student class assignment updated successfully for ' . $student->name . ' → ' . $className);
            
            $this->resetSelection();

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating student class assignment: ' . $e->getMessage());
        }
    }

    public function removeTeacherFromClass($teacherId, $classId)
    {
        if (!Gate::allows('assign teacher classes')) {
            session()->flash('error', 'You do not have permission to modify teacher class assignments.');
            return;
        }

        try {
            $teacher = User::findOrFail($teacherId);
            $class = SchoolClass::findOrFail($classId);
            
            $teacher->assignedClasses()->detach($classId);
            
            session()->flash('message', $teacher->name . ' removed from ' . $class->display_name . '.');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while removing teacher from class.');
        }
    }

    public function removeStudentFromClass($studentId)
    {
        if (!Gate::allows('assign student classes')) {
            session()->flash('error', 'You do not have permission to modify student class assignments.');
            return;
        }

        try {
            $student = User::findOrFail($studentId);
            $student->update(['class_id' => null]);
            
            session()->flash('message', $student->name . ' removed from their class.');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while removing student from class.');
        }
    }

    public function render()
    {
        // Get all classes with student and teacher counts for overview
        $classesQuery = SchoolClass::query()
            ->withCount(['students', 'teachers'])
            ->when($this->formLevelFilter, function ($query) {
                return $query->where('form_level', $this->formLevelFilter);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('form_level', 'like', '%' . $this->search . '%')
                      ->orWhere('class_name', 'like', '%' . $this->search . '%');
                });
            });

        $classes = $classesQuery->orderBy('form_level')->orderBy('class_name')->paginate(15, ['*'], 'classes');

        // Get teachers for assignment
        $teachersQuery = User::role('teacher')
            ->with('assignedClasses')
            ->when($this->teacherSearch, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->teacherSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->teacherSearch . '%');
                });
            });

        $teachers = $teachersQuery->orderBy('name')->paginate(10, ['*'], 'teachers');

        // Get students for assignment
        $studentsQuery = User::role('student')
            ->with('schoolClass')
            ->when($this->studentSearch, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->studentSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->studentSearch . '%');
                });
            })
            ->when($this->studentClassFilter, function ($query) {
                if ($this->studentClassFilter === 'no-class') {
                    return $query->whereNull('class_id');
                } else {
                    return $query->where('class_id', $this->studentClassFilter);
                }
            });

        $students = $studentsQuery->orderBy('name')->paginate(10, ['*'], 'students');

        return view('livewire.admin.class-management', [
            'classes' => $classes,
            'formLevels' => SchoolClass::getFormLevels(),
            'teachers' => $teachers,
            'students' => $students,
            'allClasses' => SchoolClass::active()->orderBy('form_level')->orderBy('class_name')->get(),
            'selectedTeacher' => $this->selectedTeacherId ? User::with('assignedClasses')->find($this->selectedTeacherId) : null,
            'selectedStudent' => $this->selectedStudentId ? User::with('schoolClass')->find($this->selectedStudentId) : null,
        ]);
    }
} 