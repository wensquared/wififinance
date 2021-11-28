<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocklist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','ticker','amount'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function stocklist_history()
    {
        return $this->hasMany(StocklistHistory::class, 'stocklist_id', 'id')->orderBy('created_at','desc');
    }

    public function stocklist_history_buy()
    {
        return $this->hasMany(StocklistHistory::class, 'stocklist_id', 'id')->where('action',true);
    }

    public function stocklist_history_sell()
    {
        return $this->hasMany(StocklistHistory::class, 'stocklist_id', 'id')->orderBy('created_at','desc')->where('action',false);
    }
}
