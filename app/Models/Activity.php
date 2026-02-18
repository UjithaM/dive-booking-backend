<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'short_description',
        'description',
        'duration_hours',
        'max_participants',
        'includes',
        'hero_image_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'includes' => 'array',
        'is_active' => 'boolean',
        'duration_hours' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
