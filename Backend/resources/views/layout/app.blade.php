<!DOCTYPE html>
<html lang="sk">
<head>
    @include('layout.partials.head')
</head>
<body class="d-flex flex-column min-vh-100" data-has-errors="{{ $errors->isNotEmpty() ? 'true' : 'false' }}" data-is-edit="{{ old('_method') === 'PUT' ? 'true' : 'false' }}">
    @include('layout.partials.nav')

    <main class="main-content container-fluid custom-fluid py-4 flex-grow-1">
        @yield('content')
    </main>

    @include('layout.partials.footer')
</body>
</html>