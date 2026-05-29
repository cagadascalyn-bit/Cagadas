@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="mb-3">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" class="rounded-circle" width="100" height="100" style="object-fit:cover;border:3px solid #db2777;">
                @else
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width:100px;height:100px;background:#db2777;font-size:2.5rem;color:#fff;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <h5 style="color:#f472b6;">{{ $user->name }}</h5>
            <p style="color:#6b7280;">{{ $user->email }}</p>
            @if($user->gender)
                <span class="badge" style="background:#db277722;color:#f472b6;border:1px solid #db277744;">{{ $user->gender }}</span>
            @endif
            @if($user->address)
                <p class="mt-2" style="color:#9ca3af;font-size:.85rem;"><i class="bi bi-geo-alt me-1"></i>{{ $user->address }}</p>
            @endif
            <hr style="border-color:#4d1a35;">
            <div class="d-flex justify-content-around">
                <div>
                    <div style="color:#f472b6;font-weight:700;font-size:1.3rem;">{{ $user->songs()->count() }}</div>
                    <div style="color:#6b7280;font-size:.8rem;">Songs</div>
                </div>
                <div>
                    <div style="color:#06b6d4;font-weight:700;font-size:1.3rem;">{{ $user->created_at->diffForHumans() }}</div>
                    <div style="color:#6b7280;font-size:.8rem;">Joined</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Form --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2" style="color:#f472b6;"></i>Edit Profile</div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="color:#9ca3af;">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color:#9ca3af;">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color:#9ca3af;">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select...</option>
                                @foreach(['Male','Female','Other'] as $g)
                                    <option value="{{ $g }}" {{ old('gender', $user->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color:#9ca3af;">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color:#9ca3af;">New Password <small>(leave blank to keep)</small></label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color:#9ca3af;">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="color:#9ca3af;">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
