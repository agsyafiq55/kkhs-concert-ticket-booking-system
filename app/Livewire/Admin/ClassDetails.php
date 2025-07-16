<?php

namespace App\Livewire\Admin;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ClassDetails extends Component
{
    use WithPagination;

    public $classId;
    public $class;
    public $studentSearch = '';
    public $teacherSearch = '';
    public $showStudents = true;
    public $showTeachers = true;

    public function mount($classId)
    {
        // Check if user has permission to view class details
        if (!Gate::allows('manage classes')) {
            abort(403, 'You do not have permission to view class details.');
        }

        $this->classId = $classId;
        $this->class = SchoolClass::findOrFail($classId);
    }

    public function updatingStudentSearch()
    {
        $this->resetPage('students');
    }

    public function updatingTeacherSearch()
    {
        $this->resetPage('teachers');
    }

    public function toggleStudents()
    {
        $this->showStudents = !$this->showStudents;
    }

    public function toggleTeachers()
    {
        $this->showTeachers = !$this->showTeachers;
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
            
            session()->flash('message', $student->name . ' has been removed from ' . $this->class->display_name . '.');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while removing student from class.');
        }
    }

    public function removeTeacherFromClass($teacherId)
    {
        if (!Gate::allows('assign teacher classes')) {
            session()->flash('error', 'You do not have permission to modify teacher class assignments.');
            return;
        }

        try {
            $teacher = User::findOrFail($teacherId);
            $teacher->assignedClasses()->detach($this->classId);
            
            session()->flash('message', $teacher->name . ' has been removed from ' . $this->class->display_name . '.');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while removing teacher from class.');
        }
    }

    public function render()
    {
        // Get students in this class
        $students = $this->class->students()
            ->when($this->studentSearch, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->studentSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->studentSearch . '%')
                      ->orWhere('daftar_no', 'like', '%' . $this->studentSearch . '%');
                });
            })
            ->orderBy('name')
            ->paginate(20, ['*'], 'students');

        // Get teachers assigned to this class
        $teachers = $this->class->teachers()
            ->when($this->teacherSearch, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->teacherSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->teacherSearch . '%');
                });
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'teachers');

        return view('livewire.admin.class-details', [
            'students' => $students,
            'teachers' => $teachers,
        ]);
    }
} 