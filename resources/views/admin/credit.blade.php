@extends('layouts.app')

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

    <h3>Gérer les Crédits</h3>

    <!-- Crédits en attente -->
    <h4>Crédits en Attente</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Montant Demandé</th>
                <th>Taux d'Intérêt</th>
                <th>Statut</th>
                <th>Date de Demande</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($credits->where('status', 'Pending') as $credit)
                <tr>
                    <td>{{ $credit->user->name }}</td>
                    <td>{{ number_format($credit->requested_amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $credit->interest_rate }}%</td>
                    <td>{{ ucfirst($credit->status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($credit->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <!-- Formulaire pour approuver -->
                        <form action="{{ route('admin.credit.manage', $credit->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                        </form>

                        <!-- Formulaire pour rejeter -->
                        <form action="{{ route('admin.credit.manage', $credit->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-danger btn-sm">Rejeter</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun crédit en attente.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Crédits approuvés -->
    <h4>Crédits Approuvés</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Montant Approuvé</th>
                <th>Taux d'Intérêt</th>
                <th>Date d'Approbation</th>
                <th>Statut</th>
                <th>Statut Paiement</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($credits->where('status', 'Approved') as $credit)
                <tr>
                    <td>{{ $credit->user->name }}</td>
                    <td>{{ number_format($credit->approved_amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $credit->interest_rate }}%</td>
                    <td>{{ $credit->approved_date ? $credit->approved_date->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        <span class="badge bg-success">{{ ucfirst($credit->status) }}</span>
                    </td>
                    <td>
                        @if ($credit->payment_status === 'Paid')
                            <span class="badge bg-success">Payé</span>
                        @elseif ($credit->payment_status === 'Partial')
                            <span class="badge bg-info">Partiellement Payé</span>
                        @else
                            <span class="badge bg-warning">Non Payé</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun crédit approuvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Crédits rejetés -->
    <h4>Crédits Rejetés</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Montant Demandé</th>
                <th>Taux d'Intérêt</th>
                <th>Date de Rejet</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($credits->where('status', 'Rejected') as $credit)
                <tr>
                    <td>{{ $credit->user->name }}</td>
                    <td>{{ number_format($credit->requested_amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $credit->interest_rate }}%</td>
                    <td>{{ $credit->rejected_date ? $credit->rejected_date->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        <span class="badge bg-danger">{{ ucfirst($credit->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun crédit rejeté.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
