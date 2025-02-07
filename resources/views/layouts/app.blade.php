<!DOCTYPE html>
<html lang="en">
    {{-- <style>
        html, body {
    height: 100%; /* Pastikan body mengisi seluruh tinggi layar */
}

body {
    display: flex;
    flex-direction: column;
}

main {
    flex: 1; /* Memastikan main mengambil ruang yang tersisa */
}


</style> --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'List API OPD')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Menyertakan CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link untuk CSS Prism.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet" />
    <!-- Link untuk JS Prism.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>

    <!-- Menyertakan JS Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mengintegrasikan Tailwind CSS dan JS melalui Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    @include('components.navbar') <!-- Pastikan Anda memiliki komponen navbar -->

    <!-- Content -->
    <main class="max-w-screen-xl mx-auto mt-8 px-5">
        @yield('content')
    </main>

    <!-- Footer -->
    {{-- <footer class="bg-gray-800 text-white text-center py-1">
        <p>&copy; {{ date('Y') }} Portal Data Jateng. All rights reserved.</p>
    </footer> --}}

    <!-- Menyertakan jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Skrip lainnya -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
</body>
</html>
