<?php

namespace App\Http\Controllers;

use App\Models\Stocklist;
use App\Models\StocklistHistory;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;


class StocklistController extends Controller
{
    public function buy(Request $request)
    {
        // dd($request->all());

        $ticker = $request->ticker;
        $client = new Client();
        // TODO Try catch
        $url = "https://api.tiingo.com/iex/?tickers=".$request->ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);
        // $result[] = json_decode($res->getBody()->getContents());

        $tmp = json_decode($res->getBody()->getContents());
        $last_price = $tmp[0]->last;
        // dd($last_price);

        // TODO case what to do when price raise/fall while buying
        /* if( (float)$resquest->now_price < (float)$last_price) {
            dd('price fell');
        } */

        // TODO when user has ticker on watchlist, remove it, because user has it in his stocklist

        // TODO insert/update user's stocklist 
        $user_has_ticker = Stocklist::where('ticker',$ticker)->first();
        // dd($user_has_ticker);
        $user_id = Auth::user()->id;
        if ($user_has_ticker) {
        } else {
            Stocklist::create($request->all());
            # code...
        }
        
        // TODO insert entry in stock_history
        $stock_id = Stocklist::select('id')->where('ticker',$ticker)->first();
        // dd($stock_id->id);
        $stock_history_entry = new StocklistHistory($request->all());
        $stock_history_entry->stocklist_id = $stock_id->id;
        $stock_history_entry->action = true;
        $stock_history_entry->save();
        dd('saved???');
    }
}
