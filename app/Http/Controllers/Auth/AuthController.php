<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',

        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return response()->json([
            'message' => 'Account Register Successfully',
        ], 201);
    }

    public function login(Request $request)
    {
       $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'sometimes|boolean'
        ]);

        if(Auth::attempt($request->only(['email','password']),$request->remember_me)){
            $user = Auth::user();

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ]);
        }

         return response()->json([
                'message' => "Bad credentials",
            ],401);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->accessToken->delete();
        return response()->noContent();
    }
}
