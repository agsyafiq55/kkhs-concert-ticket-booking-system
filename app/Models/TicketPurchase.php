<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketPurchase extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'ticket_id',
        'student_id',
        'teacher_id',
        'purchase_date',
        'qr_code',
        'status',
        'is_walk_in',
        'is_sold',
        'is_vip',
        'vip_name',
        'vip_email',
        'vip_phone',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'datetime',
        'is_walk_in' => 'boolean',
        'is_sold' => 'boolean',
        'is_vip' => 'boolean',
    ];
    
    /**
     * Get the ticket that the purchase belongs to.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
    
    /**
     * Get the student that the purchase belongs to.
     * Nullable for walk-in tickets.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Get the teacher that recorded the purchase.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    /**
     * Check if this is a walk-in ticket.
     */
    public function isWalkIn(): bool
    {
        return $this->is_walk_in;
    }
    
    /**
     * Check if this walk-in ticket has been sold (payment received).
     */
    public function isSold(): bool
    {
        return $this->is_sold;
    }

    /**
     * Check if this is a VIP ticket.
     */
    public function isVip(): bool
    {
        return $this->is_vip;
    }

    /**
     * Get the email address for sending ticket confirmations.
     * Returns VIP email for VIP tickets, student email for regular tickets.
     */
    public function getRecipientEmailAttribute(): string
    {
        if ($this->is_vip && $this->vip_email) {
            return $this->vip_email;
        }
        
        if ($this->student && $this->student->email) {
            return $this->student->email;
        }
        
        return '';
    }
    
    /**
     * Mark this walk-in ticket as sold (payment received).
     */
    public function markAsSold(): bool
    {
        if (!$this->is_walk_in) {
            return false; // Only walk-in tickets can be marked as sold this way
        }
        
        $this->is_sold = true;
        return $this->save();
    }
    
    /**
     * Mark this ticket as used (scanned at entrance).
     */
    public function markAsUsed(): bool
    {
        $this->status = 'used';
        return $this->save();
    }
    
    /**
     * Check if this ticket is ready for entrance scanning.
     * For regular tickets: always ready if valid
     * For walk-in tickets: ready only if sold and valid
     * For VIP tickets: always ready if valid (pre-paid)
     */
    public function isReadyForEntrance(): bool
    {
        if ($this->status !== 'valid') {
            return false;
        }
        
        if ($this->is_walk_in) {
            return $this->is_sold;
        }
        
        // VIP and regular tickets are always ready if valid
        return true;
    }
    
    /**
     * Get a human-readable name for the ticket holder.
     * For VIP and walk-in tickets without students, return appropriate names.
     */
    public function getHolderNameAttribute(): string
    {
        if ($this->student) {
            return $this->student->name;
        }
        
        if ($this->is_vip && $this->vip_name) {
            return $this->vip_name;
        }
        
        if ($this->is_walk_in) {
            return 'Walk-in Customer';
        }
        
        return 'Unknown';
    }

    /**
     * Boot the model and set up event listeners.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticketPurchase) {
            if (empty($ticketPurchase->order_id)) {
                $ticketPurchase->order_id = $ticketPurchase->generateOrderId();
            }
        });
    }

    /**
     * Generate a unique order ID with long numbers.
     * Format: YYYYMMDD + random 12-digit number
     */
    public function generateOrderId(): string
    {
        do {
            // Get current date in YYYYMMDD format
            $datePrefix = now()->format('Ymd');
            
            // Generate a 12-digit random number
            $randomNumber = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
            
            // Combine them to create a 20-digit order ID
            $orderId = $datePrefix . $randomNumber;
            
        } while (self::where('order_id', $orderId)->exists());
        
        return $orderId;
    }

    /**
     * Get the order ID attribute with formatting.
     */
    public function getFormattedOrderIdAttribute(): string
    {
        return 'ORD-' . $this->order_id;
    }
}
