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
            Please download the template file to fill in the student's name, email, Daftar No (5-digit registration number), and class. The template file is in CSV format.
        </flux:text>
        <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <flux:text class="text-sm text-blue-700 dark:text-blue-300">
                <strong>Class Format:</strong> Use "Form Level Class Name" format (e.g., "1 AMANAH", "2 BESTARI", "6 ATAS 1", "Peralihan CERIA 1")
            </flux:text>
        </div>
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
                    Maximum file size: 20MB. Empty rows will be automatically ignored. Large imports (2000+ students) are fully supported with automatic chunking and optimized processing. Please allow 5-15 minutes for very large imports.
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
                @if($previewData['is_large_import'] ?? false)
                    Showing first {{ $previewData['preview_limit'] }} of {{ $previewData['total_rows'] }} rows. 
                    <strong>Large import optimization:</strong> Processing will be done in chunks of 200 rows with optimized password hashing for maximum performance.
                @else
                    Showing all {{ $previewData['total_rows'] }} rows that will be processed.
                @endif
            </flux:text>
        </div>
        @endif

        <!-- Comprehensive Validation Errors -->
        @if(!empty($validationErrors))
        <div class="mt-4">
            <flux:callout icon="exclamation-triangle" variant="danger">
                <flux:callout.heading>❌ Validation Errors Found ({{ count($validationErrors) }})</flux:callout.heading>
                <flux:callout.text>
                    <strong>Import blocked!</strong> Please fix ALL validation errors in your Excel file before proceeding. No accounts will be created until all errors are resolved.
                </flux:callout.text>
            </flux:callout>
            
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mt-3 max-h-96 overflow-y-auto">
                <div class="space-y-3">
                    @php
                        $groupedErrors = collect($validationErrors)->groupBy('field');
                    @endphp
                    
                    @foreach($groupedErrors as $field => $fieldErrors)
                    <div class="border-b border-red-200 dark:border-red-700 pb-3 last:border-b-0">
                        <h4 class="font-semibold text-red-800 dark:text-red-200 mb-2">
                            <flux:icon.exclamation-triangle class="w-4 h-4 inline mr-1" />
                            {{ ucfirst(str_replace('_', ' ', $field)) }} Errors ({{ count($fieldErrors) }})
                        </h4>
                        <div class="space-y-1">
                            @foreach($fieldErrors as $error)
                            <div class="text-sm text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded p-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium">Row {{ $error['row'] }}:</span> 
                                        {{ $error['error'] }}
                                    </div>
                                    @if(!empty($error['value']))
                                    <div class="ml-2 text-xs text-red-600 dark:text-red-400 bg-red-200 dark:bg-red-800/50 px-2 py-1 rounded">
                                        "{{ $error['value'] }}"
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4 p-3 bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded">
                    <flux:text class="text-sm text-yellow-800 dark:text-yellow-200">
                        <strong>📝 Next Steps:</strong>
                        <ol class="list-decimal list-inside mt-1 space-y-1">
                            <li>Download your original Excel file</li>
                            <li>Fix all {{ count($validationErrors) }} errors listed above</li>
                            <li>Save the file and upload it again</li>
                            <li>Ensure all emails are unique and properly formatted</li>
                            <li>Verify all Daftar No values are exactly 5 digits</li>
                            <li>Check that all classes exist in the system</li>
                        </ol>
                    </flux:text>
                </div>
            </div>
        </div>
        @endif

        <!-- Import Button -->
        <div class="mt-6 flex gap-3">
            @if(!empty($validationErrors))
                <flux:button variant="danger" disabled>
                    <flux:icon.exclamation-triangle class="w-4 h-4 mr-2" />
                    Cannot Import - {{ count($validationErrors) }} Errors Found
                </flux:button>
                <flux:button wire:click="clearResults" variant="filled">
                    Clear & Try Again
                </flux:button>
            @else
                <flux:button wire:click="import" variant="primary" :disabled="$importing">
                    @if($importing)
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                        Creating Accounts...
                    @else
                        <flux:icon.user-plus class="w-4 h-4 mr-2" />
                        Create {{ $previewData['total_rows'] ?? 0 }} Student Accounts
                    @endif
                </flux:button>
                
                <flux:button wire:click="clearResults" variant="filled">
                    Cancel
                </flux:button>
            @endif
        </div>
    </div>
    @endif

    <!-- Progress Bar Section -->
    @if($showProgressBar)
    <div class="border bg-zinc-50 dark:bg-zinc-700 border-zinc-200 dark:border-zinc-600 rounded-lg p-6" wire:poll.1s="getProgress">
        <flux:heading size="lg">Import Progress</flux:heading>
        <flux:text>
            Creating student accounts... Please wait while we process your data.
        </flux:text>
        
        @if($importProgress)
        <div class="mt-4 space-y-4">
            <!-- Progress Bar -->
            <div class="bg-zinc-200 dark:bg-zinc-700 rounded-full h-4 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-full transition-all duration-500 ease-out" 
                     style="--progress-width: {{ ($importProgress['percentage'] ?? 0) }}%; width: var(--progress-width);"></div>
            </div>
            
            <!-- Progress Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $importProgress['percentage'] ?? 0 }}%
                    </div>
                    <div class="text-blue-700 dark:text-blue-300">Complete</div>
                </div>
                
                <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                        {{ $importProgress['processed_rows'] ?? 0 }} / {{ $importProgress['total_rows'] ?? 0 }}
                    </div>
                    <div class="text-green-700 dark:text-green-300">Rows Processed</div>
                </div>
                
                <div class="text-center p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                    <div class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">
                        {{ $importProgress['success_count'] ?? 0 }}
                    </div>
                    <div class="text-emerald-700 dark:text-emerald-300">Created</div>
                </div>
                
                <div class="text-center p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                    <div class="text-lg font-semibold text-amber-600 dark:text-amber-400">
                        {{ $importProgress['current_chunk'] ?? 0 }} / {{ $importProgress['total_chunks'] ?? 0 }}
                    </div>
                    <div class="text-amber-700 dark:text-amber-300">Chunks</div>
                </div>
            </div>
            
                         <!-- Processing Status -->
             <div class="flex items-center justify-center space-x-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                 <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                 <flux:text class="text-blue-700 dark:text-blue-300">
                     @if(($importProgress['status'] ?? '') === 'initializing')
                         Initializing import process...
                     @elseif(($importProgress['status'] ?? '') === 'starting')
                         Starting import, reading file...
                     @elseif(($importProgress['status'] ?? '') === 'reading_file')
                         Reading and analyzing file...
                     @elseif(($importProgress['status'] ?? '') === 'initializing_import')
                         File read successfully. Found {{ $importProgress['total_rows'] ?? 0 }} rows. Starting import...
                     @elseif(($importProgress['status'] ?? '') === 'processing_rows')
                         Processing individual rows... {{ $importProgress['processed_rows'] ?? 0 }} of {{ $importProgress['total_rows'] ?? 0 }} completed.
                     @elseif(($importProgress['status'] ?? '') === 'processing')
                         Processing chunk {{ $importProgress['current_chunk'] ?? 0 }} of {{ $importProgress['total_chunks'] ?? 0 }}... 
                         @if(($importProgress['total_rows'] ?? 0) > 1000)
                             Large import in progress, please be patient.
                         @endif
                     @elseif(($importProgress['status'] ?? '') === 'failed')
                         Import failed. Please check the error messages below.
                     @else
                         Processing data...
                     @endif
                 </flux:text>
             </div>
        </div>
        @else
        <div class="mt-4 flex items-center justify-center py-8">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Initializing import process...
                </flux:text>
            </div>
            </div>
    @endif
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
            <flux:heading size="sm" class="mb-3">
                Successfully Created Accounts
                @if($importResult['success_count'] > count($importResult['created_users']))
                    (Showing first {{ count($importResult['created_users']) }} of {{ $importResult['success_count'] }})
                @endif
            </flux:heading>
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="space-y-3">
                    @foreach($importResult['created_users'] as $user)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 text-sm border-b border-green-200 dark:border-green-700 pb-2 last:border-b-0">
                        <div>
                            <span class="font-medium text-green-800 dark:text-green-200">{{ $user['name'] }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-600 dark:text-zinc-400">{{ $user['email'] }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-500 dark:text-zinc-500">Password: {{ $user['daftar_no'] }}</span>
                        </div>
                        <div>
                            @if(!empty($user['class']))
                                <flux:badge color="emerald" size="sm">{{ $user['class'] }}</flux:badge>
                            @else
                                <span class="text-zinc-400 dark:text-zinc-600">No class</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Skipped Users -->
        @if(!empty($importResult['skipped_users']) && count($importResult['skipped_users']) > 0)
        <div class="mb-6">
            <flux:heading size="sm" class="mb-3">
                Skipped Users (Already Exist)
                @if($importResult['skipped_count'] > count($importResult['skipped_users']))
                    (Showing first {{ count($importResult['skipped_users']) }} of {{ $importResult['skipped_count'] }})
                @endif
            </flux:heading>
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
