<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'booking_reference',
        'customer_id',
        'centre_id',
        'promotion_id',
        'status',
        'booking_date',
        'total_amount',
        'discount_amount',
        'final_amount',
        'currency',
        'number_of_participants',
        'special_requests',
        'internal_notes',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'cancelled_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function roomBookings()
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
