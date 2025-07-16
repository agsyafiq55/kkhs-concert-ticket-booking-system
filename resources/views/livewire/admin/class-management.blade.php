<div class="py-6">
    <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Main Card -->
        <div class="bg-white dark:bg-zinc-800 shadow-xl sm:rounded-xl border border-gray-200 dark:border-zinc-700">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-200 dark:border-zinc-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <flux:heading size="xl" class="flex items-center gap-3">
                            <div class="p-2 bg-rose-100 dark:bg-rose-900/20 rounded-lg">
                                <flux:icon.academic-cap />
                            </div>
                            Class Management
                        </flux:heading>
                        <flux:text class="mt-1 text-gray-600 dark:text-gray-400">
                            Manage school classes, assign teachers to classes, and assign students to their classes.
                        </flux:text>
                    </div>
                </div>
            </div>

            <!-- Status Messages -->
            @if (session('message'))
            <div class="px-6 pt-6">
                <flux:callout icon="check-circle" variant="success">
                    <flux:callout.heading>Success</flux:callout.heading>
                    <flux:callout.text>{{ session('message') }}</flux:callout.text>
                </flux:callout>
            </div>
            @endif

            @if (session('error'))
            <div class="px-6 pt-6">
                <flux:callout icon="exclamation-circle" variant="danger">
                    <flux:callout.heading>Error</flux:callout.heading>
                    <flux:callout.text>{{ session('error') }}</flux:callout.text>
                </flux:callout>
            </div>
            @endif

            <!-- Class Overview Section -->
            <div class="px-6 py-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <flux:icon.layout-grid class="w-5 h-5" />
                    </div>
                    <flux:heading size="lg">Class Overview</flux:heading>
                </div>

                <!-- Filters -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>Search Classes</flux:label>
                            <flux:input 
                                icon="magnifying-glass" 
                                wire:model.live.debounce.300ms="search" 
                                placeholder="Search classes..." 
                            />
                        </flux:field>
                        <flux:field>
                            <flux:label>Filter by Form Level</flux:label>
                            <flux:select wire:model.live="formLevelFilter">
                                <flux:select.option value="">All Form Levels</flux:select.option>
                                @foreach($formLevels as $level)
                                    <flux:select.option value="{{ $level }}">{{ $level }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </flux:field>
                    </div>
                </div>

                <!-- Classes Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @forelse($classes as $class)
                        <a href="{{ route('admin.class-details', $class->id) }}" class="block">
                            <div class="bg-white dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 rounded-lg p-6 hover:shadow-md transition-all cursor-pointer hover:border-rose-300 dark:hover:border-rose-600">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                                            {{ $class->display_name }}
                                        </flux:heading>
                                    </div>
                                    @if($class->is_active)
                                        <flux:badge color="green">Active</flux:badge>
                                    @else
                                        <flux:badge color="gray">Inactive</flux:badge>
                                    @endif
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Students:</span>
                                        <flux:badge color="blue">{{ $class->students_count }}</flux:badge>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Teachers:</span>
                                        <flux:badge color="lime">{{ $class->teachers_count }}</flux:badge>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-zinc-600">
                                    <flux:text class="text-xs text-rose-600 dark:text-rose-400 font-medium">
                                        <flux:icon.arrow-right class="w-3 h-3 inline mr-1" />
                                        Click to view details
                                    </flux:text>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <flux:icon.academic-cap class="w-12 h-12 mx-auto text-zinc-400 mb-4" />
                            <flux:heading size="lg" class="text-zinc-500 dark:text-zinc-400 mb-2">No Classes Found</flux:heading>
                            <flux:text class="text-zinc-400">No classes match your current filters.</flux:text>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $classes->links() }}
                </div>
            </div>

            <!-- Teacher Assignment Section -->
            <div class="border-t border-gray-200 dark:border-zinc-700 px-6 py-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <flux:icon.user-group class="w-5 h-5" />
                    </div>
                    <flux:heading size="lg">Teacher Assignments</flux:heading>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Teachers List -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <flux:heading size="md">Teachers</flux:heading>
                            <div class="w-64">
                                <flux:input 
                                    icon="magnifying-glass"
                                    wire:model.live.debounce.300ms="teacherSearch" 
                                    placeholder="Search teachers..." 
                                />
                            </div>
                        </div>

                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($teachers as $teacher)
                                <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600 p-4 hover:bg-gray-100 dark:hover:bg-zinc-600 transition-colors cursor-pointer
                                            {{ $selectedTeacherId === $teacher->id ? 'ring-2 ring-rose-500 bg-rose-50 dark:bg-rose-900/20' : '' }}"
                                     wire:click="selectTeacher({{ $teacher->id }})">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <flux:heading size="sm">{{ $teacher->name }}</flux:heading>
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ $teacher->email }}</flux:text>
                                            @if($teacher->assignedClasses->count() > 0)
                                                <div class="mt-2 flex flex-wrap gap-1">
                                                    @foreach($teacher->assignedClasses as $class)
                                                        <flux:badge color="blue" class="text-xs">{{ $class->display_name }}</flux:badge>
                                                    @endforeach
                                                </div>
                                            @else
                                                <flux:text class="text-xs text-zinc-400 mt-1">No classes assigned</flux:text>
                                            @endif
                                        </div>
                                        @if($teacher->assignedClasses->count() > 0)
                                            <flux:badge color="green">{{ $teacher->assignedClasses->count() }} classes</flux:badge>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <flux:icon.user-group class="w-8 h-8 mx-auto text-zinc-400 mb-2" />
                                    <flux:text class="text-zinc-400">No teachers found</flux:text>
                                </div>
                            @endforelse
                        </div>

                        <!-- Teachers Pagination -->
                        <div class="mt-4">
                            {{ $teachers->links() }}
                        </div>
                    </div>

                    <!-- Class Assignment Panel -->
                    <div class="space-y-4">
                        @if($selectedTeacher)
                            <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600 p-6">
                                <flux:heading size="md" class="mb-4">
                                    Assign Classes to {{ $selectedTeacher->name }}
                                </flux:heading>

                                <form wire:submit.prevent="updateTeacherClasses">
                                    <div class="space-y-4">
                                        @foreach($allClasses->groupBy('form_level') as $formLevel => $levelClasses)
                                            <div>
                                                <flux:heading size="sm" class="mb-2 text-zinc-700 dark:text-zinc-300">{{ $formLevel }}</flux:heading>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    @foreach($levelClasses as $class)
                                                        <label class="flex items-center space-x-2 p-2 rounded border border-gray-200 dark:border-zinc-600 hover:bg-white dark:hover:bg-zinc-600 transition-colors">
                                                            <flux:checkbox 
                                                                wire:model="selectedTeacherClasses" 
                                                                value="{{ $class->id }}" />
                                                            <span class="text-sm">{{ $class->class_name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="flex gap-2 pt-4">
                                            <flux:button type="submit" variant="primary">Update Assignments</flux:button>
                                            <flux:button type="button" wire:click="resetSelection" variant="ghost">Cancel</flux:button>
                                        </div>
                                    </div>
                                </form>

                                @error('teacher')
                                    <flux:text class="mt-2 text-red-600 dark:text-red-400 text-sm">{{ $message }}</flux:text>
                                @enderror
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-zinc-600 p-8 text-center">
                                <flux:icon.user-group class="w-12 h-12 mx-auto text-zinc-400 mb-4" />
                                <flux:heading size="md" class="text-zinc-500 dark:text-zinc-400 mb-2">Select a Teacher</flux:heading>
                                <flux:text class="text-zinc-400">Choose a teacher from the list to assign them to classes.</flux:text>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Student Assignment Section -->
            <div class="border-t border-gray-200 dark:border-zinc-700 px-6 py-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <flux:icon.academic-cap class="w-5 h-5" />
                    </div>
                    <flux:heading size="lg">Student Assignments</flux:heading>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Students List -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Search Students</flux:label>
                                <flux:input 
                                    icon="magnifying-glass"
                                    wire:model.live.debounce.300ms="studentSearch" 
                                    placeholder="Search students..." 
                                />
                            </flux:field>
                            <flux:field>
                                <flux:label>Filter by Class</flux:label>
                                <flux:select wire:model.live="studentClassFilter">
                                    <flux:select.option value="">All Students</flux:select.option>
                                    <flux:select.option value="no-class">No Class Assigned</flux:select.option>
                                    @foreach($allClasses as $class)
                                        <flux:select.option value="{{ $class->id }}">{{ $class->display_name }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>

                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($students as $student)
                                <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600 p-4 hover:bg-gray-100 dark:hover:bg-zinc-600 transition-colors cursor-pointer
                                            {{ $selectedStudentId === $student->id ? 'ring-2 ring-rose-500 bg-rose-50 dark:bg-rose-900/20' : '' }}"
                                     wire:click="selectStudent({{ $student->id }})">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <flux:heading size="sm">{{ $student->name }}</flux:heading>
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ $student->email }}</flux:text>
                                            <div class="mt-1">
                                                @if($student->schoolClass)
                                                    <flux:badge color="blue" class="text-xs">{{ $student->schoolClass->display_name }}</flux:badge>
                                                @else
                                                    <flux:badge color="gray" class="text-xs">No Class</flux:badge>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <flux:icon.academic-cap class="w-8 h-8 mx-auto text-zinc-400 mb-2" />
                                    <flux:text class="text-zinc-400">No students found</flux:text>
                                </div>
                            @endforelse
                        </div>

                        <!-- Students Pagination -->
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    </div>

                    <!-- Class Assignment Panel -->
                    <div class="space-y-4">
                        @if($selectedStudent)
                            <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border border-gray-200 dark:border-zinc-600 p-6">
                                <flux:heading size="md" class="mb-4">
                                    Assign Class to {{ $selectedStudent->name }}
                                </flux:heading>

                                <form wire:submit.prevent="updateStudentClass">
                                    <div class="space-y-4">
                                        <flux:field>
                                            <flux:label>Select Class</flux:label>
                                            <flux:select wire:model="selectedStudentClass">
                                                <flux:select.option value="">No Class</flux:select.option>
                                                @foreach($allClasses->groupBy('form_level') as $formLevel => $levelClasses)
                                                    <optgroup label="{{ $formLevel }}">
                                                        @foreach($levelClasses as $class)
                                                            <flux:select.option value="{{ $class->id }}">{{ $class->class_name }}</flux:select.option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </flux:select>
                                        </flux:field>

                                        <div class="flex gap-2 pt-4">
                                            <flux:button type="submit" variant="primary">Update Assignment</flux:button>
                                            <flux:button type="button" wire:click="resetSelection" variant="ghost">Cancel</flux:button>
                                        </div>
                                    </div>
                                </form>

                                @error('student')
                                    <flux:text class="mt-2 text-red-600 dark:text-red-400 text-sm">{{ $message }}</flux:text>
                                @enderror

                                @if($selectedStudent->schoolClass)
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-zinc-600">
                                        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Current Assignment:</flux:text>
                                        <div class="flex items-center justify-between">
                                            <flux:badge color="blue">{{ $selectedStudent->schoolClass->display_name }}</flux:badge>
                                            <flux:button 
                                                wire:click="removeStudentFromClass({{ $selectedStudent->id }})"
                                                wire:confirm="Are you sure you want to remove this student from their class?"
                                                variant="danger" 
                                                size="xs">
                                                Remove from Class
                                            </flux:button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-zinc-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-zinc-600 p-8 text-center">
                                <flux:icon.academic-cap class="w-12 h-12 mx-auto text-zinc-400 mb-4" />
                                <flux:heading size="md" class="text-zinc-500 dark:text-zinc-400 mb-2">Select a Student</flux:heading>
                                <flux:text class="text-zinc-400">Choose a student from the list to assign them to a class.</flux:text>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 