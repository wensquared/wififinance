<?php

namespace App\Http\Controllers;

use App\Models\Stocklist;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{

    /**
     * Display stock info page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('info.index');
    }

    /**
     * Search for the given ticker and get and return data from API 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $resquest)
    {
        $ticker = $resquest->ticker;
        $client = new Client();

        $url = "https://api.tiingo.com/iex/?tickers=".$resquest->ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);

        $tmp = json_decode($res->getBody()->getContents());
        if (!$tmp) {
            return redirect()->route('info.index')->with('error','Stock with Ticker '.$resquest->ticker.' does not exist.');
        }
        $now_price = $tmp[0]->last;


        $date_now = date('Y-m-d');
        $date_week_ago = date('Y-m-d',strtotime('-1 week'));
        $date_month_ago = date('Y-m-d', strtotime('-1 month'));
        $url = "https://api.tiingo.com/tiingo/daily/".$resquest->ticker."/prices?startDate=".$date_month_ago;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);
        $tmp = json_decode($res->getBody()->getContents());
        foreach ($tmp as $data) {
            $datum = new \DateTime($data->date);
            
            $dates[] = $datum->format('m-d');
            $close_prices[] = $data->close;
        }


        $url = "https://api.tiingo.com/tiingo/daily/".$resquest->ticker;
        $res = $client->get($url, [
            'headers' => [
                'Content-type' =>  'application/json',
                'Authorization'     => 'Token '.config('services.tiingo.token'),
                ]
            ]);
        $tmp = json_decode($res->getBody()->getContents());

        $ticker_name = $tmp->name;
        $description = $tmp->description;


        //check if user has ticker in stocklist
        if(Auth::user()) {
            $user_has_ticker = Stocklist::where('user_id',Auth::user()->id)->where('ticker',$ticker)->first();
            if($user_has_ticker) {
                $num_of_stocks = $user_has_ticker->amount;
            }
            else {
                $num_of_stocks = null;
            }

            $user_id = Auth::user()->id;
            $user_has_ticker = Watchlist::where('ticker',$ticker)->where('user_id',$user_id)->get();
            $user = User::find($user_id);
            return view('info.result',compact('now_price','ticker_name','description','ticker','user_has_ticker','user','num_of_stocks'))
            ->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))
            ->with('close_prices',json_encode($close_prices,JSON_NUMERIC_CHECK));
        }

    return view('info.result',compact('now_price','ticker_name','description','ticker'))
            ->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))
            ->with('close_prices',json_encode($close_prices,JSON_NUMERIC_CHECK));
    }


    /**
     * Set/unset given ticker to user's watchlist wtih Ajax. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function watchlist(Request $request)
    {
        $user_id = Auth::user()->id;
        if ($request->ticker) {
            if ($request->ticker) {

                $user_ticker = Watchlist::where('ticker',$request->ticker)->where('user_id',$user_id)->get();

                if($user_ticker->isEmpty()) {
                    $watchlist_item = new Watchlist();
                    $watchlist_item->user_id = $user_id;
                    $watchlist_item->ticker = $request->ticker;
                    $watchlist_item->save();
                    $txt = 'Gespeichert!';
                }
                else {
                    Watchlist::find($user_ticker[0]->id)->delete();
                    $txt = 'deleted';
                }
                $status = 200;
                $key = 'success';
                $msg = 'status: '.$txt;
            }
            else {
                $status = 470;
                $key = 'error';
                $msg = 'PROBLEM: No ticker found to set/unset';
            }
        }
        if( request()->ajax()) {
            return response()->json(['status'=>$status, 'msg'=>$msg]);
        }
        return redirect()->route('info.index');
    }
} 
