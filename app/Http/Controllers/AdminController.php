<?php

namespace App\Http\Controllers;

use App\Models\BalanceHistory;
use App\Models\Country;
use App\Models\Role;
use App\Models\Stocklist;
use App\Models\StocklistHistory;
use App\Models\User;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AdminController extends Controller
{
    use FileTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->diskName = 'public_verification_img';
    }

    /**
     * Display a list of all users and their information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg = null;
        $paginate = true;
        if ($request->user_email) {
            $users = User::where('email',$request->user_email)->with('role')->with('country')->get();

            if(!$users->isEmpty()) {
                $paginate = null;
                return view('admin.index', compact('users','paginate'));
            }
            else {
                $msg = 'No user with email '.$request->user_email.' found.';
            }
        }

        $users = User::with('role')->with('country')->paginate(2);

        if ($msg) {
            return redirect()->route('admin.index', compact('users','paginate'))->with('error',$msg);
        }
        return view('admin.index', compact('users','paginate'));
    }

    /**
     * Show the form for editing a specific user.
     *
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(User $admin)
    {   
        if(Auth::user()->id == $admin->id){
            $users = User::with('role')->with('country')->paginate(15);
            return redirect()->route('admin.index',compact('users'));
        }
        $roles = Role::get();
        $countries = Country::get();

        return view('admin.edit', compact('admin','roles','countries'));
    }

    /**
     * Update an user's data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id',$id)->first();
        
        $validateData = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email'=>'required|email:filter,dns|unique:users,email,'.$user->id.'id',
            'country_id'=>['required','exists:countries,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:200'],
            'postcode' => ['required', 'string', 'max:10'],
            'role_id'=>'nullable|exists:roles,id',
        ]);

        
        $user->role_id = $request->role_id;
        $user->update($validateData);
        
        if (isset($request->password)) {
            $request->validate([
                'password'=>['required','confirmed','password'=>Password::min(8)->mixedCase()->numbers()->symbols()],
            ]);
            $user->password = Hash::make($request->password);
            $user->save();
        }
        $users = User::get();
        return redirect()->route('admin.index',compact('users'))->with('success', $user->username.'\'s data has been updated');
    }

    /**
     * Display user's verified id picture.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->first();
        
        return view('admin.show', compact('user'));
    }

    /**
     * Display user's balance history.
     *
     * @param  int  $user_id_history
     * @return \Illuminate\Http\Response
     */
    public function show_history($user_id_history)
    {
        $user_balance_history = BalanceHistory::where('user_id',$user_id_history)->orderBy('created_at','desc')->paginate(10);
        return view('admin.show_history',compact('user_balance_history'));
    }

    /**
     * Display user's stock transaction history.
     *
     * @param  int  $user_id_history
     * @return \Illuminate\Http\Response
     */
    public function show_stock_history($user_id_history, $ticker=null)
    {

        if ($ticker) {
            $admin_searched_ticker = Stocklist::where('user_id',$user_id_history)->where('ticker',$ticker)->first('id');
            $user_stock_history = StocklistHistory::where('stocklist_id',$admin_searched_ticker->id)->with('stocklist')->orderBy('created_at','desc')->paginate(10);
            return view('admin.show_stock_history',compact('user_id_history','user_stock_history'));
        }

        $user_stock_ids = Stocklist::where('user_id',$user_id_history)->get('id');
        foreach ($user_stock_ids as $key) {
            $array_ids[] = $key->id;
        }
        $user_stock_history = StocklistHistory::whereIn('stocklist_id',$array_ids)->with('stocklist')->orderBy('created_at','desc')->paginate(10);
        return view('admin.show_stock_history',compact('user_id_history','user_stock_history'));
    }

    /**
     * After given search term. Check if search term is available for specific user and returns user's specific stock transaction history 
     * and will redirect to function show_stock_history
     *
     * @param  int  $user_id_history
     * @return \Illuminate\Http\Response
     */
    public function search_ticker_history(Request $request)
    {
        $user_id_history = $request->user_id_history;
        $ticker = $request->ticker;
        
        $has_ticker = Stocklist::where('ticker',$ticker)->where('user_id',$user_id_history)->first();
        
        if (!$has_ticker) {
            $user_stock_ids = Stocklist::where('user_id',$user_id_history)->get('id');
            foreach ($user_stock_ids as $key) {
                $array_ids[] = $key->id;
            }
            $user_stock_history = StocklistHistory::whereIn('stocklist_id',$array_ids)->with('stocklist')->get();
            return redirect()->route('admin.user_stock_history',compact('user_id_history'))->with('error','No history for searched ticker');
        }
        return redirect()->route('admin.user_stock_history',['user_id_history'=>$user_id_history,'ticker'=>$ticker]);
    }

    /**
     * Remove the selected user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            if ($user->verification_img) {
                $this->deleteFile($user->verification_img)->deleteFile('show_'.$user->verification_img);
                $status = 200;
                $key = 'success';
                $msg = 'User '.$user->username.' deleted.';
            }
            else {
                $status = 470;
                $key = 'error';
                $msg = 'PROBLEM: User couldn\'t be deleted.';
            }
        }
        if( request()->ajax()) {
            return response()->json(['status'=>$status, 'msg'=>$msg]);
        }
        $users = User::get();
        return redirect()->route('admin.index',compact('users'));
    }

    public function showimg($img)
    {
        return $this->showFile($img);
    }

    public function download($img)
    {
        return $this->downloadFile($img);
    }
}
