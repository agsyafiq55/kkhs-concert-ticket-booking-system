<div class="space-y-6">
    <div>
        <flux:heading size="lg">Bulk Student Account Creation</flux:heading>
        <flux:text class="mt-2">Upload a CSV or Excel file to create multiple student accounts at once. IC numbers will be used as temporary passwords.</flux:text>
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

    <!-- File Upload Section -->
    <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
        <div class="mb-4 flex items-center justify-between">
            <flux:heading size="md">Upload Student Data</flux:heading>
            <flux:button href="{{ route('admin.bulk-student-upload.template') }}" variant="primary" icon="document-arrow-down">
                Download Template
            </flux:button>
        </div>

        <div class="space-y-4">
            <!-- File Upload -->
            <flux:field>
                <flux:label>Select CSV or Excel File</flux:label>
                <flux:description>
                    Upload a file with columns: name, email, ic_number. Maximum file size: 10MB. Empty rows will be automatically ignored.
                </flux:description>
                <input type="file" wire:model="file" accept=".csv,.xlsx,.xls"
                    class="block w-full text-sm text-zinc-900 border border-zinc-300 rounded-lg cursor-pointer bg-zinc-50 dark:text-zinc-400 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 focus:outline-none">
                <flux:error name="file" />
            </flux:field>

            <!-- Required Format Information -->
            <flux:callout icon="light-bulb">
                <flux:callout.text>
                    <strong>How to use:</strong><br>
                    • Step 1: Download the template file<br>
                    • Step 2: Fill in the template with the student's name, email, and IC number<br>
                    • Step 3: Upload the template file on here<br>
                    • Step 4: The system will automatically create the student accounts<br><br>
                    <strong>Important:</strong> After student accounts are created, make sure to check if all information is correct.
                </flux:callout.text>
            </flux:callout>
        </div>
    </div>

    <!-- File Preview Section -->
    @if($showPreview && !empty($previewData))
    <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
        <flux:heading size="md" class="mb-4">File Preview</flux:heading>
        
        <div class="mb-4">
            <flux:text>
                <strong>Total rows to process:</strong> {{ $previewData['total_rows'] ?? 0 }}
            </flux:text>
        </div>

        <!-- Preview Table -->
        <div class="overflow-x-auto">
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

        @if(($previewData['total_rows'] ?? 0) > 5)
        <div class="mt-3">
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                Showing first 5 rows. {{ $previewData['total_rows'] - 5 }} more rows will be processed.
            </flux:text>
        </div>
        @endif

        <!-- Import Button -->
        <div class="mt-6 flex gap-3">
            <flux:button wire:click="import" variant="primary" :disabled="$importing">
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
    <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
        <flux:heading size="md" class="mb-4">Import Results</flux:heading>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
