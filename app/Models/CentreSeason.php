<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentreSeason extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'centre_id',
        'name',
        'start_month',
        'end_month',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_month' => 'integer',
        'end_month' => 'integer',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
}
