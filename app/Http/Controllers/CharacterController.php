<?php

namespace App\Http\Controllers;

use App\Models\Episode;

class CharacterController extends Controller
{
    public function show($episodeId)
    {
        $episode = Episode::findOrFail($episodeId);
        $characters = $episode->characters;
        return response()->json($characters);  
    }
}
