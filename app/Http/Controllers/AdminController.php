<?php

namespace App\Http\Controllers;

use App\Models\BalanceHistory;
use App\Models\Country;
use App\Models\Role;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('role')->with('country')->paginate(15);
        return view('admin.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(User $admin)
    {   
        if(Auth::user()->id == $admin->id){
            // dd('not allowed');
            $users = User::with('role')->with('country')->paginate(15);
            return redirect()->route('admin.index',compact('users'));
        }
        $roles = Role::get();
        $countries = Country::get();

        return view('admin.edit', compact('admin','roles','countries'));
    }

    /**
     * Update the specified resource in storage.
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
        return redirect()->route('admin.index',compact('users'))->with('success', $user->username.'\'s data has been updated');;
    }

    /**
     * Display the specified resource.
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
     * Display the specified resource.
     *
     * @param  int  $user_id_history
     * @return \Illuminate\Http\Response
     */
    public function show_history($user_id_history)
    {
        $user_balance_history = BalanceHistory::where('user_id',$user_id_history)->orderBy('created_at','desc')->paginate(15);
        // dd($user_balance_history);
        return view('admin.show_history',compact('user_balance_history'));
    }

    /**
     * Remove the specified resource from storage.
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
