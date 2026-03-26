<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'asset_id', 'type', 'technician', 'vendor_service',
        'scheduled_at', 'completed_at', 'cost', 'status',
        'problem_description', 'action_taken', 'notes', 'created_by',
    ];

    protected $casts = [
        'scheduled_at'  => 'date',
        'completed_at'  => 'date',
        'cost'          => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
