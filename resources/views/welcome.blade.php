<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Épargne et Crédit</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #0062cc;
            color: white;
            padding: 15px 0;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
        .hero {
            background-color: #f8f9fa;
            padding: 100px 15px;
            text-align: center;
        }
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .features {
            padding: 60px 15px;
        }
        .features .feature-card {
            transition: transform 0.3s;
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .features .feature-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .cta {
            background-color: #0062cc;
            color: white;
            padding: 50px 15px;
            text-align: center;
        }
        .cta a {
            color: white;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h3 m-0">Épargne et Crédit</h1>
            <nav>
                <ul class="nav">
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Tableau de Bord Admin</a></li>
                        @else
                            <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link text-white">Mon Tableau de Bord</a></li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link text-white"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link text-white">Connexion</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="nav-link text-white">Inscription</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Bienvenue sur Épargne et Crédit</h1>
        <p>Votre solution de gestion financière pour l’épargne, les crédits, et la solidarité.</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg mt-4">Rejoignez-nous maintenant</a>
        @endguest
    </div>

    <!-- Features Section -->
    <div class="features">
        <div class="container">
            <h2 class="text-center mb-5">Nos Fonctionnalités</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="feature-card">
                        <h5>Épargnez chaque semaine</h5>
                        <p>Participez facilement à l'épargne collective et gérez vos contributions.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <h5>Crédits rapides</h5>
                        <p>Obtenez un crédit jusqu'à 3 fois le montant de votre épargne.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <h5>Cotisations de solidarité</h5>
                        <p>Contribuez à soutenir les membres en difficulté financière.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <h5>Gestion des amendes</h5>
                        <p>Des pénalités claires pour encourager la discipline financière.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call-to-Action Section -->
    <div class="cta">
        <div class="container">
            <h2>Prêt à commencer ?</h2>
            <p>Inscrivez-vous dès aujourd'hui et découvrez comment nous simplifions la gestion financière.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Rejoignez-nous</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Épargne et Crédit. Tous droits réservés.</p>
            <ul class="nav justify-content-center">
                <li class="nav-item"><a href="/about" class="nav-link text-white">À propos</a></li>
                <li class="nav-item"><a href="/contact" class="nav-link text-white">Contact</a></li>
            </ul>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
