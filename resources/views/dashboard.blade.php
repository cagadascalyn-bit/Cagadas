@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="flex-shrink-0" style="background:var(--accent-soft);border-radius:12px;padding:12px;">
                    <i class="bi bi-people-fill" style="font-size:1.6rem;color:var(--accent);"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="color:var(--text-muted);font-size:.8rem;">Total Users</div>
                    <div style="font-size:1.8rem;font-weight:700;color:var(--accent);">{{ $totalUsers }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="flex-shrink-0" style="background:#06b6d422;border-radius:12px;padding:12px;">
                    <i class="bi bi-music-note-list" style="font-size:1.6rem;color:#06b6d4;"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="color:var(--text-muted);font-size:.8rem;">Total Songs</div>
                    <div style="font-size:1.8rem;font-weight:700;color:#06b6d4;">{{ $totalSongs }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="flex-shrink-0" style="background:#f59e0b22;border-radius:12px;padding:12px;">
                    <i class="bi bi-music-note-beamed" style="font-size:1.6rem;color:#f59e0b;"></i>
                </div>
                <div class="overflow-hidden">
                    <div style="color:var(--text-muted);font-size:.8rem;">My Songs</div>
                    <div style="font-size:1.8rem;font-weight:700;color:#f59e0b;">{{ Auth::user()->songs()->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-bar-chart me-2" style="color:var(--accent);"></i>Songs by Genre</div>
            <div class="card-body"><canvas id="genreChart"></canvas></div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pie-chart me-2" style="color:#06b6d4;"></i>Top 5 Users by Songs</div>
            <div class="card-body"><canvas id="userChart"></canvas></div>
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

const chartColor = getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim() || '#9ca3af';
const gridColor  = getComputedStyle(document.documentElement).getPropertyValue('--border').trim() || '#4d1a35';

new Chart(document.getElementById('genreChart'), {
    type: 'bar',
    data: {
        labels: genreLabels.length ? genreLabels : ['No data'],
        datasets: [{ label: 'Songs', data: genreCounts.length ? genreCounts : [0],
            backgroundColor: '#db277799', borderColor: '#f472b6', borderWidth: 1, borderRadius: 6 }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: chartColor } } },
        scales: {
            x: { ticks: { color: chartColor }, grid: { color: gridColor } },
            y: { ticks: { color: chartColor }, grid: { color: gridColor }, beginAtZero: true }
        }
    }
});

new Chart(document.getElementById('userChart'), {
    type: 'doughnut',
    data: {
        labels: userLabels.length ? userLabels : ['No data'],
        datasets: [{ data: userCounts.length ? userCounts : [1],
            backgroundColor: ['#db2777','#ec4899','#f472b6','#fb7185','#be185d'],
            borderColor: 'var(--bg2)', borderWidth: 3 }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: chartColor } } }
    }
});
</script>
@endpush
