<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Role;
use App\Models\Stocklist;
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
        $user_stocklist = Stocklist::where('user_id',Auth::user()->id)->get();
        $client = new Client();
        if ($user_watchlists->isEmpty()) {
            // return view('user.index');
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
    
                //TODO calculate from history the $$ of stock, get latest prices if some stock got sold
    
                $stocklist[] = ['ticker'=>$stock->ticker, 'price'=>$now_price, 'ticker_name'=>$ticker_name, 'holding'=>'TODO', 'stock_amount'=>$stock->amount];
            }
        }
        
        


        return view('user.index', compact('watchlist','stocklist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    { 
        // $roles = Role::get();
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

        // $absPath = Storage::disk('public_verification_img')->path('');
        // dd($user->verification_img);

        $update = $request->except('verification_img','password');

        if (isset($request->verification_img)) {
            if ($user->verification_img) {
                // dd('user hatte schon ein bild');
                $this->deleteFile($user->verification_img)->deleteFile('show_'.$user->verification_img);
            }
            $this->saveFile($request->file('verification_img'))->maxWidth(850,'show_');
            $update['verification_img'] = $this->saveFile;
        }
        // $user->role_id = $request->role_id;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
