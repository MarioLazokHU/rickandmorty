<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CharacterController extends Controller
{
    public function show($episodeId)
    {
        $validator = Validator::make(['episodeId' => $episodeId], [
            'episodeId' => ['required', 'integer', 'exists:episodes,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid episode ID'
            ], 400);
        }

        $episode = Episode::findOrFail($episodeId);
        $characters = $episode->characters;

        return response()->json($characters);
    }
}
