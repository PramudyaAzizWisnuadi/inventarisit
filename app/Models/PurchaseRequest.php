<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'request_number', 'total_price', 'status', 'request_date', 'notes', 'receipt_photo'];

    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(PurchaseRequestDetail::class); }
}
