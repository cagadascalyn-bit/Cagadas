<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function index()
    {
        $songs = Song::where('user_id', Auth::id())->latest()->get();
        return view('songs.index', compact('songs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'artist'    => 'required|string|max:255',
            'album'     => 'nullable|string|max:255',
            'genre'     => 'nullable|string|max:100',
            'year'      => 'nullable|integer|min:1900|max:' . date('Y'),
            'file_path' => 'nullable|file|mimes:mp3,wav,ogg|max:20480',
        ]);

        $data = $request->only('title', 'artist', 'album', 'genre', 'year');

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('songs', 'public');
        }

        Auth::user()->songs()->create($data);
        return redirect()->route('songs.index')->with('toast_success', 'Song added to playlist!');
    }

    public function update(Request $request, Song $song)
    {
        abort_if($song->user_id !== Auth::id(), 403);

        $request->validate([
            'title'     => 'required|string|max:255',
            'artist'    => 'required|string|max:255',
            'album'     => 'nullable|string|max:255',
            'genre'     => 'nullable|string|max:100',
            'year'      => 'nullable|integer|min:1900|max:' . date('Y'),
            'file_path' => 'nullable|file|mimes:mp3,wav,ogg|max:20480',
        ]);

        $data = $request->only('title', 'artist', 'album', 'genre', 'year');

        if ($request->hasFile('file_path')) {
            if ($song->file_path) Storage::disk('public')->delete($song->file_path);
            $data['file_path'] = $request->file('file_path')->store('songs', 'public');
        }

        $song->update($data);
        return redirect()->route('songs.index')->with('toast_success', 'Song updated successfully!');
    }

    public function destroy(Song $song)
    {
        abort_if($song->user_id !== Auth::id(), 403);
        if ($song->file_path) Storage::disk('public')->delete($song->file_path);
        $song->delete();
        return redirect()->route('songs.index')->with('toast_success', 'Song removed from playlist!');
    }
}
