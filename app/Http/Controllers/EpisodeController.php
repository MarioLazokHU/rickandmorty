<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(Request $request)
    {
        $searchtext = $request->input('searchtext');
        $date = $request->input('date');
        $orderBy = $request->input('orderBy', 'air_date');
        $orderDirection = $request->input('orderDirection', 'asc');

        $episodes = Episode::query()
            ->when($searchtext, function ($query, $searchtext) {
                $query->where(function ($query) use ($searchtext) {
                    $query->where('name', 'LIKE', "%{$searchtext}%")
                          ->orWhere('air_date', 'LIKE', "%{$searchtext}%");
                });
            })
            ->when($date, function ($query, $date) {
                $query->where('air_date', 'LIKE', "%{$date}%");
            })
            ->orderBy($orderBy, $orderDirection)
            ->simplePaginate(5);

        return view('episodes.index', compact('episodes'));
    }
}

