<?php

namespace App\Http\Controllers\Api\V1\View;

use Illuminate\Http\Request;
use App\Models\AuxProfile;
use App\Http\Controllers\Controller;

class AuxProfileController extends Controller
{
    public function getAuxProfile()
    {
        $auxProfiles = AuxProfile::with('user')->get();
        return response()->json($auxProfiles);
    }

    public function filterBySpecialty(Request $request)
    {
        $specialty = $request->input('specialty');
        $auxProfiles = AuxProfile::with('user')
            ->where('specialty', 'like', "%$specialty%")
            ->get();
        return response()->json($auxProfiles);
    }

    public function getAux(Request $request,$id)
    {
        $auxProfile = AuxProfile::with('user')->find($id);

        if (!$auxProfile) {
            return response()->json(['message' => 'Auxiliary profile not found'], 404);
        }

        return response()->json($auxProfile);
    }
    public function storeGoogleToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string|email',
        ]);

        // Buscar o crear el usuario basado en el email
        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name, 'password' => bcrypt(str_random(16))]
        );

        // Crear y guardar el token
        $token = new PersonalAccessToken();
        $token->tokenable_id = $user->id;
        $token->tokenable_type = get_class($user);
        $token->name = 'google';
        $token->token = hash('sha256', $request->token);
        $token->save();

        // Crear un token de acceso para el usuario
        $accessToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Google token stored successfully.', 'access_token' => $accessToken]);
    }

    public function storeFacebookToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string|email',
        ]);

        // Buscar o crear el usuario basado en el email
        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name, 'password' => bcrypt(str_random(16))]
        );

        // Crear y guardar el token
        $token = new PersonalAccessToken();
        $token->tokenable_id = $user->id;
        $token->tokenable_type = get_class($user);
        $token->name = 'facebook';
        $token->token = hash('sha256', $request->token);
        $token->save();

        // Crear un token de acceso para el usuario
        $accessToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Facebook token stored successfully.', 'access_token' => $accessToken]);
    }

    public function deleteToken(Request $request, $tokenName)
    {
        $user = $request->user();

        PersonalAccessToken::where('tokenable_id', $user->id)
            ->where('tokenable_type', get_class($user))
            ->where('name', $tokenName)
            ->delete();

        return response()->json(['message' => ucfirst($tokenName) . ' token deleted successfully.']);
    }
}
