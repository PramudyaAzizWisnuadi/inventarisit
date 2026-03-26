<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'model_type', 'model_id', 'action', 'description',
        'old_values', 'new_values', 'user_id', 'user_name',
        'ip_address', 'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, string $description, $model = null, array $old = [], array $new = []): void
    {
        $user = auth()->user();
        self::create([
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->id,
            'action'      => $action,
            'description' => $description,
            'old_values'  => $old ?: null,
            'new_values'  => $new ?: null,
            'user_id'     => $user?->id,
            'user_name'   => $user?->name,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
