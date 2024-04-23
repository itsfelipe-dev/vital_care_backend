<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'rol' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            // 'role'=> $validatedData['rol'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $tokenExpiration = now()->addMinutes(config('sanctum.expiration'));


        $user->assignRole($validatedData['rol']);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'token_expiration' => $tokenExpiration,
        ]);

    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        $tokenExpiration = now()->addMinutes(config('sanctum.expiration'));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'token_expiration' => $tokenExpiration,
        ]);
    }

    public function home(Request $request)
    {
        return $request->user();

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
