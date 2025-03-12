<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'List API OPD')</title>
    
    <!-- FontAwesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Bootstrap & Prism.js -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Layout utama */
        body {
            display: flex;
            height: 100vh; /* Pastikan memenuhi layar penuh */
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #1F2937;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
        }

        /* Konten utama */
        .content {
            flex-grow: 1; /* Isi sisa lebar layar */
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Footer tetap di bawah */
        .footer {
            background-color: #1F2937;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }

        /* Sidebar Responsif */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
                padding: 10px;
            }
            .content {
                margin-left: 80px;
            }
            .sidebar a span {
                display: none;
            }
        }

        /* Login Page Styling */
        .login-page .content {
            margin-left: 0 !important;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>

<body class="{{ request()->routeIs('login') ? 'login-page' : '' }}">
    @auth
    <!-- Sidebar -->
    <aside class="sidebar">
    <a href="{{ url('/') }}" class="block text-2xl font-bold mb-4 text-white hover:text-gray-300">
            Dashboard
    </a>

        <nav x-data="{ open: false }">
            <ul>
                <!-- Menu Integrasi API -->
                <li class="mb-2">
                    <button @click="open = !open" class="block py-2 px-4 rounded-md bg-green-500 hover:bg-green-600 w-full text-left flex justify-between items-center transition-all duration-300">
                        <span><i class="fa fa-network-wired mr-2"></i> Integrasi API</span>
                        <i class="fa fa-chevron-down transition-transform" :class="open ? 'rotate-180' : 'rotate-0'"></i>
                    </button>
                    <div x-show="open" class="mt-1 bg-gray-800 border border-gray-700 rounded-md shadow-lg overflow-hidden origin-top">
                        <ul>
                            <li>
                                <a href="{{ route('api.index') }}" class="block py-2 px-4 text-gray-200 hover:bg-green-600 hover:text-white rounded-md transition-all">
                                    Daftar API OPD
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('apibps.index') }}" class="block py-2 px-4 text-gray-200 hover:bg-green-600 hover:text-white rounded-md transition-all">
                                    Daftar API BPS
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu Visualisasi API -->
                <li class="mb-2">
                    <a href="{{ route('visualisasi.index') }}" class="block py-2 px-4 rounded-md bg-blue-500 hover:bg-blue-600 transition-all duration-300">
                        <i class="fa fa-chart-bar mr-2"></i> <span>Visualisasi API</span>
                    </a>
                </li>
            </ul>
        </nav>

        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Menu Setting & Logout -->
        <div class="mt-auto">
            <div class="dropdown">
                <button class="w-full py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-left dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-cog mr-2"></i> <span>Setting</span>
                </button>
                <ul class="dropdown-menu w-full" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="{{ route('visualisasi.create') }}">
                            <i class="fa fa-key mr-2"></i> Add Token Instansi
                        </a>
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full py-2 px-4 rounded-md bg-red-500 hover:bg-red-600 text-white">
                    <i class="fa fa-sign-out-alt mr-2"></i> <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <!-- Konten Utama -->
    <div class="content">
        <main class="max-w-screen-xl mx-auto mt-8 px-5">
            @yield('content')
        </main>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>
