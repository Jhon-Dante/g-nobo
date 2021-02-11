<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use Hash;


class ProfileController extends Controller
{
    //
    public function profile()
    {
        return view('admin.profile.profile');
    }

    public function update(ProfileRequest $request){
        $user = User::find(Auth::id());

        $this->validate($request, [
            'email' => 'required|email|unique:users,email,'.$user->id.',id|max:191'
        ]);
        
        $user->email = $request->email;

        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return $user;
    }
}
