<?php

namespace App\Http\Controllers;

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
        return view('user.balance');
    }

    public function update(Request $request)
    {
        $request->all();
        $user = User::find(Auth::user()->id);

        if ($request->has('deposit_form')) {
            $user->balance += (float)($request->balance);
            $user->save();
        }
        else if($request->has('withdraw_form')) {
            if ((float) $request->balance > $user->balance) {
                dd('withdrawing more money than in balance');
            }
            $user->balance -= (float) $request->balance;
            $user->save();
        }
        else {
            dd('Error');
        }
        
        return redirect()->route('balance.index');

    }
}
