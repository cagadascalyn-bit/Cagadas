@extends('layouts.app')
@section('title', 'My Playlist')

@section('content')

{{-- Now Playing Bar --}}
<div id="playerBar" class="card mb-3 p-3" style="display:none;">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="flex-shrink-0" style="background:var(--accent-soft);border-radius:10px;padding:10px;">
            <i class="bi bi-music-note-beamed" style="color:var(--accent);font-size:1.4rem;"></i>
        </div>
        <div class="flex-grow-1" style="min-width:100px;">
            <div id="nowTitle" style="color:var(--accent);font-weight:600;font-size:.95rem;"></div>
            <div id="nowArtist" style="color:var(--text-muted);font-size:.8rem;"></div>
        </div>
        <audio id="audioPlayer" controls style="width:100%;max-width:320px;accent-color:#db2777;">
            Your browser does not support audio.
        </audio>
        <button class="btn btn-sm btn-outline-secondary flex-shrink-0" onclick="stopPlayer()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
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
                        <th class="d-none d-sm-table-cell">Artist</th>
                        <th class="d-none d-md-table-cell">Album</th>
                        <th class="d-none d-lg-table-cell">Genre</th>
                        <th class="d-none d-lg-table-cell">Year</th>
                        <th>Play</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($songs as $song)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div><i class="bi bi-music-note me-1" style="color:var(--accent);"></i>{{ $song->title }}</div>
                            <div class="d-sm-none" style="font-size:.78rem;color:var(--text-muted);">{{ $song->artist }}</div>
                        </td>
                        <td class="d-none d-sm-table-cell">{{ $song->artist }}</td>
                        <td class="d-none d-md-table-cell">{{ $song->album ?? '—' }}</td>
                        <td class="d-none d-lg-table-cell">
                            @if($song->genre)
                                <span class="badge badge-genre">{{ $song->genre }}</span>
                            @else —
                            @endif
                        </td>
                        <td class="d-none d-lg-table-cell">{{ $song->year ?? '—' }}</td>
                        <td>
                            @if($song->file_path)
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="playSong('{{ asset('storage/' . $song->file_path) }}', '{{ addslashes($song->title) }}', '{{ addslashes($song->artist) }}')">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                            @else
                                <span style="color:var(--text-muted);font-size:.8rem;">—</span>
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
                    <tr><td colspan="8" class="text-center py-4" style="color:var(--text-muted);">No songs yet. Add your first song!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Song Modal --}}
<div class="modal fade" id="addSongModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('songs.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:var(--accent);">Add Song</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Artist</label>
                            <input type="text" name="artist" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Album</label>
                            <input type="text" name="album" class="form-control">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Genre</label>
                            <input type="text" name="genre" class="form-control">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Year</label>
                            <input type="number" name="year" class="form-control" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="color:var(--text-muted);">Audio File <small>(mp3, wav, ogg — max 20MB)</small></label>
                            <input type="file" name="file_path" class="form-control" accept=".mp3,.wav,.ogg">
                        </div>
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" id="editSongForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:var(--accent);">Edit Song</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Title</label>
                            <input type="text" name="title" id="eTitle" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Artist</label>
                            <input type="text" name="artist" id="eArtist" class="form-control" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Album</label>
                            <input type="text" name="album" id="eAlbum" class="form-control">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Genre</label>
                            <input type="text" name="genre" id="eGenre" class="form-control">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label" style="color:var(--text-muted);">Year</label>
                            <input type="number" name="year" id="eYear" class="form-control" min="1900" max="{{ date('Y') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="color:var(--text-muted);">Replace Audio File <small>(optional)</small></label>
                            <input type="file" name="file_path" class="form-control" accept=".mp3,.wav,.ogg">
                        </div>
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
    player.pause(); player.src = '';
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
