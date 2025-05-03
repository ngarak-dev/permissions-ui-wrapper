@extends('permissions-ui::layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Create New Permission</h2>
                <a href="{{ route('permissions.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded">
                    Back to Permissions
                </a>
            </div>

            <form method="POST" action="{{ route('permissions.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Permission Name</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                        id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="group" class="block text-gray-700 text-sm font-bold mb-2">Permission Group</label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('group') border-red-500 @enderror"
                        id="group" name="group">
                        <option value="">-- No Group --</option>
                        @foreach($groups as $key => $group)
                        <option value="{{ $key }}" {{ old('group')==$key ? 'selected' : '' }}>
                            {{ $group['label'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('group')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-start">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection