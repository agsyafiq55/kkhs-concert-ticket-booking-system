<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <flux:heading size="xl">Edit Ticket</flux:heading>
                    <flux:button variant="filled" :href="route('admin.tickets')" wire:navigate>
                        Back to List
                    </flux:button>
                </div>
                
                <form wire:submit="update" class="space-y-6">
                    <div>
                        <flux:select 
                            label="Concert" 
                            wire:model="concert_id" 
                            :error="$errors->first('concert_id')"
                            required
                        >
                            <option value="">Select a concert</option>
                            @foreach ($concerts as $concert)
                                <option value="{{ $concert->id }}">{{ $concert->title }} ({{ $concert->date->format('M d, Y') }})</option>
                            @endforeach
                        </flux:select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <flux:input 
                                label="Ticket Type" 
                                wire:model="ticket_type" 
                                placeholder="e.g. VIP, Standard, Student"
                                :error="$errors->first('ticket_type')"
                                required
                            />
                        </div>
                        
                        <div>
                            <flux:input 
                                type="number" 
                                label="Price ($)" 
                                wire:model="price" 
                                placeholder="0.00"
                                min="0"
                                step="0.01"
                                :error="$errors->first('price')"
                                required
                            />
                        </div>
                        
                        <div>
                            <flux:input 
                                type="number" 
                                label="Quantity Available" 
                                wire:model="quantity_available" 
                                placeholder="100"
                                min="0"
                                step="1"
                                :error="$errors->first('quantity_available')"
                                required
                            />
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary">
                            Update Ticket
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
