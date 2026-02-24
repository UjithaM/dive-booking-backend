<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Activity extends Model implements HasMedia
{
    use HasFactory, HasUuids, SoftDeletes, InteractsWithMedia;

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

    protected $appends = ['media_url'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->nonQueued();
    }

    public function getMediaUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('images');
    }
}
