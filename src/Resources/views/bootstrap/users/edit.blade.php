@extends('permissions-ui::layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Manage Roles for: {{ $user->name }}</span>
                    <a href="{{ route('user.roles.index') }}" class="btn btn-secondary btn-sm">Back to Users</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('user.roles.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label>Assign Roles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]"
                                            value="{{ $role->name }}" id="role_{{ $role->id }}" {{ in_array($role->name,
                                        $userRoles) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Roles</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection