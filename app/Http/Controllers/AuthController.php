<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // autentikasi email dan password harus diisi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Mendapatkan objek user yang diotentikasi

            $token = $user->createToken('auth_token')->plainTextToken;

            return response(['token' => $token, 'role' => $user->role]);
        }

        return response(['message' => 'Invalid credentials'], 401);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response(['message' => 'Successfully logged out']);
    }
}
