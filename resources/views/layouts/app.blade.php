<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Title --}}
    <link rel="icon" type="image/png" href="">
    <title>Hr3 Lumino</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-slate-50">
    <!-- Include Sidebar Component -->
    @include('layouts.includes.sidebar')

    <!-- Main Content -->
    <main class="ml-64 min-h-screen">
        <!-- Include Header Component -->
        @include('layouts.includes.header')

        <!-- Content -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>

    <script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id + '-dropdown');
        const arrow = document.getElementById(id + '-arrow');
        
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    </script>
</body>
</html>