@extends('layouts.app')

@section('title', 'Tableau de Bord Utilisateur')

@section('content')
<div class="container">

    <!-- Section des Statistiques Utilisateur -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Épargne Totale</h5>
                    <h3>{{ number_format($data['total_epargne'], 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Crédits en Attente</h5>
                    <h3>{{ number_format($data['total_credit_pending'], 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Crédits Remboursés</h5>
                    <h3>{{ number_format($data['total_credit_repaid'], 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des Amendes et Cotisations -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Amendes Totales</h5>
                    <h3>{{ number_format($data['total_amendes'], 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Cotisations de Solidarité</h5>
                    <h3>{{ number_format($data['total_solidarite'], 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
