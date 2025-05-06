<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Permissions Management')</title>

    @if(config('permissions-ui.ui_framework') === 'bootstrap')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @else
    <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="{{ config('permissions-ui.ui_framework') === 'bootstrap' ? '' : 'bg-gray-100' }}">
    <div class="{{ config('permissions-ui.ui_framework') === 'bootstrap' ? 'container mt-4' : '' }}">
        @yield('content')
    </div>

    @if(config('permissions-ui.ui_framework') === 'bootstrap')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @endif

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Additional Scripts -->
    @stack('scripts')

    <!-- Notification Script for Livewire Events -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Livewire v3 syntax for event listeners
            Livewire.on('notify', (data) => {
                // Use Toastify for better toast notifications
                Toastify({
                    text: data.message,
                    duration: 3000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    className: "bg-green-500",
                    onClick: function(){} // Callback after click
                }).showToast();
            });
        });
    </script>
</body>

</html>