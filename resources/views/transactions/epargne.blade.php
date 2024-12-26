@extends('layouts.app')

@section('title', 'Mes Épargnes')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes Épargnes</h1>
    </div>

    <!-- Affichage des messages de succès ou d'erreur -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <!-- Formulaire pour ajouter une épargne -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Ajouter une Épargne
        </div>
        <div class="card-body">
            <form action="{{ route('transactions.epargne.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="parts" class="form-label">Nombre de Parts (1 à 5)</label>
                    <select id="parts" name="parts" class="form-select" required>
                        <option value="" disabled selected>Choisissez le nombre de parts</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
        </div>
    </div>
    <!-- Tableau des épargnes -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Nombre de Parts</th>
                <th>Montant Total (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($epargnes as $epargne)
                <tr>
                    <td>{{ $epargne->date }}</td>
                    <td>{{ $epargne->parts }}</td>
                    <td>{{ number_format($epargne->amount, 0, ',', ' ') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Aucune épargne enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
