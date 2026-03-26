<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequestDetail extends Model
{
    use HasFactory;

    protected $fillable = ['borrow_request_id', 'asset_id', 'returned_at'];

    public function borrowRequest() { return $this->belongsTo(BorrowRequest::class); }
    public function asset() { return $this->belongsTo(Asset::class); }
}
