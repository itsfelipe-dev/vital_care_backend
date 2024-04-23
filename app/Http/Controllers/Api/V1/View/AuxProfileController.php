<?php

namespace App\Http\Controllers\Api\V1\View;

use Illuminate\Http\Request;
use App\Models\AuxProfile;
use App\Http\Controllers\Controller;

class AuxProfileController extends Controller
{
    public function getAuxProfile()
    {
        $auxProfiles = AuxProfile::all();
        return response()->json($auxProfiles);
    }

    public function filterBySpecialty(Request $request)
    {
        $specialty = $request->input('specialty');
        $auxProfiles = AuxProfile::where('specialty', 'like', "%$specialty%")->get();
        return response()->json($auxProfiles);
    }


}
