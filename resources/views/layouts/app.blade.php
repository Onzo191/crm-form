<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Title -->
    <title>
        @hasSection('title')
            @yield('title') |
        @endif CRM Nhóm 3
    </title>

    <!-- Include Styles -->
    @include('layouts.includes.styles')
</head>

<body>
    <!-- Layout Content -->
    {{ $slot }}

    <!-- Include Scripts -->
    @include('layouts.includes.scripts')
</body>

</html>
