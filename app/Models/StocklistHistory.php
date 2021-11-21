<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocklistHistory extends Model
{
    use HasFactory;

    protected $fillable = ['stocklist_id', 'amount', 'price', 'action'];


    public function stocklist()
    {
        return $this->belongsTo(Stocklist::class, 'stocklist_id', 'id');
    }
}
