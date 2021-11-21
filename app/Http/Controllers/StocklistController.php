<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StocklistController extends Controller
{
    public function buy(Request $resquest)
    {
        dd($resquest->all());
    }
}
