<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
