@extends('layouts.app')

@section('title', 'Mes Crédits')

@section('content')
<div class="container">    
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

    
    <h3>Demander un Nouveau Crédit</h3>
    @if ($unpaidCredits)
    <div class="alert alert-warning">
        Vous avez des crédits en cours. Vous devez rembourser tous vos crédits avant de pouvoir en demander un nouveau.
    </div>
    @else
        <form action="{{ route('transactions.credit.create') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">Montant du Crédit :</label>
                <input type="number" name="amount" id="amount" class="form-control" required>
            </div>
            <p>Vous pouvez emprunter jusqu'à : {{ number_format($maxCreditAmount, 0, ',', ' ') }} FCFA</p>
            <button type="submit" class="btn btn-primary">Demander Crédit</button>
        </form>
    @endif

    <hr>

    <!-- Liste des crédits -->
    <h3>Mes Crédits en Cours</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Montant Approuvé</th>
                <th>Taux d'Intérêt</th>
                <th>Montant Payé</th>
                <th>Montant Restant</th>
                <th>Date d'Échéance</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($credits as $credit)
                <tr>
                    <!-- Montant Total Approuvé -->
                    <td>{{ number_format($credit->approved_amount, 0, ',', ' ') }} FCFA</td>

                    <!-- Taux d'Intérêt -->
                    <td>{{ $credit->interest_rate }}%</td>

                    <!-- Montant Total Payé -->
                    <td>{{ number_format($credit->amount_paid, 0, ',', ' ') }} FCFA</td>

                    <!-- Montant Restant -->
                    <td>{{ number_format($credit->approved_amount - $credit->amount_paid, 0, ',', ' ') }} FCFA</td>

                    <!-- Date d'Échéance -->
                    <td>{{ $credit->date_due ? \Carbon\Carbon::parse($credit->date_due)->format('d/m/Y') : 'N/A' }}</td>

                    <!-- Statut -->
                    <td>
                        @if ($credit->payment_status === 'Paid')
                            <span class="badge bg-success">Payé</span>
                        @elseif ($credit->status === 'Approved')
                            <span class="badge bg-warning">En cours</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($credit->status) }}</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td>
                        @if ($credit->status === 'Approved' && $credit->payment_status !== 'Paid')
                            <!-- Formulaire de Paiement pour les Tranches -->
                            <form action="{{ route('credit.pay', $credit->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="number" name="amount" class="form-control" placeholder="Montant à payer"
                                           min="1" max="{{ $credit->approved_amount - $credit->amount_paid }}" required>
                                    <button type="submit" class="btn btn-primary btn-sm">Payer</button>
                                </div>
                            </form>
                        @elseif ($credit->payment_status === 'Paid')
                            <span class="badge bg-success">Crédit Payé</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Aucun crédit disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
