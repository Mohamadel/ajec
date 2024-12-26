@extends('layouts.app')

@section('title', 'Graphiques')

@section('content')
<div class="container">
    <h1 class="mb-4">Graphiques</h1>

    <!-- Affichage du Graphique -->
    <div class="card mb-4">
        <div class="card-body">
            <canvas id="chartCanvas" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Script pour le Graphique -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('chartCanvas').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar', // Type de graphique
                data: @json($chartData), // Données envoyées depuis le contrôleur
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true }, // Afficher la légende
                        tooltip: { enabled: true } // Activer les infobulles
                    }
                }
            });
        });
    </script>
</div>
@endsection
