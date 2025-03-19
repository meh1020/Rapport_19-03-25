@extends('general.top')

@section('title', 'LISTES AVURNAV')

@section('content')


<div class="container-fluid px-4">
    <div class="top-menu">
        <button class="btn btn-success">
            <a class="text-decoration-none text-white" href="{{ route('avurnav.create') }}">Créer AVURNAV</a>
        </button>
    </div>
    <h2 class="mb-4 text-center">🚢 Liste des Données AVURNAV</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>AVURNAV Local</th>
                    <th>Île</th>
                    <th>Vous signale</th>
                    <th>Position</th>
                    <th>Navire</th>
                    <th>POB</th>
                    <th>Type</th>
                    <th>Caractéristiques</th>
                    <th>Zone</th>
                    <th>Dernière Communication</th>
                    <th>Contacts</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($avurnavs as $avurnav)
                    <tr>
                        <td><small>{{ $avurnav->id }}</small></td>
                        <td><small>{{ $avurnav->date }}</small></td>
                        <td><small>{{ $avurnav->avurnav_local }}</small></td>
                        <td><small>{{ $avurnav->ile }}</small></td>
                        <td><small>{{ $avurnav->vous_signale }}</small></td>
                        <td><small>{{ $avurnav->position }}</small></td>
                        <td><small>{{ $avurnav->navire }}</small></td>
                        <td><small>{{ $avurnav->pob }}</small></td>
                        <td><small>{{ $avurnav->type }}</small></td>
                        <td><small>{{ $avurnav->caracteristiques }}</small></td>
                        <td><small>{{ $avurnav->zone }}</small></td>
                        <td><small>{{ $avurnav->derniere_communication ?? 'Non disponible' }}</small></td>
                        <td><small>{{ $avurnav->contacts }}</small></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('export.pdf', $avurnav->id) }}" class="btn btn-secondary btn-sm">Exporter</a>
                                <!-- <a href="{{ route('export.pdf', $avurnav->id) }}" class="btn btn-success btn-sm">Voir détail</a> -->
                                <form action="" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet élément ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center text-muted">Aucune donnée enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>
    .pagination {
        flex-wrap: wrap; /* Empêche le débordement */
        justify-content: center; /* Centre la pagination */
    }
    .table {
        border-radius: 5px; /* Arrondi des bords du tableau */
        overflow: hidden; /* Assure que les coins restent arrondis */
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
