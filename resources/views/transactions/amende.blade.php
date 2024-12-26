@extends('layouts.app')

@section('title', 'Mes Amendes')

@section('content')
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes Amendes</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h3>Historique des Amendes</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Montant</th>
                <th>Raison</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($amendes as $amende)
                <tr>
                    <td>{{ $amende->date ?? 'Non défini' }}</td>
                    <td>{{ number_format($amende->amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $amende->reason ?? 'Aucune raison' }}</td>
                    <td>{{ ucfirst($amende->status) }}</td>
                    <td>
                        @if ($amende->status === 'unpaid')
                            <form action="{{ route('transactions.amende.pay', $amende->id) }}" method="POST" onsubmit="return confirm('Confirmer le paiement de cette amende ?');">
                                @csrf
                                <button type="submit" class="btn btn-primary">Payer</button>
                            </form>
                        @else
                            <span class="text-success">Payée</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucune amende disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
