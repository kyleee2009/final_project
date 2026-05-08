<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'user_id',
        'type',
        'source_or_destination',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(StockTransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}