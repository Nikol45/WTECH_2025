<!DOCTYPE html>
<html lang="sk">
<head>
    @include('layout.partials.head')
</head>
<body>
    @include('layout.partials.nav')

    <main class="main-content">
        @yield('content')
    </main>

    @include('layout.partials.footer')
</body>
</html>