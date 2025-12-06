<?php

namespace App\Http\Controllers;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Bus\DispatchesJobs;
// use Illuminate\Foundation\Validation\ValidateRequestes;
// Use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestController extends Controller
{
  public function showRegister()
  {
    return view('auth.register');
  }

  public function register(Request $request)
  {
    $request->validate([
        'name'=>'required|min:3|max:9',
        'email'=>'required|email|unique:users',
        'password'=>'required|max:6',
        'age'=>'required|integer|min:10|max:99'
    ]);

    User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
        'age'=>$request->age,
        'role'=>'student',
    ]);

    return redirect()->route('login')->with('success','register successfully');
  }

  public function showlogin()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $request->validate([
        'email'=>'required|email',
        'password'=>'required',
    ]);

    $credientals = $request->only('email','password');

    if(Auth::attempt($credientals))
    {
        return redirect()->route('books.index');
    }

    return back()->with('error','unauthorized credientals');
  }

  public function logout()
  {
    Auth::logout();
    return back()->route('login');
  }

//   public function __construct()
//   {
//     $this->middleware('auth');
//   }
}
