<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'staff_profiles';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'title',
        'bio',
        'profile_photo_url',
        'specialties',
        'is_active',
    ];

    protected $casts = [
        'specialties' => 'array',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
