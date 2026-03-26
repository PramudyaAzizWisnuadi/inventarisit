<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoftwareLicense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'license_code', 'software_name', 'publisher', 'version', 'license_key',
        'license_type', 'category_id', 'vendor_id', 'purchase_date', 'purchase_price',
        'expiry_date', 'max_users', 'used_users', 'status', 'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'expiry_date'   => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(): bool
    {
        return $this->expiry_date && $this->expiry_date->isFuture()
            && $this->expiry_date->diffInDays(now()) <= 30;
    }

    public static function generateCode(): string
    {
        $year = now()->format('Y');
        $count = self::withTrashed()->whereYear('created_at', $year)->count() + 1;
        return 'SW-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
