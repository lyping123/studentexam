<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class userContorller extends Controller
{
    public function login(){
        return view('login');
    }
    
    public function authentication(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('exam.index');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);

    }

    public function register(){
        return view('register');
    }

    public function register_user(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        return redirect()->route('user.login');
    }
    public function logout(){
        if(Auth::user()->role == 'admin'){
            Auth::logout();
            return redirect()->route('user.login');
        }else{
            Auth::logout();
            return redirect()->route('student.login');
        }   
    }
    public function studentLoginPage()
    {
        return view('studentLogin');
    }
    public function studentLogin(Request $request)
    {
        $request->validate([
            'ic' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('ic', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('exam.index');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function studentRegisterPage(Request $request){
        $courses=course::all();
        return view('studentRegister',compact('courses'));
    }

    public function studentRegister(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'ic' => 'required',
            'password' => 'required|confirmed',
            'course' => 'required'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->ic= $request->ic;
        $user->role = 'student';
        $user->password = $request->password;
        $user->course_id = $request->course;
        $user->save();

        return redirect()->route('student.login');
    }
}
