@extends('general.top')

@section('title', 'LISTES MADA')

@section('content')


<div class="container-fluid px-4">
    <div class="top-menu">
        <button class="btn btn-secondary">
            <a class="text-decoration-none text-white" href="{{ route('avurnav.index') }}">Liste AVURNAV</a>
        </button>
    </div>
    <h2 class="mb-4 text-center">📜 Créer un donné AVURNAV</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ url('/avurnav/store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
              <label for="date" class="form-label">DATE :</label>
              <input type="date" name="date" id="date" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="avurnav_local" class="form-label">AVURNAV LOCAL MADAGASCAR :</label>
              <input type="text" name="avurnav_local" id="avurnav_local" class="form-control">
            </div>
          </div>
        
          <!-- Deuxième ligne : ILE DE MADAGASCAR et VOUS SIGNALE -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="ile" class="form-label">ILE DE MADAGASCAR :</label>
              <input type="text" name="ile" id="ile" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="vous_signale" class="form-label">VOUS SIGNALE :</label>
              <input type="text" name="vous_signale" id="vous_signale" class="form-control">
            </div>
          </div>
        
          <!-- Troisième ligne : 1- Position et 2- Navire -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="position" class="form-label">1- Position :</label>
              <input type="text" name="position" id="position" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="navire" class="form-label">2- Navire :</label>
              <input type="text" name="navire" id="navire" class="form-control">
            </div>
          </div>
        
          <!-- Quatrième ligne : 3- POB et 4- Type -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="pob" class="form-label">3- POB :</label>
              <input type="number" name="pob" id="pob" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="type" class="form-label">4- Type :</label>
              <input type="text" name="type" id="type" class="form-control">
            </div>
          </div>
        
          <!-- Cinquième ligne : 5- Caractéristiques et 6- Zone -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="caracteristiques" class="form-label">5- Caractéristiques :</label>
              <input type="text" name="caracteristiques" id="caracteristiques" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="zone" class="form-label">6- Zone :</label>
              <input type="text" name="zone" id="zone" class="form-control">
            </div>
          </div>
        
          <!-- Sixième ligne : 7- Dernière Communication et 8- Contacts -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="derniere_communication" class="form-label">7- Dernière Communication :</label>
              <input type="date" name="derniere_communication" id="derniere_communication" class="form-control">
            </div>
            <div class="col-md-6">
              <label for="contacts" class="form-label">8- Contacts :</label>
              <input type="text" name="contacts" id="contacts" class="form-control">
            </div>
          </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>

</div>
@endsection