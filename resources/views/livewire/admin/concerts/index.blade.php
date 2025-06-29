<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <flux:heading size="xl">Manage Concerts</flux:heading>
                    @can('create concerts')
                        <flux:button variant="primary" icon="plus" :href="route('admin.concerts.create')" wire:navigate>
                            Add Concert
                        </flux:button>
                    @endcan
                </div>
                
                @if (session('message'))
                    <flux:callout icon="check-circle" class="mb-4">
                        <flux:callout.heading>Success</flux:callout.heading>
                        <flux:callout.text>{{ session('message') }}</flux:callout.text>
                    </flux:callout>
                @endif

                @if (session('error'))
                    <flux:callout icon="exclamation-triangle" variant="danger" class="mb-4">
                        <flux:callout.heading>Error</flux:callout.heading>
                        <flux:callout.text>{{ session('error') }}</flux:callout.text>
                    </flux:callout>
                @endif
                
                <!-- Search -->
                <div class="mb-4">
                    <flux:input icon="magnifying-glass" wire:model.live="search" placeholder="Search concerts..." />
                </div>
                
                <!-- Concerts Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Venue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                                @if(auth()->user()->can('edit concerts') || auth()->user()->can('delete concerts'))
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($concerts as $concert)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $concert->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $concert->venue }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $concert->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $concert->start_time->format('g:i A') }} - {{ $concert->end_time->format('g:i A') }}</td>
                                    @if(auth()->user()->can('edit concerts') || auth()->user()->can('delete concerts'))
                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            @can('edit concerts')
                                                <flux:button size="sm" variant="filled" :href="route('admin.concerts.edit', $concert->id)" wire:navigate>
                                                    Edit
                                                </flux:button>
                                            @endcan
                                            @can('delete concerts')
                                                <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $concert->id }})">
                                                    Delete
                                                </flux:button>
                                            @endcan
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ (auth()->user()->can('edit concerts') || auth()->user()->can('delete concerts')) ? '5' : '4' }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        @can('create concerts')
                                            No concerts found. <a href="{{ route('admin.concerts.create') }}" wire:navigate class="text-blue-600 dark:text-blue-400 hover:underline">Create one to get started.</a>
                                        @else
                                            No concerts found.
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $concerts->links() }}
                </div>
                
                <!-- Delete Confirmation Modal -->
                @if ($concertIdToDelete)
                    <flux:modal name="delete-confirmation" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Confirm Deletion</flux:heading>
                                <flux:text class="mt-2">Are you sure you want to delete this concert? This action cannot be undone.</flux:text>
                            </div>
                            
                            <div class="flex justify-end space-x-2">
                                <flux:button variant="filled" wire:click="cancelDelete">Cancel</flux:button>
                                <flux:button variant="danger" wire:click="deleteConcert">Delete</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endif
            </div>
        </div>
    </div>
</div>
