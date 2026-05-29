<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalSongs = Song::count();

        // Songs per genre for chart
        $genreData = Song::selectRaw('genre, COUNT(*) as count')
            ->whereNotNull('genre')
            ->groupBy('genre')
            ->pluck('count', 'genre');

        // Songs per user (top 5)
        $songsPerUser = User::withCount('songs')->orderByDesc('songs_count')->take(5)->get();

        return view('dashboard', compact('totalUsers', 'totalSongs', 'genreData', 'songsPerUser'));
    }
}
