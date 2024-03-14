<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class Onboarding extends Controller
{
    function login(Request $req){
        $req->validate([
          'id'=> 'required',
          'password'=>'required'  
        ]);
        $id = $req->id;
        // echo $id;
        $password = $req->password;
        if (Auth::attempt(['email' => $id, 'password' => $password])) {
            return redirect('/');
        } else {
            return redirect()->back()->with('message', '존재하지 않는 계정입니다.');
        }        
    }

    // public function register(Request $req)
    // {
    //     $req->validate([
    //         'id' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $id = $req->id;
    //     $email = 'admin@admin.com';
    //     $password = bcrypt($req->password); 

    //     $user = User::create([
    //         'name' => $id,
    //         'email' => $email,
    //         'password' => $password,
    //     ]);

    //     return redirect('/login')->with('message', 'Registration successful');
    // }

    function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
