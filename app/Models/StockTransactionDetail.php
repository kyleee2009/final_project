<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransactionDetail extends Model
{
    protected $fillable = [
        'stock_transaction_id',
        'item_id',
        'quantity',
        'stock_before',
        'stock_after',
    ];

    public function transaction()
    {
        return $this->belongsTo(StockTransaction::class, 'stock_transaction_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}