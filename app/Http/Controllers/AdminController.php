<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('role')->with('country')->get();
        return view('admin.index', compact('users'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $admin)
    {   
        if(Auth::user()->id == $admin->id){
            dd('not allowed');
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
        return redirect()->route('admin.index',compact('users'));
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
