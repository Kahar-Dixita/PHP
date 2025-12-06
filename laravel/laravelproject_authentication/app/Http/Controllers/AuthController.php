<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'age'      => 'required|integer|min:10|max:99',
            'role'     => 'required|in:student,admin',   // ✅ added role validation
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'age'      => $request->age,
            'role'     => $request->role, // ✅ dynamic role from form
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required'
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        $user = Auth::user();

        // ROLE BASED REDIRECT
        if ($user->role === 'student') {
            return redirect()->route('books.index');   // student redirect
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');  // admin redirect
        }

        // fallback
        return redirect()->route('books.index');
    }

    return back()->with('error', 'Invalid credentials!');
}


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
