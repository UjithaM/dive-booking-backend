<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tenant_id',
        'token',
        'status',
        'expires_at',
        'customer_name',
        'customer_email',
        'customer_phone',
        'special_requests',
        'total_amount',
        'currency',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // -------------------------------------------------------------------------
    // Static helpers
    // -------------------------------------------------------------------------

    /**
     * Create a fresh cart for the given tenant and return it with a secure token.
     */
    public static function createForTenant(string $tenantId): self
    {
        return static::create([
            'tenant_id' => $tenantId,
            'token' => Str::random(32),
            'status' => 'active',
            'expires_at' => now()->addDays(7),
            'total_amount' => 0,
            'currency' => 'USD',
        ]);
    }

    /**
     * Find an active cart by token (scoped to tenant).
     */
    public static function findActiveByToken(string $token, string $tenantId): ?self
    {
        return static::where('token', $token)
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->first();
    }

    // -------------------------------------------------------------------------
    // Business logic
    // -------------------------------------------------------------------------

    /**
     * Recalculate and persist the cart total from its items.
     */
    public function recalculateTotal(): void
    {
        $total = $this->items()->sum('total_price');
        $this->update(['total_amount' => $total]);
    }
}
