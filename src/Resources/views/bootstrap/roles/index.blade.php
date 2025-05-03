@extends('permissions-ui::layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Roles Management</span>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">Create New Role</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search roles...">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="rolesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @foreach($role->permissions as $permission)
                                        <span class="badge bg-info text-dark">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('roles.edit', $role) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm"
                                                data-name="{{ $role->name }}">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('rolesTable');
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });

    // Confirm delete
    document.querySelectorAll('.delete-confirm').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm(`Are you sure you want to delete the role "${this.dataset.name}"?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection