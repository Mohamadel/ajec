@extends('layouts.app')

@section('title', 'Paramètres Globaux')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Gestion des Paramètres Globaux</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.setting') }}">
        @csrf
        <div class="mb-3">
            <label for="cost_per_part" class="form-label">Coût par Part :</label>
            <input type="number" name="cost_per_part" id="cost_per_part" 
                   value="{{ $settings->where('key_name', 'cost_per_part')->first()->value ?? '' }}" 
                   class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="interest_rate" class="form-label">Taux d'Intérêt (%):</label>
            <input type="number" name="interest_rate" id="interest_rate" 
                   value="{{ $settings->where('key_name', 'interest_rate')->first()->value ?? '' }}" 
                   class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="amende_cost" class="form-label">Montant Amende :</label>
            <input type="number" name="amende_cost" id="amende_cost" 
                   value="{{ $settings->where('key_name', 'amende_cost')->first()->value ?? '' }}" 
                   class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="solidarite_cost" class="form-label">Montant Solidarité :</label>
            <input type="number" name="solidarite_cost" id="solidarite_cost" 
                   value="{{ $settings->where('key_name', 'solidarite_cost')->first()->value ?? '' }}" 
                   class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Mettre à Jour</button>
    </form>
</div>
@endsection
