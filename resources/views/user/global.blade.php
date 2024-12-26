@extends('layouts.app')

@section('title', 'Vue Globale')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        Filtrer et Trier les Données
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('global.view') }}">
            <div class="row">
                <!-- Recherche -->
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un utilisateur" value="{{ $search }}">
                </div>

                <!-- Trier par -->
                <div class="col-md-4">
                    <select name="sort_field" class="form-select">
                        <option value="name" {{ $sortField === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="total_epargne" {{ $sortField === 'total_epargne' ? 'selected' : '' }}>Épargne Totale</option>
                        <option value="total_cotisation" {{ $sortField === 'total_cotisation' ? 'selected' : '' }}>Cotisation Totale</option>
                        <option value="total_credit" {{ $sortField === 'total_credit' ? 'selected' : '' }}>Crédits Restants</option>
                        <option value="total_amende" {{ $sortField === 'total_amende' ? 'selected' : '' }}>Amendes</option>
                    </select>
                </div>

                <!-- Ordre -->
                <div class="col-md-2">
                    <select name="sort_order" class="form-select">
                        <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Croissant</option>
                        <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Décroissant</option>
                    </select>
                </div>

                <!-- Bouton de Filtrage -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Appliquer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-secondary text-white">Vue Globale des Utilisateurs</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Épargne Totale (FCFA)</th>
                    <th>Cotisations (FCFA)</th>
                    <th>Crédits (FCFA)</th>
                    <th>Amendes (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->epargnes->sum('total_amount') }}</td>
                        <td>{{ $user->cotisations->sum('amount') }}</td>
                        <td>{{ $user->credits->where('status', 'Pending')->sum('amount') }}</td>
                        <td>{{ $user->amendes->sum('amount') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
