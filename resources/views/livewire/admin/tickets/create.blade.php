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
                        <div class="flex items-center">
                            <flux:icon.ticket variant="solid" class="w-9 h-9 mr-2" />
                            <flux:heading size="xl">Create Ticket</flux:heading>
                        </div>
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

                    <!-- Regular Ticket Details Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-4">
                            <flux:heading size="lg">Regular Ticket (Teacher Assignment)</flux:heading>
                            <flux:tooltip content="Regular tickets are assigned by teachers to students">
                                <flux:badge color="green">Required</flux:badge>
                            </flux:tooltip>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <flux:field>
                                <flux:label>Regular Ticket Name</flux:label>
                                <flux:description>
                                    Name for the regular ticket type (e.g., "General Admission", "Student Ticket")
                                </flux:description>
                                <flux:input 
                                    wire:model="ticket_type" 
                                    placeholder="e.g., General Admission, Student Ticket"
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
                            <flux:label>Regular Ticket Quantity</flux:label>
                            <flux:description>
                                Enter the number of regular tickets available for teacher assignment to students.
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

                    <!-- Walk-in Tickets Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <flux:heading size="lg">Additional Ticket Types</flux:heading>
                            <flux:tooltip content="Create specialized ticket types for walk-in sales and VIP customers. Each concert can only have one of each type.">
                                <flux:badge color="blue">Optional</flux:badge>
                            </flux:tooltip>
                        </div>

                        <!-- Walk-in Tickets Checkbox -->
                        <flux:field variant="inline">
                            <flux:label>Create Walk-in Tickets</flux:label>
                            <flux:switch wire:model.live="createWalkInTickets" />
                            <flux:error name="createWalkInTickets" />
                        </flux:field>

                        <!-- Walk-in Ticket Fields (shown conditionally) -->
                        @if($createWalkInTickets)
                            <div class="ml-4 pl-4 border-l-2 border-blue-200 dark:border-blue-700 space-y-4">
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                    Walk-in tickets are standalone tickets sold at the door during the concert. Each concert can only have one walk-in ticket type.
                                </flux:text>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <flux:field>
                                        <flux:label>Walk-in Ticket Name</flux:label>
                                        <flux:description>Name for the walk-in ticket type (e.g., "Walk-in Ticket", "Door Sales")</flux:description>
                                        <flux:input 
                                            wire:model="walkInTicketType" 
                                            placeholder="Walk-in Ticket"
                                            :error="$errors->first('walkInTicketType')"
                                        />
                                        <flux:error name="walkInTicketType" />
                                    </flux:field>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <flux:field>
                                        <flux:label>Walk-in Price (RM)</flux:label>
                                        <flux:input 
                                            type="number" 
                                            wire:model="walkInPrice" 
                                            placeholder="0.00"
                                            min="0"
                                            step="0.01"
                                            :error="$errors->first('walkInPrice')"
                                        />
                                        <flux:error name="walkInPrice" />
                                    </flux:field>
                                    
                                    <flux:field>
                                        <flux:label>Walk-in Quantity</flux:label>
                                        <flux:input 
                                            type="number" 
                                            wire:model="walkInQuantity" 
                                            placeholder="50"
                                            min="0"
                                            step="1"
                                            :error="$errors->first('walkInQuantity')"
                                        />
                                        <flux:error name="walkInQuantity" />
                                    </flux:field>
                                </div>
                            </div>
                        @endif

                        <!-- VIP Tickets Checkbox -->
                        <flux:field variant="inline">
                            <flux:label>Create VIP Tickets</flux:label>
                            <flux:switch wire:model.live="createVipTickets" />
                            <flux:error name="createVipTickets" />
                        </flux:field>

                        <!-- VIP Ticket Fields (shown conditionally) -->
                        @if($createVipTickets)
                            <div class="ml-4 pl-4 border-l-2 border-purple-200 dark:border-purple-700 space-y-4">
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                    VIP tickets are standalone premium tickets sold to special customers. Each concert can only have one VIP ticket type.
                                </flux:text>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <flux:field>
                                        <flux:label>VIP Ticket Name</flux:label>
                                        <flux:description>Name for the VIP ticket type (e.g., "VIP Ticket", "Premium Access")</flux:description>
                                        <flux:input 
                                            wire:model="vipTicketType" 
                                            placeholder="VIP Ticket"
                                            :error="$errors->first('vipTicketType')"
                                        />
                                        <flux:error name="vipTicketType" />
                                    </flux:field>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <flux:field>
                                        <flux:label>VIP Price (RM)</flux:label>
                                        <flux:input 
                                            type="number" 
                                            wire:model="vipPrice" 
                                            placeholder="0.00"
                                            min="0"
                                            step="0.01"
                                            :error="$errors->first('vipPrice')"
                                        />
                                        <flux:error name="vipPrice" />
                                    </flux:field>
                                    
                                    <flux:field>
                                        <flux:label>VIP Quantity</flux:label>
                                        <flux:input 
                                            type="number" 
                                            wire:model="vipQuantity" 
                                            placeholder="20"
                                            min="0"
                                            step="1"
                                            :error="$errors->first('vipQuantity')"
                                        />
                                        <flux:error name="vipQuantity" />
                                    </flux:field>
                                </div>
                            </div>
                        @endif
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
