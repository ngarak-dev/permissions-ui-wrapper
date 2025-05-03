@extends('permissions-ui::layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Create New Permission</span>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary btn-sm">Back to Permissions</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('permissions.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">Permission Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="group">Permission Group</label>
                            <select class="form-control @error('group') is-invalid @enderror" id="group" name="group">
                                <option value="">-- No Group --</option>
                                @foreach($groups as $key => $group)
                                <option value="{{ $key }}" {{ old('group')==$key ? 'selected' : '' }}>
                                    {{ $group['label'] }}
                                </option>
                                @endforeach
                            </select>
                            @error('group')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create Permission</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection