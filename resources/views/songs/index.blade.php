@extends('layouts.app')
@section('title', 'My Playlist')

@section('content')

{{-- Now Playing Bar --}}
<div id="playerBar" class="card mb-3 p-3" style="display:none!important;">
    <div class="d-flex align-items-center gap-3">
        <div style="background:#db277722;border-radius:10px;padding:10px;">
            <i class="bi bi-music-note-beamed" style="color:#a78bfa;font-size:1.4rem;"></i>
        </div>
        <div class="flex-grow-1">
            <div id="nowTitle" style="color:#f472b6;font-weight:600;"></div>
            <div id="nowArtist" style="color:#6b7280;font-size:.85rem;"></div>
        </div>
        <audio id="audioPlayer" controls style="width:320px;accent-color:#7c3aed;">
            Your browser does not support audio.
        </audio>
        <button class="btn btn-sm btn-outline-secondary" onclick="stopPlayer()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-music-note-list me-2" style="color:#06b6d4;"></i>My Playlist</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSongModal">
            <i class="bi bi-plus-lg me-1"></i>Add Song
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Album</th>
                        <th>Genre</th>
                        <th>Year</th>
                        <th>Play</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($songs as $song)
                    <tr id="row-{{ $song->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><i class="bi bi-music-note me-1" style="color:#f472b6;"></i>{{ $song->title }}</td>
                        <td>{{ $song->artist }}</td>
                        <td>{{ $song->album ?? '—' }}</td>
                        <td>
                            @if($song->genre)
                                <span class="badge badge-genre">{{ $song->genre }}</span>
                            @else —
                            @endif
                        </td>
                        <td>{{ $song->year ?? '—' }}</td>
                        <td>
                            @if($song->file_path)
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="playSong('{{ asset('storage/' . $song->file_path) }}', '{{ addslashes($song->title) }}', '{{ addslashes($song->artist) }}')">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                            @else
                                <span style="color:#4b5563;font-size:.8rem;">No file</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-info"
                                onclick="openEditSong({{ $song->id }}, '{{ addslashes($song->title) }}', '{{ addslashes($song->artist) }}', '{{ addslashes($song->album) }}', '{{ addslashes($song->genre) }}', '{{ $song->year }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('songs.destroy', $song) }}" class="d-inline"
                                  onsubmit="return confirm('Remove this song?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4" style="color:#6b7280;">No songs yet. Add your first song!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Song Modal --}}
<div class="modal fade" id="addSongModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('songs.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#f472b6;">Add Song</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Artist</label>
                        <input type="text" name="artist" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Album</label>
                        <input type="text" name="album" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Genre</label>
                        <input type="text" name="genre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Year</label>
                        <input type="number" name="year" class="form-control" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Audio File <small>(mp3, wav, ogg — max 20MB)</small></label>
                        <input type="file" name="file_path" class="form-control" accept=".mp3,.wav,.ogg">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Song</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Song Modal --}}
<div class="modal fade" id="editSongModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editSongForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#f472b6;">Edit Song</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Title</label>
                        <input type="text" name="title" id="eTitle" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Artist</label>
                        <input type="text" name="artist" id="eArtist" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Album</label>
                        <input type="text" name="album" id="eAlbum" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Genre</label>
                        <input type="text" name="genre" id="eGenre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Year</label>
                        <input type="number" name="year" id="eYear" class="form-control" min="1900" max="{{ date('Y') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Replace Audio File <small>(optional)</small></label>
                        <input type="file" name="file_path" class="form-control" accept=".mp3,.wav,.ogg">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function playSong(url, title, artist) {
    const player = document.getElementById('audioPlayer');
    const bar    = document.getElementById('playerBar');
    player.src   = url;
    document.getElementById('nowTitle').textContent  = title;
    document.getElementById('nowArtist').textContent = artist;
    bar.style.display = 'block';
    player.play();
}

function stopPlayer() {
    const player = document.getElementById('audioPlayer');
    player.pause();
    player.src = '';
    document.getElementById('playerBar').style.display = 'none';
}

function openEditSong(id, title, artist, album, genre, year) {
    document.getElementById('eTitle').value  = title;
    document.getElementById('eArtist').value = artist;
    document.getElementById('eAlbum').value  = album !== 'null' ? album : '';
    document.getElementById('eGenre').value  = genre !== 'null' ? genre : '';
    document.getElementById('eYear').value   = year  !== 'null' ? year  : '';
    document.getElementById('editSongForm').action = '/songs/' + id;
    new bootstrap.Modal(document.getElementById('editSongModal')).show();
}
</script>
@endpush
