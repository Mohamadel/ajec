@extends('layouts.app')

@section('title', 'Gestion des Amendes')

@section('content')
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
<div class="card">
    <div class="card-header bg-danger text-white">Ajouter une Amende</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.createAmende') }}">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">Utilisateur :</label>
                <select name="user_id" id="user_id" class="form-select">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label for="reason" class="form-label">Raison :</label>
                <input type="text" name="reason" id="reason" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-danger">Ajouter</button>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-secondary text-white">Liste des Amendes</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Montant (FCFA)</th>
                    <th>Raison</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($amendes as $amende)
                <tr>
                    <td>{{ $amende->id }}</td>
                    <td>{{ $amende->user->name }}</td>
                    <td>{{ $amende->amount }}</td>
                    <td>{{ $amende->reason }}</td>
                    <td>{{ $amende->date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
