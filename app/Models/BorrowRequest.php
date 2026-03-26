<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'borrower_name', 'request_number', 'status', 'request_date', 'notes'];

    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(BorrowRequestDetail::class); }
}
