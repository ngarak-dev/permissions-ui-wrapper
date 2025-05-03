<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Permissions Management</title>
    
    @if(config('permissions-ui.ui_framework') === 'bootstrap')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="{{ config('permissions-ui.ui_framework') === 'bootstrap' ? '' : 'bg-gray-100' }}">
    <div class="{{ config('permissions-ui.ui_framework') === 'bootstrap' ? 'container mt-4' : '' }}">
        @yield('content')
    </div>
    
    @if(config('permissions-ui.ui_framework') === 'bootstrap')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @endif
</body>
</html>