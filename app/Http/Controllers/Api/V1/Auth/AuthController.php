<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\AuxProfile;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role_id' => $validatedData['role_id'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $user->assignRole($validatedData['role_id']);

        $token = $user->createToken('auth_token')->plainTextToken;
        $tokenExpiration = now()->addMinutes(config('sanctum.expiration'));

        $response = [
            'user_id' => $user->id, // Agregar el user_id en la respuesta
            'access_token' => $token,
            'token_type' => 'Bearer',
            'token_expiration' => $tokenExpiration,
            'name' => $user->name,
            'role_id' => $user->role_id,
        ];

        if ($validatedData['role_id'] === 'aux') {
            $response['message'] = 'Auxiliary user created. Please complete the auxiliary profile.';
        }

        return response()->json($response);
    }

    public function completeAuxProfile(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'birthday' => 'required|date',
            'specialty' => 'required|string|max:255',
            'profile_image' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'stars' => 'required|numeric|min:0|max:5',
            'city' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($validatedData['user_id']);

        if (!$user->hasRole('aux')) {
            return response()->json(['message' => 'User is not an auxiliary.'], 403);
        }

        $auxProfile = AuxProfile::create([
            'user_id' => $user->id,
            'birthday' => $validatedData['birthday'],
            'specialty' => $validatedData['specialty'],
            'profile_image' => $validatedData['profile_image'],
            'description' => $validatedData['description'],
            'stars' => $validatedData['stars'],
            'city' => $validatedData['city'],
        ]);

        return response()->json([
            'message' => 'Auxiliary profile completed successfully.',
            'aux_profile' => $auxProfile,
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
            'name' => $user->name,
            'role_id' => $user->role_id,
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