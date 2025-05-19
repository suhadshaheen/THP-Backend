<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated  = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required',
            'city' => 'required',
            'country' => 'required',
            'username' => 'required|unique:users',
            'role' => 'required',
        ]);
        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'username' => $validated['username'],
            'role' => $validated['role'],

        ]);
        return response()->json(['message' => 'User registered'], 201);
    }
    public function login(Request $request){
        $validated  = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if(Auth::attempt($validated)){
            $request->session()->regenerate();
            return response()->json(['message' => ' logged in successfully.'], 200);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);

    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => ' logged out successfully.'], 200);

    }
}
