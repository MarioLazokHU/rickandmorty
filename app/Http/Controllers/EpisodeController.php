<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'searchtext' => ['nullable', 'string'],
            'date' => ['nullable', 'string'],
            'orderBy' => ['sometimes', 'in:name,air_date'],
            'orderDirection' => ['sometimes', 'in:asc,desc'],
        ]);

        $searchtext = $validatedData['searchtext'] ?? null;
        $date = $validatedData['date'] ?? null;
        $orderBy = $validatedData['orderBy'] ?? 'air_date';
        $orderDirection = $validatedData['orderDirection'] ?? 'asc';

        $episodes = Episode::query()
            ->when($searchtext, function ($query, $searchtext) {
                $query->where(function ($query) use ($searchtext) {
                    $query->where('name', 'LIKE', "%{$searchtext}%")
                          ->orWhere('air_date', 'LIKE', "%{$searchtext}%");
                });
            })
            ->orderBy($orderBy, $orderDirection)
            ->simplePaginate(5);

        return view('episodes.index', compact('episodes'));
    }
}
