<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'cart_id',
        'bookable_type',
        'bookable_id',
        'quantity',
        'participant_count',
        'unit_price',
        'total_price',
        'scheduled_date',
        'scheduled_time',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'scheduled_date' => 'date',
        'quantity' => 'integer',
        'participant_count' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Polymorphic relationship – Activity, Course, Service, or Room.
     */
    public function bookable()
    {
        return $this->morphTo();
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Calculate the total price for this item based on unit price,
     * quantity, and participant_count (only multiplied when is_per_person = true).
     */
    public static function calculateTotalPrice(
        float $unitPrice,
        int $quantity,
        int $participantCount,
        bool $isPerPerson
    ): float {
        return $isPerPerson
            ? $unitPrice * $quantity * $participantCount
            : $unitPrice * $quantity;
    }
}
