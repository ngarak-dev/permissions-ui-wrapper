@extends('permissions-ui::layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Edit Role: {{ $role->name }}</h2>
                <a href="{{ route('roles.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded">
                    Back to Roles
                </a>
            </div>

            <form method="POST" action="{{ route('roles.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Role Name</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                        id="name" name="name" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Permissions</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    class="form-checkbox h-5 w-5 text-blue-600" {{ in_array($permission->name,
                                $rolePermissions) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-start">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection