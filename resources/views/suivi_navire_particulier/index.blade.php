@extends('general.top')

@section('title', 'LISTES DES SUIVI DES NAVIRES PARTICULIERS')

@section('content')

<div class="container-fluid px-4">
    <div class="top-menu">
        <button class="btn btn-success">
            <a class="text-decoration-none text-white" href="{{ route('suivi_navire_particuliers.create')}}">Inserer nav particulier</a>
        </button>
    </div>
    <h2 class="mb-4 text-center">⛴️ Liste des Données de suivi des navires particuliers</h2>

    <div class="table-responsive">

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Nom du Navire</th>
                    <th>MMSI</th>
                    <th>Observations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suivis as $suivi)
                    <tr>
                        <td>{{ $suivi->date }}</td>
                        <td>{{ $suivi->nom_navire }}</td>
                        <td>{{ $suivi->mmsi }}</td>
                        <td>{{ $suivi->observations }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <form action="{{ route('suivi_navire_particuliers.destroy', $suivi->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce suivi ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun suivi enregistré.</td>
                    </tr>
                @endforelse
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
