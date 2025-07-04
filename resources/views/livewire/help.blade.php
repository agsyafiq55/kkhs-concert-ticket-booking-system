<div class="mx-auto sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="pb-6">
        <div class="flex items-center mb-2">
            <flux:icon.book-open-text class="w-9 h-9 mr-3 text-rose-500" />
            <flux:heading size="xl">Help & Documentation</flux:heading>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                <div class="p-4">
                    <flux:heading size="sm" class="mb-4 text-zinc-600 dark:text-zinc-400">Navigation</flux:heading>
                    <flux:navlist variant="outline">
                        @foreach($availableSections as $key => $section)
                        <flux:navlist.item
                            wire:click="setActiveSection('{{ $key }}')"
                            :current="$activeSection === '{{ $key }}'"
                            icon="{{ $section['icon'] }}"
                            class="cursor-pointer">
                            {{ $section['title'] }}
                        </flux:navlist.item>
                        @endforeach
                    </flux:navlist>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @if($activeSection === 'overview')
                    @include('livewire.help.sections.overview')
                    @elseif($activeSection === 'concerts')
                    @include('livewire.help.sections.concerts')
                    @elseif($activeSection === 'tickets')
                    @include('livewire.help.sections.tickets')
                    @elseif($activeSection === 'walk_in')
                    @include('livewire.help.sections.walk-in')
                    @elseif($activeSection === 'sales_monitoring')
                    @include('livewire.help.sections.sales-monitoring')
                    @elseif($activeSection === 'user_management')
                    @include('livewire.help.sections.user-management')
                    @elseif($activeSection === 'selling_tickets')
                    @include('livewire.help.sections.selling-tickets')
                    @elseif($activeSection === 'scanning_entry')
                    @include('livewire.help.sections.scanning-entry')
                    @elseif($activeSection === 'walk_in_sales')
                    @include('livewire.help.sections.walk-in-sales')
                    @elseif($activeSection === 'my_tickets')
                    @include('livewire.help.sections.my-tickets')
                    @elseif($activeSection === 'troubleshooting')
                    @include('livewire.help.sections.troubleshooting')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>