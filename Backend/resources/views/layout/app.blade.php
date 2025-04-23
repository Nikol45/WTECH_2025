<!DOCTYPE html>
<html lang="sk">
<head>
    @include('layout.partials.head')
</head>
<body>
    @include('layout.partials.nav')

    <main class="main-content container-fluid custom-fluid py-4">
        @yield('content')
    </main>

    @include('layout.partials.footer')
</body>
</html>