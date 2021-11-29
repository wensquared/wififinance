<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Role;
use App\Models\Stocklist;
use App\Models\StocklistHistory;
use App\Models\User;
use App\Models\Watchlist;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use GuzzleHttp\Client;


class UserController extends Controller
{
    use FileTrait;

    public function __construct()
    {
        $this->diskName = 'public_verification_img';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_watchlists = Watchlist::where('user_id',Auth::user()->id)->get();
        $user_stocklist = Stocklist::where('user_id',Auth::user()->id)->where('amount','>',0)->get();
        $client = new Client();
        if ($user_watchlists->isEmpty()) {
            $watchlist = null;
        }
        else {
            
            foreach ($user_watchlists as $value) {
    
                $url = "https://api.tiingo.com/iex/?tickers=".$value->ticker;
                $res = $client->get($url, [
                    'headers' => [
                        'Content-type' =>  'application/json',
                        'Authorization'     => 'Token '.config('services.tiingo.token'),
                        ]
                    ]);
    
                $tmp = json_decode($res->getBody()->getContents());
                $now_price = $tmp[0]->last;
    
    
                $url = "https://api.tiingo.com/tiingo/daily/".$value->ticker;
                $res = $client->get($url, [
                    'headers' => [
                        'Content-type' =>  'application/json',
                        'Authorization'     => 'Token '.config('services.tiingo.token'),
                        ]
                    ]);
                $tmp = json_decode($res->getBody()->getContents());
                $ticker_name = $tmp->name;
    
                $watchlist[] = ['ticker'=>$value->ticker, 'price'=>$now_price, 'ticker_name'=>$ticker_name];
            }
        }
        
        if ($user_stocklist->isEmpty()) {
            $stocklist = null;
        } else {
            foreach ($user_stocklist as $stock) {
                $url = "https://api.tiingo.com/iex/?tickers=".$stock->ticker;
                $res = $client->get($url, [
                    'headers' => [
                        'Content-type' =>  'application/json',
                        'Authorization'     => 'Token '.config('services.tiingo.token'),
                        ]
                    ]);
    
                $tmp = json_decode($res->getBody()->getContents());
                $now_price = $tmp[0]->last;
    
    
                $url = "https://api.tiingo.com/tiingo/daily/".$stock->ticker;
                $res = $client->get($url, [
                    'headers' => [
                        'Content-type' =>  'application/json',
                        'Authorization'     => 'Token '.config('services.tiingo.token'),
                        ]
                    ]);
                $tmp = json_decode($res->getBody()->getContents());
                $ticker_name = $tmp->name;
    

                $buy_stock_history = Stocklist::where('ticker',$stock->ticker)->where('user_id',Auth::user()->id)->with('stocklist_history_buy')->first();
                $sell_stock_history = Stocklist::where('ticker',$stock->ticker)->where('user_id',Auth::user()->id)->with('stocklist_history_sell')->first();
                $stock_remain = $buy_stock_history->stocklist_history_buy->sum('amount') - $sell_stock_history->stocklist_history_sell->sum('amount');
                $rest = $stock_remain;
                $amount_array = null;
                $price_array = null;
                foreach ($buy_stock_history->stocklist_history_buy as $key) {
                    $amount_array[] = $key->amount;
                    $price_array[] = $key->price;
                }
        
                $j = 0;
                $avg_holding_price = 0;
                for ($i=$stock_remain; $i > 0; $i--) { 
                    if($rest <= $amount_array[$j]){
                        $avg_holding_price += $price_array[$j];
                    }
                    else {
                        $m = 0;
                        for ($k=0; $k < $amount_array[$j]; $k++) { 
                            $avg_holding_price += $price_array[$j];
                            $m++;
                        }
                        $i -= ($m-1);
                        $rest -= $amount_array[$j];
                        $j++;
                    }
                }
                
                $avg_price_per_share = $avg_holding_price / $stock_remain;
                $holding_value = $now_price*$stock->amount;
                $profit_loss = (float)($holding_value - $avg_holding_price)/ $avg_holding_price;
                $profit_loss = round($profit_loss*100,2);


                $stocklist[] = ['ticker'=>$stock->ticker, 
                                'ticker_name'=>$ticker_name, 
                                'price'=>round($now_price,2), 
                                'avg_price_per_share'=>round($avg_price_per_share,2),
                                'stock_amount'=>$stock->amount,
                                'holding_value'=>round($holding_value,2),
                                'profit_loss'=>$profit_loss,
                            ];
            }
        }
        return view('user.index', compact('watchlist','stocklist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    { 
        $countries = Country::get();
        return view('user.edit', compact('user','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email'=>'required|email:filter,dns|unique:users,email,'.$user->id.'id',
            'country_id'=>['required','exists:countries,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:200'],
            'postcode' => ['required', 'string', 'max:10'],
            'verification_img'=>['nullable','mimes:gif,png,jpg,jpeg','max:4096'],
        ]);

        $update = $request->except('verification_img','password');

        if (isset($request->verification_img)) {
            if ($user->verification_img) {
                $this->deleteFile($user->verification_img)->deleteFile('show_'.$user->verification_img);
            }
            $this->saveFile($request->file('verification_img'))->maxWidth(850,'show_');
            $update['verification_img'] = $this->saveFile;
        }
        $user->update($update);
        
        if (isset($request->password)) {
            $request->validate([
                'password'=>['required','confirmed','password'=>Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('mainpage')->with('success','Your data has been updated');
    }
}
