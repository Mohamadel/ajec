@extends('layouts.app')

@section('title', 'Mes Cotisations de Solidarité')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mes Cotisations de Solidarité</h1>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('user.contributeSolidarite') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Contribuer</button>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Montant (FCFA)</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($solidarites as $solidarite)
                <tr>
                    <td>{{ $solidarite->date }}</td>
                    <td>{{ number_format($solidarite->amount, 0, ',', ' ') }}</td>
                    <td>{{ $solidarite->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Aucune cotisation enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
