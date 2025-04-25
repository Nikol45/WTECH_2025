@extends('layout.app')

@section('content')
    <div class="container text-center py-5">
        <h1>Ďakujeme za objednávku!</h1>
        <p class="mt-3">O pár sekúnd budete presmerovaný na úvodnú stránku.</p>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = '{{ route('homepage') }}';
        }, 5000); // 5 sekúnd
    </script>
@endsection
