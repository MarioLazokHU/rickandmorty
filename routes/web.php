<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\CharacterController;

Route::get('/', [EpisodeController::class, 'index']);
Route::get('episodes/search', [EpisodeController::class, 'index']);
Route::get('/episodes/{episode}/characters', [CharacterController::class, 'show']);
