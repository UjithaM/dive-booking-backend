<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'short_description',
        'description',
        'duration_days',
        'min_age',
        'price',
        'currency',
        'includes',
        'hero_image_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'includes' => 'array',
        'is_active' => 'boolean',
        'duration_days' => 'integer',
        'min_age' => 'integer',
        'price' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
