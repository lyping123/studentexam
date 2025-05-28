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
            'autherticate' => 'required',
            'password' => 'required'
        ]);
        $loginField=filter_var($request->autherticate, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';


        $credentials = [
            $loginField => $request->autherticate,
            'password' => $request->password
        ];

        $userexits=User::where('email',$request->autherticate)->orWhere('name',$request->autherticate)->first();
        if(!$userexits){
            return back()->withErrors([
                'email' => 'The email or ic is not registered.',
            ]);

        }
        
        $remember=$request->filled('remember');
        if ($userexits->password==$request->password) {
            Auth::login($userexits,$remember);
            $request->session()->regenerate();
            if(Auth::user()->role == 'admin'){
                
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('student.dashboard'));
            
            // return redirect()->intended('/');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);

    }

    public function register(){
        return view('adminRegister');
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
        Auth::logout();
        return redirect()->route('user.login');
         
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

    public function profile(){
        $user=Auth::user();
        return view('profile',compact('user'));
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);


        $user=Auth::user();
        $user->name=$request->name;
        $user->email=$request->email;
        if($request->checkpassword){
            $request->validate([
                'password' => 'required'
            ]);
            $user->password=$request->password;
        }
        
        $user->save();
        return redirect()->route('user.profile')->with("success","Profile updated successfully");
    }
    
}
