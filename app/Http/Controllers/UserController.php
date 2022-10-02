<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function create()
    {
        return view('users.register');
    }

    public function login(){
        return view('users.login');
    }

    public function authenticate(){
        $formfields= request()->validate([
            'email'=> ['required', 'email'],
            'password' => ['required']
        ]);
        if(auth()->attempt($formfields)){
            request()->session()->regenerate();

            return redirect('/')->with('message', 'User logged in');
        }
        return back()->withErrors(['email'=>'Invalid credentials'])->onlyInput('email');
    }

    public function store()
    {
        $formfields = request()->validate([
            'name' => ['required', 'min:2'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        //  Hash password
        $formfields['password'] = bcrypt($formfields['password']);

        $user = User::create($formfields);

        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in succesfully');
    }

    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('message', 'User logged out successfully');
    }


}
