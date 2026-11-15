<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPaymentAttempt extends Model
{
    protected $fillable = [
        'order_id',
        'snap_token',
        'status',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
