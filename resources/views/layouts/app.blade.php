<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .navbar {
            z-index: 1050;
            position: sticky;
            top: 0;
            width: 100%;
        }

        .sidebar {
            margin-top: 2px; /* Ajustez cette valeur à la hauteur de la navbar */
            position: fixed;
            height: calc(100vh - 70px); /* Hauteur totale moins la navbar */
            background-color: #343a40;
            color: #fff;
            overflow-y: auto;
            width: 250px;
            z-index: 1040;
        }


        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        .sidebar a:hover, .sidebar .active {
            background-color: #007bff;
            border-radius: 4px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
        }
        #sidebarChart {
        max-width: 150px; /* Largeur maximale */
        margin: 10px auto; /* Centrer le graphique */
        }
    </style>
    
</head>
<body>
    <div id="app">
        <!-- Barre de navigation -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 80px; height: 70px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @auth
            <!-- Sidebar Dynamique -->
            @if(auth()->user()->role === 'admin')
                <!-- Sidebar Admin -->
                <nav class="sidebar">
                    <div class="p-4">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
                                    <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.setting') }}" class="nav-link @if(request()->routeIs('admin.settings')) active @endif">
                                    <i class="fas fa-cogs"></i> Paramètres Globaux
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.users') }}" class="nav-link @if(request()->routeIs('admin.users')) active @endif">
                                    <i class="fas fa-users"></i> Gestion des Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.credit') }}" class="nav-link @if(request()->routeIs('admin.credit')) active @endif">
                                    <i class="fas fa-credit-card"></i> Gestion des Crédits
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.amendes') }}" class="nav-link @if(request()->routeIs('admin.amende')) active @endif">
                                    <i class="fas fa-exclamation-circle"></i> Gestion des Amendes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.solidarites') }}" class="nav-link @if(request()->routeIs('admin.solidarites')) active @endif">
                                    <i class="fas fa-hand-holding-heart"></i> Gestion des Solidarités
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.graphique') }}" class="nav-link @if(request()->routeIs('admin.graphique')) active @endif">
                                    <i class="fas fa-chart-bar"></i> Graphiques
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            @elseif(auth()->user()->role === 'user')
                <!-- Sidebar Utilisateur -->
                <nav class="sidebar">
                    <div class="p-4">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                                    <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transactions.epargne') }}" class="nav-link @if(request()->routeIs('transactions.epargne')) active @endif">
                                    <i class="fas fa-piggy-bank"></i> Mes Épargnes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transactions.credit') }}" class="nav-link @if(request()->routeIs('transactions.credit')) active @endif">
                                    <i class="fas fa-credit-card"></i> Mes Crédits
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transactions.solidarite') }}" class="nav-link @if(request()->routeIs('transactions.solidarite')) active @endif">
                                    <i class="fas fa-hand-holding-heart"></i> Mes Solidarités
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transactions.amende') }}" class="nav-link @if(request()->routeIs('transactions.amende')) active @endif">
                                    <i class="fas fa-exclamation-circle"></i> Mes Amendes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.credits.priority') }}" class="nav-link @if(request()->routeIs('user.credits.priority')) active @endif">
                                    <i class="fas fa-list-ol"></i> Priorités des Crédits
                                </a>
                            </li>

                        </ul>
                    </div>
                </nav>
            @endif
        @endauth

        <!-- Contenu principal -->
        <div @auth class="content" @endauth>
            @yield('content')
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzPNr5KA0IbcBiNJE8bDxBIqjMFXSEWP6P0GOh7nj8M4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
