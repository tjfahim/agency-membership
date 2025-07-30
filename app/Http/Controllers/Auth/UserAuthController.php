<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class UserAuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if($user->hasRole('admin')){
                $request->session()->regenerate();
                return to_route('dashboard');
            }
            return to_route('view_login');
        }
 
        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ])->onlyInput('error');
    }

    public function register (Request $request) 
    {
        $request->validate([
            'name'=>['required', 'string', 'min:4', 'max:50'],
            'email'=>['required','email','unique:users,email'],
            'password'=>['required' ,Password::min(6)],
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
        ]);

        return to_route('login');
        
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('login');
    }
}
