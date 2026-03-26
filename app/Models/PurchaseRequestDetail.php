<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestDetail extends Model
{
    protected $fillable = ['purchase_request_id', 'item_name', 'specification', 'brand', 'qty', 'price', 'subtotal'];

    public function purchaseRequest() { return $this->belongsTo(PurchaseRequest::class); }
}
