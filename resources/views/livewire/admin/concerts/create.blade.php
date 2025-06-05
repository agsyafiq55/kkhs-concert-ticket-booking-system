<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <flux:heading size="xl">Create Concert</flux:heading>
                    <flux:button variant="filled" :href="route('admin.concerts')" wire:navigate>
                        Back to List
                    </flux:button>
                </div>
                
                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <flux:input 
                                label="Title" 
                                wire:model="title" 
                                placeholder="Enter concert title"
                                :error="$errors->first('title')"
                                required
                            />
                        </div>
                        
                        <div>
                            <flux:input 
                                label="Venue" 
                                wire:model="venue" 
                                placeholder="Enter venue name"
                                :error="$errors->first('venue')"
                                required
                            />
                        </div>
                    </div>
                    
                    <div>
                        <flux:textarea 
                            label="Description" 
                            wire:model="description" 
                            placeholder="Enter concert description"
                            :error="$errors->first('description')"
                            rows="5"
                            required
                        />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <flux:input 
                                type="date" 
                                label="Date" 
                                wire:model="date" 
                                :error="$errors->first('date')"
                                required
                            />
                        </div>
                        
                        <div>
                            <flux:input 
                                type="time" 
                                label="Start Time" 
                                wire:model="start_time" 
                                :error="$errors->first('start_time')"
                                required
                            />
                        </div>
                        
                        <div>
                            <flux:input 
                                type="time" 
                                label="End Time" 
                                wire:model="end_time" 
                                :error="$errors->first('end_time')"
                                required
                            />
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary">
                            Create Concert
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
