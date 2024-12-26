@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Liste des Utilisateurs</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date d'Inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
