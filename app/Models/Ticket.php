<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'concert_id',
        'ticket_type',
        'price',
        'quantity_available',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity_available' => 'integer',
    ];
    
    /**
     * Get the concert that the ticket belongs to.
     */
    public function concert(): BelongsTo
    {
        return $this->belongsTo(Concert::class);
    }
    
    /**
     * Get the purchases for the ticket.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class);
    }
    
    /**
     * Get the count of purchases for this ticket.
     */
    public function getPurchasedCountAttribute(): int
    {
        return $this->purchases()->where('status', '!=', 'cancelled')->count();
    }
    
    /**
     * Get the remaining available tickets.
     */
    public function getRemainingTicketsAttribute(): int
    {
        return max(0, $this->quantity_available - $this->purchased_count);
    }
}
