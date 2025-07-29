<!DOCTYPE html>
<html>
<head>
    <title>Menu App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>