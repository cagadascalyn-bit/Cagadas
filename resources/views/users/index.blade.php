@extends('layouts.app')
@section('title', 'Users Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2" style="color:#f472b6;"></i>All Users</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-lg me-1"></i>Add User
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" onclick="openEdit({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                                  onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4" style="color:#6b7280;">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#f472b6;">Add User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editUserForm">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#f472b6;">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color:#9ca3af;">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
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
function openEdit(id, name, email) {
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editUserForm').action = '/users/' + id;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
</script>
@endpush
