<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister(){ return view('auth.register'); }

    public function register(Request $r){
        $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password)
        ]);
        Auth::login($user);
        return redirect('/home');
    }

    public function showLogin(){ return view('auth.login'); }

    public function login(Request $r){
        $creds = $r->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($creds)) {
            $r->session()->regenerate();
            return redirect()->intended('/home');
        }
        return back()->withErrors(['email'=>'Invalid credentials']);
    }

    public function logout(Request $r){
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/login');
    }
}
