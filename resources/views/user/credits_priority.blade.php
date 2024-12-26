@extends('layouts.app')

@section('title', 'Priorités des Crédits')

@section('content')
<div class="container">
    <div class="alert alert-info text-center mb-4">
        <h2>Priorités des Crédits</h2>
        <p>
            Cette liste affiche les utilisateurs et leur statut de crédit, classés par priorité pour l'approbation. 
            Les utilisateurs ayant des crédits en attente apparaissent en haut.
        </p>
    </div>

    <table class="table">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Crédits Totaux Acquis (FCFA)</th>
            <th>Montant Remboursé (FCFA)</th>
            <th>Montant Restant (FCFA)</th>
            <th>Crédits en Attente</th>
            <th>État de la Demande</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalEpargne = App\Models\Epargne::sum('amount'); // Total des épargnes
            $totalCreditsUsed = App\Models\Credit::where('payment_status', '!=', 'Paid')->sum('approved_amount') - App\Models\Credit::sum('amount_paid');
            $availableBalance = $totalEpargne - $totalCreditsUsed; // Solde disponible
        @endphp
        @foreach ($usersCredits as $user)
            <tr>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['email'] }}</td>
                <td>{{ number_format($user['total_credits'], 0, ',', ' ') }}</td>
                <td>{{ number_format($user['total_paid'], 0, ',', ' ') }}</td>
                <td>{{ number_format($user['remaining'], 0, ',', ' ') }}</td>
                <td>
                    @if ($user['pending_credits'] > 0)
                        <span class="badge bg-warning">{{ $user['pending_credits'] }} en attente</span>
                    @else
                        <span class="badge bg-success">Aucun</span>
                    @endif
                </td>
                <td>
                    @if ($user['remaining'] > $availableBalance)
                        <span class="badge bg-danger">Bloqué (Caisse insuffisante)</span>
                    @else
                        <span class="badge bg-primary">Aucun</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>
@endsection
