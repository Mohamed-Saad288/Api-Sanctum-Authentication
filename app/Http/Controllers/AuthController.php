<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:7'
        ]);
        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password'])
        ]);
        $token = $user->createToken('user_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function login(Request $request)
    {
        $attributes = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:7'
        ]);
       $user = User::where('email',$attributes['email'])->first();

        if (!$user || !Hash::check($attributes['password'],$user->password))
        {
            return response([
               'message' => 'bad cred'
            ],401);
        }
        $token = $user->createToken('user_token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'User Logged out'
        ];
    }
}
