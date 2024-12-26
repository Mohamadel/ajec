@extends('layouts.app')

@section('title', 'Tableau de Bord Administrateur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Contenu Principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Tableau de Bord Administrateur</h2>

            <!-- Statistiques Globales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Utilisateurs</h5>
                            <h3>{{ $stats['total_users'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Total Épargne</h5>
                            <h3>{{ number_format($stats['total_epargne'], 0, ',', ' ') }} FCFA</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5>Crédits En Attente</h5>
                            <h3>{{ number_format($stats['credits_pending'], 0, ',', ' ') }} FCFA</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5>Amendes Non Payées</h5>
                            <h3>{{ number_format($stats['amendes_unpaid'], 0, ',', ' ') }} FCFA</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestion des Utilisateurs et Paramètres -->
            @yield('admin-content')

        </main>
    </div>
</div>
@endsection
