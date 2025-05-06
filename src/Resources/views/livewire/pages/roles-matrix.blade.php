@php
$namespace = config('permissions-ui.views.namespace', 'permission-wrapper');
@endphp

@extends($namespace . '::layouts.app')

@section('title', 'Role Permission Matrix')

@section('content')
<div class="container mx-auto py-6 px-4">
    @livewire('role-permission-matrix')
</div>
@endsection

@push('scripts')
@livewireScripts
@endpush

@push('styles')
@livewireStyles
@endpush