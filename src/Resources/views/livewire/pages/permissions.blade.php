@php
$namespace = config('permissions-ui.views.namespace', 'permission-wrapper');
@endphp

@extends($namespace . '::layouts.app')

@section('title', 'Permissions Management')

@section('content')
<div class="container mx-auto py-6 px-4">
    @livewire('permission-manager')
</div>
@endsection

@push('scripts')
@livewireScripts
@endpush

@push('styles')
@livewireStyles
@endpush