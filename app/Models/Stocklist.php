<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocklist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','ticker','amount','action'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
