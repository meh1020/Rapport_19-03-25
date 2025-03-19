@extends('general.top')

@section('title', 'LISTES DES PASSAGES INOFENSIFS')

@section('content')


<div class="container-fluid px-4">
    <div class="top-menu">
        <button class="btn btn-success">
            <a class="text-decoration-none text-white" href="{{ route('passage_inoffensifs.create') }}">Inserer un passage ino</a>
        </button>
    </div>
    <h2 class="mb-4 text-center"> ⛵ Liste des Données de passages inoffensifs</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
            <tr>
                <th>Date d'entrée</th>
                <th>Date de sortie</th>
                <th>Navire</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($passages as $passage)
                <tr>
                    <td>{{ $passage->date_entree }}</td>
                    <td>{{ $passage->date_sortie }}</td>
                    <td>{{ $passage->navire }}</td>
                    <td class="text-center">
                        <form action="{{ route('passage_inoffensifs.destroy', $passage->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce passage ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i>
                            Supprimer
                        </button>
                        </form>
                    </td>
                </tr>
            @endforeach
    
            @if($passages->isEmpty())
                <tr>
                <td colspan="5" class="text-center">Aucun passage inoffensif enregistré.</td>
                </tr>
            @endif
            </tbody>
        </table>
     </div>
     <style>
        .table {
            border-radius: 5px; /* Arrondi des bords du tableau à 5px */
            overflow: hidden; /* Conserve l'arrondi des coins */
        }
    
        .table thead {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
    
        .table tbody {
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }
    </style>
    
@endsection