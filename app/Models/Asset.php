<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_code', 'name', 'brand', 'model', 'serial_number',
        'category_id', 'location_id', 'vendor_id', 'user_id',
        'purchase_date', 'purchase_price', 'warranty_expiry',
        'condition', 'status', 'specifications', 'notes', 'photo', 'qr_code',
    ];

    protected $casts = [
        'purchase_date'  => 'date',
        'warranty_expiry' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function currentAssignment()
    {
        return $this->assignments()->where('status', 'Aktif')->latest()->first();
    }

    public function isWarrantyExpired(): bool
    {
        return $this->warranty_expiry && $this->warranty_expiry->isPast();
    }

    public static function generateCode(): string
    {
        $year = now()->format('Y');
        $count = self::withTrashed()->whereYear('created_at', $year)->count() + 1;
        return 'HW-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
