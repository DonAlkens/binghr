<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bing Hr </title>

    <link rel="stylesheet" href="{{ asset('/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/styles.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <div class="bhr__app d-flex">

        @include('shared.sidebar')

        <div class="bhr__content flex-fill">
            <div class="bhr__content-wrapper">
                @include('shared.navbar')

                {{-- Content here --}}
                @yield('content')

                @include('shared.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script src="{{ asset('/assets/js/main.js') }}"></script>
</body>

</html>
