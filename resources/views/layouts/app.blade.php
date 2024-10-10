<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplicación')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('partials.navbar') <!-- Incluye la barra de navegación aquí -->

    <div class="content">
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
