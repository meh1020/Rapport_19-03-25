@extends('general.top')

@section('title', 'RAPPORTS')

@section('content')
<div class="container-fluid px-4">

    <!-- Affichage du texte récapitulatif du filtre -->
    @if(isset($filterResult))
        <div class="alert alert-info mt-3">
            <strong>{{ $filterResult }}</strong>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card my-4">
        <div class="card-header">
            <h5>Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('rapport.index') }}">
                <div class="row">
                    <!-- Filtrer par année -->
                    <div class="col-md-4">
                        <label for="filter_year">Filtrer par année</label>
                        <select name="filter_year" id="filter_year" class="form-control">
                            <option value="">Année</option>
                            @for ($y = date('Y'); $y >= 2000; $y--)
                                <option value="{{ $y }}" {{ request('filter_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filtrer par trimestre d'une année choisie -->
                    <div class="col-md-4">
                        <label>Filtrer par trimestre</label>
                        <div class="input-group">
                            <select name="filter_year_quarter" class="form-control">
                                <option value="">Année</option>
                                @for ($y = date('Y'); $y >= 2000; $y--)
                                    <option value="{{ $y }}" {{ request('filter_year_quarter') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <select name="filter_quarter" class="form-control">
                                <option value="">Trimestre</option>
                                <option value="1" {{ request('filter_quarter') == 1 ? 'selected' : '' }}>1er trimestre</option>
                                <option value="2" {{ request('filter_quarter') == 2 ? 'selected' : '' }}>2ème trimestre</option>
                                <option value="3" {{ request('filter_quarter') == 3 ? 'selected' : '' }}>3ème trimestre</option>
                                <option value="4" {{ request('filter_quarter') == 4 ? 'selected' : '' }}>4ème trimestre</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filtrer par mois d'une année choisie -->
                    <div class="col-md-4">
                        <label>Filtrer par mois</label>
                        <div class="input-group">
                            <select name="filter_year_month" class="form-control">
                                <option value="">Année</option>
                                @for ($y = date('Y'); $y >= 2000; $y--)
                                    <option value="{{ $y }}" {{ request('filter_year_month') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <select name="filter_month" class="form-control">
                                <option value="">Mois</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('filter_month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>



                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                        <a href="{{ route('rapport.index') }}" class="btn btn-secondary">Réinitialiser</a>

                        <!-- Bouton Exporter PDF -->
                        <a href="{{ route('rapport.exportPdf', request()->all()) }}" class="btn btn-danger">
                            Exporter en PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #4CAF50; color: white;">
                    <h5>Répartition des Types d'Événements</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTypes"></canvas>
                    <button class="btn btn-success mt-2" onclick="downloadChart('chartTypes', 'types_evenements.png')">Télécharger</button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Répartition des Causes d'Événements</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartCauses"></canvas>
                    <button class="btn btn-primary mt-2" onclick="downloadChart('chartCauses', 'causes_evenements.png')">Télécharger</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5>Répartition des Bilans SAR par Région</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartRegions"></canvas>
                    <button class="btn btn-warning mt-2" onclick="downloadChart('chartRegions', 'bilan_sar_regions.png')">Télécharger</button>
                </div>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5>Statistiques des Bilans SAR</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartBilanStats"></canvas>
                    <button class="btn btn-danger mt-2" onclick="downloadChart('chartBilanStats', 'bilan_sar_stats.png')">Télécharger</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>Nombre d'entrées par zone</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="chartZones"></canvas>
                    <button class="btn btn-info mt-2" onclick="downloadChart('chartZones', 'zones_count.png')">Télécharger</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5>Répartition des navires par Flag</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="chartFlags"></canvas>
                    <button class="btn btn-secondary mt-2" onclick="downloadChart('chartFlags', 'flags_distribution.png')">Télécharger</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5>Répartition des types de navires</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartShipTypes" width="400" height="300"></canvas>
                    <button class="btn btn-secondary mt-2" onclick="downloadChart('chartShipTypes', 'ship_types_distribution.png')">
                        Télécharger
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5>Graphique Cabotage</h5>
                </div>
                <div class="card-body">
                    <canvas id="cabotageChart" width="400" height="300"></canvas>
                    <button class="btn btn-secondary mt-2" onclick="downloadChart('cabotageChart', 'cabotage_graph.png')">
                        Télécharger
                    </button>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5>Répartition des navires au Port</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPorts"></canvas>
                        <button class="btn btn-secondary mt-2" onclick="downloadChart('chartPorts', 'ports_distribution.png')">Télécharger</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function downloadChart(chartId, filename) {
        const canvas = document.getElementById(chartId);
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = filename;
        link.click();
    }

    // Chart Types
    const typesLabels = @json($typesData->pluck('name'));
    const typesCounts = @json($typesData->pluck('count'));
    new Chart(document.getElementById('chartTypes').getContext('2d'), {
        type: 'bar',
        data: { 
            labels: typesLabels, 
            datasets: [{
                label: 'Nombre d\'événements', 
                backgroundColor: '#4CAF50', 
                data: typesCounts
            }] 
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true } } 
        }
    });

    // Chart Causes
    const causesLabels = @json($causesData->pluck('name'));
    const causesCounts = @json($causesData->pluck('count'));
    new Chart(document.getElementById('chartCauses').getContext('2d'), {
        type: 'bar',
        data: { 
            labels: causesLabels, 
            datasets: [{
                label: 'Nombre d\'événements', 
                backgroundColor: '#FF9800', 
                data: causesCounts
            }]
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true } } 
        }
    });

    // Chart Regions
    const regionsLabels = @json($regionsData->pluck('name'));
    const regionsCounts = @json($regionsData->pluck('count'));
    new Chart(document.getElementById('chartRegions').getContext('2d'), {
        type: 'bar',
        data: { 
            labels: regionsLabels, 
            datasets: [{
                label: 'Nombre de bilans SAR', 
                backgroundColor: '#FFC107', 
                data: regionsCounts
            }] 
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true } } 
        }
    });

    // Chart BilanStats
    const bilanLabels = ["POB", "Survivants", "Blessés", "Morts", "Disparus", "Evasan"];
    const bilanCounts = {!! json_encode(isset($bilanStats) ? [
        $bilanStats->pob_total ?? 0,
        $bilanStats->survivants_total ?? 0,
        $bilanStats->blesses_total ?? 0,
        $bilanStats->morts_total ?? 0,
        $bilanStats->disparus_total ?? 0,
        $bilanStats->evasan_total ?? 0
    ] : [0, 0, 0, 0, 0, 0]) !!};

    new Chart(document.getElementById('chartBilanStats').getContext('2d'), {
        type: 'bar',
        data: { 
            labels: bilanLabels, 
            datasets: [{
                label: 'Total des incidents', 
                backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#F44336', '#9C27B0', '#795548'], 
                data: bilanCounts
            }] 
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true } } 
        }
    });

    // Chart Zones
    const zonesLabels = @json(array_keys($zoneCounts));
    const zonesCounts = @json(array_values($zoneCounts));
    new Chart(document.getElementById('chartZones').getContext('2d'), {
        type: 'bar',
        data: {
            labels: zonesLabels,
            datasets: [{
                label: "Nombre d'entrées",
                backgroundColor: '#17a2b8',
                data: zonesCounts
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    // Chart Flags
    const flagLabels = @json($flagData->pluck('name'));
    const flagCounts = @json($flagData->pluck('count'));
    new Chart(document.getElementById('chartFlags').getContext('2d'), {
        type: 'bar',
        data: {
            labels: flagLabels,
            datasets: [{
                label: 'Nombre de navires',
                backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33A8'],
                data: flagCounts
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
    // Chart Ship Types
    const shipTypeLabels = @json($shipTypesData->pluck('name'));
    const shipTypeCounts = @json($shipTypesData->pluck('count'));

    new Chart(document.getElementById('chartShipTypes').getContext('2d'), {
        type: 'bar',
        data: {
            labels: shipTypeLabels,
            datasets: [{
                label: 'Nombre de navires',
                backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#F44336', '#9C27B0', '#795548'],
                data: shipTypeCounts
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Récupération des données depuis le contrôleur pour le graphique Cabotage
    const cabotageData = @json($cabotageData);

    // Extraire les labels (provenances)
    const cabotageLabels = cabotageData.map(item => item.provenance);
    // Nombre de navires distincts
    const naviresData    = cabotageData.map(item => item.total_navires);
    // Sommes équipage / passagers
    const equipageData   = cabotageData.map(item => item.total_equipage);
    const passagersData  = cabotageData.map(item => item.total_passagers);

    // Créer le graphique Cabotage
    new Chart(document.getElementById('cabotageChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: cabotageLabels,
            datasets: [
                {
                    label: 'Navires distincts',
                    backgroundColor: '#4CAF50',
                    data: naviresData
                },
                {
                    label: 'Équipage total',
                    backgroundColor: '#2196F3',
                    data: equipageData
                },
                {
                    label: 'Passagers total',
                    backgroundColor: '#FF9800',
                    data: passagersData
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // Fonction de téléchargement (déjà présente en début de script)
    function downloadChart(chartId, filename) {
        const canvas = document.getElementById(chartId);
        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = filename;
        link.click();
    }

     // navire etrangers aux Ports
     const portLabels = @json(array_keys($portCounts));
    const portCountsData = @json(array_values($portCounts));
    new Chart(document.getElementById('chartPorts').getContext('2d'), {
        type: 'bar',
        data: {
            labels: portLabels,
            datasets: [{
                label: "TOTAL",
                backgroundColor: '#9C27B0',
                data: portCountsData
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
