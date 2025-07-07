<div class="space-y-6 px-6">
    <div class="flex items-center mb-6">
        <flux:icon.user-plus variant="solid" class="w-9 h-9 mr-2" />
        <flux:heading size="xl">Bulk Student Account Registration</flux:heading>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <flux:callout icon="check-circle" variant="success">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if (session()->has('warning'))
        <flux:callout icon="exclamation-triangle" variant="warning">
            {{ session('warning') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout icon="exclamation-triangle" variant="danger">
            {{ session('error') }}
        </flux:callout>
    @endif

    <!-- Step 1: Download Template -->
    <div class="border bg-zinc-50 dark:bg-zinc-700 border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
        <flux:heading size="lg">Step 1: Download Template</flux:heading>
        <flux:text>
            Please download the template file to fill in the student's name, email, and IC number. The template file is in CSV format.
        </flux:text>
        <div class="mt-4">
            <flux:button href="{{ route('admin.bulk-student-upload.template') }}" variant="primary" icon="document-arrow-down">
                Download Template
            </flux:button>
        </div>
    </div>

    <!-- Step 2: Upload Template -->
    <div class="border bg-zinc-50 dark:bg-zinc-700 border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
        <flux:heading size="lg">Step 2: Upload Template</flux:heading>
        <flux:text>
            Upload the filled template file to create the student accounts.
        </flux:text>

        <div class="mt-4 border bg-zinc-60 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 rounded-lg p-4">
            <!-- File Upload -->
            <flux:field>
                <flux:description>
                    Maximum file size: 10MB. Empty rows will be automatically ignored.
                </flux:description>
                <flux:input type="file" wire:model="file" accept=".csv,.xlsx,.xls"/>
                <flux:error name="file" />
            </flux:field>

            <!-- Loading indicator for file processing -->
            <div wire:loading wire:target="file" class="mt-4 flex items-center justify-center py-8">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-rose-600"></div>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        Processing data, please wait...
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

     <!-- Step 3: Review Account Details -->
    @if($showPreview && !empty($previewData))
    <div class="border bg-zinc-50 dark:bg-zinc-700 border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
        <flux:heading size="lg">Step 3: Review Account Details</flux:heading>
        <flux:text>
            Review the account details before proceeding with account creation.
        </flux:text>
        
        <!-- Preview Table -->
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        @if(!empty($previewData['headers']))
                            @foreach($previewData['headers'] as $header)
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                {{ $header }}
                            </th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @if(!empty($previewData['rows']))
                        @foreach($previewData['rows'] as $row)
                        <tr>
                            @foreach($row as $cell)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $cell }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        @if(($previewData['total_rows'] ?? 0) > 0)
        <div class="mt-3">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                Showing all {{ $previewData['total_rows'] }} rows that will be processed.
            </flux:text>
        </div>
        @endif

        <!-- IC Number Validation Errors -->
        @if(!empty($validationErrors))
        <div class="mt-4">
            <flux:callout icon="exclamation-triangle" variant="danger">
                <flux:callout.heading>IC Number Validation Errors</flux:callout.heading>
                <flux:callout.text>
                    Please fix the following IC number format errors before proceeding:
                </flux:callout.text>
            </flux:callout>
            
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mt-3">
                <div class="space-y-2">
                    @foreach($validationErrors as $error)
                    <div class="text-sm text-red-700 dark:text-red-300">
                        <span class="font-medium">Row {{ $error['row'] }}:</span> {{ $error['error'] }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Import Button -->
        <div class="mt-6 flex gap-3">
            <flux:button wire:click="import" variant="primary" :disabled="$importing || !empty($validationErrors)">
                Create Accounts
            </flux:button>
            
            <flux:button wire:click="clearResults" variant="filled">
                Cancel
            </flux:button>
        </div>
    </div>
    @endif

    <!-- Import Results Section -->
    @if($importResult)
    <div class="border bg-zinc-50 dark:bg-zinc-700 border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
        <flux:heading size="lg">Account Creation Results</flux:heading>
        <flux:text>
            Review the account creation results to make sure all accounts are created correctly.
        </flux:text>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-4">
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ $importResult['success_count'] }}
                </div>
                <div class="text-sm text-green-700 dark:text-green-300">Accounts Created</div>
            </div>
            
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                    {{ $importResult['skipped_count'] ?? 0 }}
                </div>
                <div class="text-sm text-yellow-700 dark:text-yellow-300">Skipped (Already Exist)</div>
            </div>
            
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                    {{ $importResult['error_count'] }}
                </div>
                <div class="text-sm text-red-700 dark:text-red-300">Errors</div>
            </div>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $importResult['total_processed'] }}
                </div>
                <div class="text-sm text-blue-700 dark:text-blue-300">Total Processed</div>
            </div>
        </div>

        <!-- Successfully Created Users -->
        @if(!empty($importResult['created_users']))
        <div class="mb-6">
            <flux:heading size="sm" class="mb-3">Successfully Created Accounts</flux:heading>
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="space-y-2">
                    @foreach($importResult['created_users'] as $user)
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">{{ $user['name'] }}</span>
                        <span class="text-zinc-600 dark:text-zinc-400">{{ $user['email'] }}</span>
                        <span class="text-zinc-500 dark:text-zinc-500">Password: {{ $user['ic_number'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Skipped Users -->
        @if(!empty($importResult['skipped_users']) && count($importResult['skipped_users']) > 0)
        <div class="mb-6">
            <flux:heading size="sm" class="mb-3">Skipped Users (Already Exist)</flux:heading>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="space-y-2">
                    @foreach($importResult['skipped_users'] as $user)
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">{{ $user['name'] }}</span>
                        <span class="text-zinc-600 dark:text-zinc-400">{{ $user['email'] }}</span>
                        <span class="text-yellow-600 dark:text-yellow-400">{{ $user['reason'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Validation Errors -->
        @if(!empty($importResult['failures']) && count($importResult['failures']) > 0)
        <div class="mb-6">
            <flux:heading size="sm" class="mb-3">Validation Errors</flux:heading>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="space-y-2">
                    @foreach($importResult['failures'] as $failure)
                    <div class="text-sm">
                        <span class="font-medium">Row {{ $failure['row'] }}:</span>
                        <ul class="list-disc list-inside ml-4 text-red-700 dark:text-red-300">
                            @foreach($failure['errors'] as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- General Errors -->
        @if(!empty($importResult['errors']) && count($importResult['errors']) > 0)
        <div class="mb-6">
            <flux:heading size="sm" class="mb-3">General Errors</flux:heading>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="space-y-1">
                    @foreach($importResult['errors'] as $error)
                    <div class="text-sm text-red-700 dark:text-red-300">{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="flex gap-3">
            <flux:button wire:click="clearResults" variant="primary">
                Import Another File
            </flux:button>
        </div>
    </div>
    @endif
</div>
