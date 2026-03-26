<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class AssetDisposal extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'disposal_date', 'disposal_type', 'notes'];

    public function asset() { return $this->belongsTo(Asset::class); }
}
