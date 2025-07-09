<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Concert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'venue',
        'date',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the tickets for the concert.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all ticket purchases for this concert.
     */
    public function ticketPurchases(): HasManyThrough
    {
        return $this->hasManyThrough(TicketPurchase::class, Ticket::class);
    }

    /**
     * Get the total revenue for this concert.
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->ticketPurchases()
            ->join('tickets', 'ticket_purchases.ticket_id', '=', 'tickets.id')
            ->whereIn('ticket_purchases.status', ['valid', 'used'])
            ->sum('tickets.price');
    }

    /**
     * Get the total sales count for this concert.
     */
    public function getTotalSalesAttribute(): int
    {
        return $this->ticketPurchases()->count();
    }
}
