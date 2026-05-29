@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#db277722;border-radius:12px;padding:14px;">
                    <i class="bi bi-people-fill" style="font-size:1.8rem;color:#f472b6;"></i>
                </div>
                <div>
                    <div style="color:#6b7280;font-size:.85rem;">Total Users</div>
                    <div style="font-size:2rem;font-weight:700;color:#f472b6;">{{ $totalUsers }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#06b6d422;border-radius:12px;padding:14px;">
                    <i class="bi bi-music-note-list" style="font-size:1.8rem;color:#06b6d4;"></i>
                </div>
                <div>
                    <div style="color:#6b7280;font-size:.85rem;">Total Songs</div>
                    <div style="font-size:2rem;font-weight:700;color:#06b6d4;">{{ $totalSongs }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="background:#f59e0b22;border-radius:12px;padding:14px;">
                    <i class="bi bi-music-note-beamed" style="font-size:1.8rem;color:#f59e0b;"></i>
                </div>
                <div>
                    <div style="color:#6b7280;font-size:.85rem;">My Songs</div>
                    <div style="font-size:2rem;font-weight:700;color:#f59e0b;">{{ Auth::user()->songs()->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-bar-chart me-2" style="color:#f472b6;"></i>Songs by Genre</div>
            <div class="card-body"><canvas id="genreChart" height="220"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pie-chart me-2" style="color:#06b6d4;"></i>Top 5 Users by Songs</div>
            <div class="card-body"><canvas id="userChart" height="220"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const genreLabels = @json($genreData->keys());
const genreCounts = @json($genreData->values());
const userLabels  = @json($songsPerUser->pluck('name'));
const userCounts  = @json($songsPerUser->pluck('songs_count'));

new Chart(document.getElementById('genreChart'), {
    type: 'bar',
    data: {
        labels: genreLabels.length ? genreLabels : ['No data'],
        datasets: [{ label: 'Songs', data: genreCounts.length ? genreCounts : [0],
            backgroundColor: '#db277799', borderColor: '#f472b6', borderWidth: 1, borderRadius: 6 }]
    },
    options: { plugins: { legend: { labels: { color: '#e0e0e0' } } },
        scales: { x: { ticks: { color: '#9ca3af' }, grid: { color: '#4d1a35' } },
                  y: { ticks: { color: '#9ca3af' }, grid: { color: '#4d1a35' }, beginAtZero: true } } }
});

new Chart(document.getElementById('userChart'), {
    type: 'doughnut',
    data: {
        labels: userLabels.length ? userLabels : ['No data'],
        datasets: [{ data: userCounts.length ? userCounts : [1],
            backgroundColor: ['#db2777','#ec4899','#f472b6','#fb7185','#be185d'],
            borderColor: '#1a0010', borderWidth: 3 }]
    },
    options: { plugins: { legend: { labels: { color: '#e0e0e0' } } } }
});
</script>
@endpush
