@extends('layouts.app')

@section('title', 'Gestion des Solidarités')

@section('content')
<div class="container">
    <h1>Gestion des Solidarités</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Utilisateur</th>
                <th>Montant</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($solidarites as $solidarite)
                <tr>
                    <td>{{ $solidarite->id }}</td>
                    <td>{{ $solidarite->user->name }}</td>
                    <td>{{ number_format($solidarite->amount, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $solidarite->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aucune solidarité enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
