@php
$namespace = config('permissions-ui.views.namespace', 'permission-wrapper');
@endphp

@extends($namespace . '::layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Manage Roles for: {{ $user->name }}</h2>
                <a href="{{ route('user.roles.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded">
                    Back to Users
                </a>
            </div>

            <form method="POST" action="{{ route('user.roles.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Assign Roles</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    class="form-checkbox h-5 w-5 text-blue-600" {{ in_array($role->name, $userRoles) ?
                                'checked' : '' }}>
                                <span class="ml-2 text-gray-700">{{ $role->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-start">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Roles
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection