<?php

namespace App\Http\Controllers;

use App\Models\Stocklist;
use App\Models\StocklistHistory;
use App\Models\User;
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

        // TODO check if balance is enough and substract money from balance
        // dd(Auth::user());
        $user = User::where('id',Auth::user()->id)->first();
        $total = (float) $request->price * (float) $request->amount;
        // dd($total);
        if ($total > $user->balance) {
            dd('not enough money');
        }
        $user->balance -= $total;
        $user->save();
        // dd(Auth::user()->balance);
        // TODO insert/update user's stocklist 
        $user_has_ticker = Stocklist::where('ticker',$ticker)->where('user_id',$user->id)->first();
        // dd($user_has_ticker);
        $user_id = Auth::user()->id;
        if ($user_has_ticker) {
            $user_has_ticker->amount += (int) $request->amount;
            $user_has_ticker->save();
        } else {
            Stocklist::create($request->all());
        }
        
        // TODO insert entry in stock_history
        $stock_id = Stocklist::select('id')->where('ticker',$ticker)->where('user_id',$user->id)->first();
        // dd($stock_id->id);
        $stock_history_entry = new StocklistHistory($request->all());
        $stock_history_entry->stocklist_id = $stock_id->id;
        $stock_history_entry->action = true;
        $stock_history_entry->save();
        // dd('saved???');
        return redirect()->route('mainpage');
    }

    public function sell(Request $request)
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

        // TODO check if user has the amount of stock and add total to user's balance
        // dd(Auth::user());
        $user = User::where('id',Auth::user()->id)->first();
        $user_has_ticker = Stocklist::where('ticker',$ticker)->where('user_id',$user->id)->first();
        $total = (float) $request->price * (float) $request->amount;
        // dd($total);
        if ($request->amount > $user_has_ticker->amount) {
            dd('u dont have enough stocks');
        }
        $user->balance += $total;
        $user->save();
        // dd(Auth::user()->balance);
        // TODO insert/update user's stocklist 
        // $user_has_ticker = Stocklist::where('ticker',$ticker)->where('user_id',$user->id)->first();
        // dd($user_has_ticker);
        $user_id = Auth::user()->id;
        if ($user_has_ticker) {
            $user_has_ticker->amount -= (int) $request->amount;
            $user_has_ticker->save();
        } else {
            Stocklist::create($request->all());
        }
        
        // TODO insert entry in stock_history
        $stock_id = Stocklist::select('id')->where('ticker',$ticker)->where('user_id',$user->id)->first();
        // dd($stock_id->id);
        $stock_history_entry = new StocklistHistory($request->all());
        $stock_history_entry->stocklist_id = $stock_id->id;
        $stock_history_entry->action = false;
        $stock_history_entry->save();
        // dd('saved???');
        return redirect()->route('mainpage');
    }

    public function show($ticker)
    {
        // dd($ticker);
        $stock_history = Stocklist::where('ticker',$ticker)->where('user_id',Auth::user()->id)->with('stocklist_history')->first();
        // dd($stock_history->stocklist_history);
        return view('user.stock_history', compact('stock_history'));
    }
}
