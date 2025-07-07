<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <div class="mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('admin.tickets') }}" wire:navigate>Tickets</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Create Ticket</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg border border-zinc-200 dark:border-zinc-600">
            <div class="p-8">
                <!-- Header Section -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
                    <div>
                        <flux:heading size="xl" class="mb-2">Create Ticket</flux:heading>
                        <flux:text>
                            Add a new ticket type for your concert
                        </flux:text>
                    </div>
                    <flux:button variant="subtle" :href="route('admin.tickets')" wire:navigate>
                        Back to Tickets
                    </flux:button>
                </div>
                
                <form wire:submit="save" class="space-y-8">
                    <!-- Concert Selection Section -->
                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Concert</flux:label>
                            <flux:description>Select the concert this ticket belongs to</flux:description>
                            <flux:select 
                                wire:model="concert_id" 
                                :error="$errors->first('concert_id')"
                                required
                            >
                                <flux:select.option value="">Choose a concert...</flux:select.option>
                                @foreach ($concerts as $concert)
                                    <flux:select.option value="{{ $concert->id }}">
                                        {{ $concert->title }} - {{ $concert->date->format('M d, Y') }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="concert_id" />
                        </flux:field>
                    </div>

                    <flux:separator />

                    <!-- Ticket Details Section -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Ticket Type</flux:label>
                                <flux:description>
                                    Give a name for this ticket type (e.g General Admission, Student)
                                </flux:description>
                                <flux:input 
                                    wire:model="ticket_type" 
                                    placeholder="e.g., VIP, Standard, Student"
                                    :error="$errors->first('ticket_type')"
                                    required
                                />
                                <flux:error name="ticket_type" />
                            </flux:field>
                            
                            <flux:field>
                                <flux:label>Price (RM)</flux:label>
                                <flux:description>
                                    Set the price for this ticket type in Malaysian Ringgit
                                </flux:description>
                                <flux:input 
                                    type="number" 
                                    wire:model="price" 
                                    placeholder="0.00"
                                    min="0"
                                    step="0.01"
                                    :error="$errors->first('price')"
                                    required
                                />
                                <flux:error name="price" />
                            </flux:field>
                        </div>
                    </div>

                    <flux:separator />

                    <!-- Availability Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <flux:tooltip content="This affects both online and walk-in ticket sales">
                                <flux:badge color="lime">Important</flux:badge>
                            </flux:tooltip>
                        </div>
                        
                        <flux:field>
                            <flux:label>Total Quantity Available</flux:label>
                            <flux:description>
                                Enter the total number of tickets available for this type. This includes both online and walk-in sales.
                                <br><strong>Note:</strong> If you plan to sell walk-in tickets, include them in this total count.
                            </flux:description>
                            <flux:input 
                                type="number" 
                                wire:model="quantity_available" 
                                placeholder="100"
                                min="0"
                                step="1"
                                :error="$errors->first('quantity_available')"
                                required
                            />
                            <flux:error name="quantity_available" />
                        </flux:field>
                    </div>

                    <flux:separator />

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 sm:justify-end pt-6">
                        <flux:button variant="ghost" :href="route('admin.tickets')" wire:navigate>
                            Cancel
                        </flux:button>
                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                        >
                            <span wire:loading.remove wire:target="save">Create Ticket</span>
                            <span wire:loading wire:target="save">Creating...</span>
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
