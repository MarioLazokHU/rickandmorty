<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Episode;
use App\Models\Character;

class FetchRickAndMortyData extends Command
{
    protected $signature = 'fetch:rickandmorty';
    protected $description = 'Fetch and store data from Rick and Morty API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $response = Http::get('https://rickandmortyapi.com/api/episode');
        $episodes = $response->json('results');

        foreach ($episodes as $episode) {
            $episodeModel = Episode::updateOrCreate(
                ['id' => $episode['id']],
                [
                    'name' => $episode['name'],
                    'air_date' => $episode['air_date'],
                    'episode' => $episode['episode']
                ]
            );

            foreach ($episode['characters'] as $characterUrl) {
                $characterResponse = Http::get($characterUrl);
                $character = $characterResponse->json();

                $characterModel = Character::updateOrCreate(
                    ['id' => $character['id']],
                    [
                        'name' => $character['name'],
                        'status' => $character['status'],
                        'species' => $character['species'],
                        'type' => $character['type'],
                        'gender' => $character['gender'],
                        'origin' => $character['origin']['name'],
                        'location' => $character['location']['name'],
                        'image' => $character['image']
                    ]
                );

                $episodeModel->characters()->attach($characterModel->id);
            }
        }

        $this->info('Data fetched and stored successfully.');
    }
}
