<?php

namespace App\Http\Controllers;

use App\Models\BalanceHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BalanceHistory::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();
        // dd($data);
        return view('user.balance',compact('data'));
    }

    public function update(Request $request)
    {
        $request->all();
        $request->validate(['balance' => 'required|numeric|gte:0.01']);
        $user = User::find(Auth::user()->id);
        $balance_entry = new BalanceHistory();

        if ($request->has('deposit_form')) {
            $user->balance += (float) $request->balance;
            $user->save();

            $balance_entry->user_id = Auth::user()->id;
            $balance_entry->amount = (float) $request->balance;
            $balance_entry->action = True;
            $balance_entry->save();

        }
        else if($request->has('withdraw_form')) {
            if ((float) $request->balance > $user->balance) {
                dd('withdrawing more money than in balance');
            }
            $user->balance -= (float) $request->balance;
            $user->save();

            $balance_entry->user_id = Auth::user()->id;
            $balance_entry->amount = (float) $request->balance;
            $balance_entry->action = False;
            $balance_entry->save();
        }
        else {
            dd('Error');
        }
        
        return redirect()->route('balance.index');

    }
}
