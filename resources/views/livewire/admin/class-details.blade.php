<div class="py-6">
    <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.class-management') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        <flux:icon.arrow-left class="w-5 h-5" />
                    </a>
                    <div>
                        <flux:heading size="xl" class="flex items-center gap-3">
                            <div class="p-2 bg-rose-100 dark:bg-rose-900/20 rounded-lg">
                                <flux:icon.academic-cap />
                            </div>
                            {{ $class->display_name }}
                        </flux:heading>
                        <flux:text class="mt-1 text-gray-600 dark:text-gray-400">
                            Class details and member management
                        </flux:text>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($class->is_active)
                        <flux:badge color="green">Active</flux:badge>
                    @else
                        <flux:badge color="gray">Inactive</flux:badge>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Messages -->
        @if (session('message'))
        <div class="mb-6">
            <flux:callout icon="check-circle" variant="success">
                <flux:callout.heading>Success</flux:callout.heading>
                <flux:callout.text>{{ session('message') }}</flux:callout.text>
            </flux:callout>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-6">
            <flux:callout icon="exclamation-circle" variant="danger">
                <flux:callout.heading>Error</flux:callout.heading>
                <flux:callout.text>{{ session('error') }}</flux:callout.text>
            </flux:callout>
        </div>
        @endif

        <!-- Class Overview Stats -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <flux:icon.academic-cap class="w-6 h-6 text-blue-600" />
                    </div>
                    <div>
                        <flux:heading size="lg">{{ $students->total() }}</flux:heading>
                        <flux:text class="text-sm text-gray-600 dark:text-gray-400">Total Students</flux:text>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Section -->
        <div class="bg-white dark:bg-zinc-800 shadow-xl sm:rounded-xl border border-gray-200 dark:border-zinc-700 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg">
                            <flux:icon.user-group class="w-5 h-5" />
                        </div>
                        <div>
                            <flux:heading size="lg">Teacher</flux:heading>
                            <flux:text class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $teachers->total() }} teacher assigned to this class
                            </flux:text>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-64">
                            <flux:input 
                                icon="magnifying-glass"
                                wire:model.live.debounce.300ms="teacherSearch" 
                                placeholder="Search teachers..." 
                                size="sm"
                            />
                        </div>
                        <flux:button 
                            wire:click="toggleTeachers" 
                            variant="ghost" 
                            size="sm"
                            class="flex items-center gap-2"
                        >
                            @if($showTeachers)
                                <flux:icon.eye-slash class="w-4 h-4" />
                                Hide
                            @else
                                <flux:icon.eye class="w-4 h-4" />
                                Show
                            @endif
                        </flux:button>
                    </div>
                </div>
            </div>

            @if($showTeachers)
                <div class="overflow-x-auto">
                    @if($teachers->count() > 0)
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-zinc-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total Classes
                                    </th>
                                    @can('assign teacher classes')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                @foreach($teachers as $teacher)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <flux:heading size="sm">{{ $teacher->name }}</flux:heading>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ $teacher->email }}</flux:text>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <flux:badge color="green" class="text-xs">Teacher</flux:badge>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                                {{ $teacher->assignedClasses->count() }}
                                            </flux:text>
                                        </td>
                                        @can('assign teacher classes')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <flux:button 
                                                    wire:click="removeTeacherFromClass({{ $teacher->id }})"
                                                    wire:confirm="Are you sure you want to remove {{ $teacher->name }} from this class?"
                                                    variant="danger" 
                                                    size="xs"
                                                >
                                                    Remove
                                                </flux:button>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Teachers Pagination -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-700 border-t border-gray-200 dark:border-zinc-600">
                            {{ $teachers->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <flux:icon.user-group class="w-12 h-12 mx-auto text-zinc-400 mb-4" />
                            <flux:heading size="lg" class="text-zinc-500 dark:text-zinc-400 mb-2">
                                @if($teacherSearch)
                                    No teachers found
                                @else
                                    No teacher assigned
                                @endif
                            </flux:heading>
                            <flux:text class="text-zinc-400">
                                @if($teacherSearch)
                                    No teachers found matching "{{ $teacherSearch }}"
                                @else
                                    No teacher is currently assigned to this class
                                @endif
                            </flux:text>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Students Section -->
        <div class="bg-white dark:bg-zinc-800 shadow-xl sm:rounded-xl border border-gray-200 dark:border-zinc-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                            <flux:icon.academic-cap class="w-5 h-5" />
                        </div>
                        <div>
                            <flux:heading size="lg">Students</flux:heading>
                            <flux:text class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $students->total() }} students in this class
                            </flux:text>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-64">
                            <flux:input 
                                icon="magnifying-glass"
                                wire:model.live.debounce.300ms="studentSearch" 
                                placeholder="Search students..." 
                                size="sm"
                            />
                        </div>
                        <flux:button 
                            wire:click="toggleStudents" 
                            variant="ghost" 
                            size="sm"
                            class="flex items-center gap-2"
                        >
                            @if($showStudents)
                                <flux:icon.eye-slash class="w-4 h-4" />
                                Hide
                            @else
                                <flux:icon.eye class="w-4 h-4" />
                                Show
                            @endif
                        </flux:button>
                    </div>
                </div>
            </div>

            @if($showStudents)
                <div class="overflow-x-auto">
                    @if($students->count() > 0)
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-zinc-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Daftar No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                    @can('assign student classes')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                @foreach($students as $student)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                                {{ $student->daftar_no ?: 'N/A' }}
                                            </flux:text>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <flux:heading size="sm">{{ $student->name }}</flux:heading>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ $student->email }}</flux:text>
                                        </td>
                                        @can('assign student classes')
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <flux:button 
                                                    wire:click="removeStudentFromClass({{ $student->id }})"
                                                    wire:confirm="Are you sure you want to remove {{ $student->name }} from this class?"
                                                    variant="danger" 
                                                    size="xs"
                                                >
                                                    Remove
                                                </flux:button>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Students Pagination -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-700 border-t border-gray-200 dark:border-zinc-600">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <flux:icon.academic-cap class="w-12 h-12 mx-auto text-zinc-400 mb-4" />
                            <flux:heading size="lg" class="text-zinc-500 dark:text-zinc-400 mb-2">
                                @if($studentSearch)
                                    No students found
                                @else
                                    No students assigned
                                @endif
                            </flux:heading>
                            <flux:text class="text-zinc-400">
                                @if($studentSearch)
                                    No students found matching "{{ $studentSearch }}"
                                @else
                                    No students are currently assigned to this class
                                @endif
                            </flux:text>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div> 