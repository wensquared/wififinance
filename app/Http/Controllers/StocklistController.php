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

    /**
     * Let user buy the selected stock. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buy(Request $request)
    {
        // dd($request->all());

        $ticker = $request->ticker;

        $client = new Client();
        $url = "https://api.tiingo.com/iex/?tickers=".$ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);

        $tmp = json_decode($res->getBody()->getContents());
        
        // TODO check request ticker exists as ticker

        // TODO case what to do when price raise/fall while buying


        // check if balance is enough and substract money from balance
        $user = User::where('id',Auth::user()->id)->first();
        $total = (float) $request->price * (float) $request->amount;
        if ($total > $user->balance) {
            return redirect()->route('user.index')->with('error', 'Not enough money to buy stock');
        }
        $user->balance -= $total;
        $user->save();
        // insert/update user's stocklist 
        $user_has_ticker = Stocklist::where('ticker',$ticker)->where('user_id',$user->id)->first();
        $user_id = Auth::user()->id;
        if ($user_has_ticker) {
            $user_has_ticker->amount += (int) $request->amount;
            $user_has_ticker->save();
        } else {
            Stocklist::create($request->all());
        }
        
        // insert entry in stock_history
        $stock_id = Stocklist::select('id')->where('ticker',$ticker)->where('user_id',$user->id)->first();
        $stock_history_entry = new StocklistHistory($request->all());
        $stock_history_entry->stocklist_id = $stock_id->id;
        $stock_history_entry->action = true;
        $stock_history_entry->save();
        return redirect()->route('user.index');
    }

    /**
     * Let user sell the selected stock. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sell(Request $request)
    {
        // dd($request->all());

        $ticker = $request->ticker;
        
        $client = new Client();
        $url = "https://api.tiingo.com/iex/?tickers=".$request->ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);

        $tmp = json_decode($res->getBody()->getContents());


        // TODO check request ticker exists as ticker
        
        // TODO case what to do when price raise/fall while buying
        
        
        $user = User::where('id',Auth::user()->id)->first();
        $user_has_ticker = Stocklist::where('ticker',$ticker)->where('user_id',$user->id)->first();
        $total = (float) $request->price * (float) $request->amount;
        if ($request->amount > $user_has_ticker->amount) {
            return redirect()->route('user.index')->with('error', 'Not enough stock to sell for given amount');
        }
        $user->balance += $total;
        $user->save();
        if ($user_has_ticker) {
            $user_has_ticker->amount -= (int) $request->amount;
            $user_has_ticker->save();
        } else {
            Stocklist::create($request->all());
        }
        
        $stock_id = Stocklist::select('id')->where('ticker',$ticker)->where('user_id',$user->id)->first();
        $stock_history_entry = new StocklistHistory($request->all());
        $stock_history_entry->stocklist_id = $stock_id->id;
        $stock_history_entry->action = false;
        $stock_history_entry->save();
        return redirect()->route('mainpage');
    }


    /**
     * Show history of all user's stock transactions.
     * If ticker is set, show user's stock transaction for that specific ticker.
     *
     * @param string  $ticker (optional)
     * @return \Illuminate\Http\Response
     */
    public function show_stock_history($ticker=null)
    {
        if ($ticker) {
            $user_searched_ticker = Stocklist::where('user_id',Auth::user()->id)->where('ticker',$ticker)->first('id');
            $stock_history = StocklistHistory::where('stocklist_id',$user_searched_ticker->id)->with('stocklist')->orderBy('created_at','desc')->paginate(10);
            return view('user.show_stock_history',compact('stock_history'));
        }

        $user_stock_ids = Stocklist::where('user_id',Auth::user()->id)->get('id');
        if ($user_stock_ids->isEmpty()) {
            return redirect()->route('user.index')->with('error', 'No stock history found');
        }

        foreach ($user_stock_ids as $key) {
            $array_ids[] = $key->id;
        }
        $stock_history = StocklistHistory::whereIn('stocklist_id',$array_ids)->with('stocklist')->orderBy('created_at','desc')->paginate(10);
        return view('user.show_stock_history',compact('stock_history'));
    }

    /**
     * Search after specific ticker to show on user's transaction history
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search_ticker_history(Request $request)
    {
        $user_id = Auth::user()->id;
        $ticker = $request->ticker;
        
        $has_ticker = Stocklist::where('ticker',$ticker)->where('user_id',$user_id)->first();
        
        if (!$has_ticker) {
            return redirect()->route('stocklist.show_stock_history')->with('error','No history for searched ticker');
        }
        return redirect()->route('stocklist.show_stock_history', $ticker);
    }
}
