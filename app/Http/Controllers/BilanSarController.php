<?php

namespace App\Http\Controllers;

use App\Models\BilanSar;
use App\Models\Region;
use App\Models\TypeEvenement;
use App\Models\CauseEvenement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BilanSarController extends Controller
{
    public function index()
    {
        $bilans = BilanSar::with(['typeEvenement', 'causeEvenement'])->paginate(200);
        return view('surveillance.bilan_sars.index', compact('bilans'));
    }

    public function create()
    {
        $types_evenement = TypeEvenement::all();
        $causes_evenement = CauseEvenement::all();
        $regions = Region::all();
        return view('surveillance.bilan_sars.create', compact('types_evenement', 'causes_evenement', 'regions'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'nom_du_navire' => 'nullable|string',
            'pavillon' => 'nullable|string',
            'immatriculation_callsign' => 'nullable|string',
            'armateur_proprietaire' => 'nullable|string',
            'type_du_navire' => 'nullable|string',
            'coque' => 'nullable|string',
            'propulsion' => 'nullable|string',
            'moyen_d_alerte' => 'nullable|string',
            'type_d_evenement_id' => 'nullable|exists:type_evenements,id',
            'cause_de_l_evenement_id' => 'nullable|exists:cause_evenements,id',
            'description_de_l_evenement' => 'nullable|string',
            'lieu_de_l_evenement' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id', // Modification ici
            'type_d_intervention' => 'nullable|string',
            'description_de_l_intervention' => 'nullable|string',
            'source_de_l_information' => 'nullable|string',
            'pob' => 'nullable|integer',
            'survivants' => 'nullable|integer',
            'blesses' => 'nullable|integer',
            'morts' => 'nullable|integer',
            'disparus' => 'nullable|integer',
            'evasan' => 'nullable|integer',
            'bilan_materiel' => 'nullable|string',
        ]);
    
        BilanSar::create($request->all());
    
        return redirect()->route('bilan_sars.index')->with('success', 'Bilan SAR ajouté avec succès.');
    }
                                                                                                          

    public function destroy(BilanSar $bilanSar)
    {
        $bilanSar->delete();
        return redirect()->route('bilan_sars.index');
    }
        // Autres méthodes (index, create, etc.) ...
    
    public function import(Request $request)
    {
        // Validation du fichier CSV
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Ouverture du fichier en lecture
        if (($handle = fopen($path, 'r')) === false) {
            return redirect()->back()->with('error', 'Impossible d\'ouvrir le fichier.');
        }

        // Utilisez le délimiteur approprié (ici ';' d'après vos en-têtes)
        $delimiter = ';';

        // Récupération de la première ligne (les en-têtes)
        $header = fgetcsv($handle, 0, $delimiter);
        if (!$header) {
            return redirect()->back()->with('error', 'Fichier CSV vide ou invalide.');
        }

        // Supprimer le BOM s'il existe et nettoyer les en-têtes
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        $header = array_map('trim', $header);

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            // Si la ligne a moins de colonnes que prévu, on l'ignore
            if (count($row) < count($header)) {
                continue;
            }
            // Si la ligne a plus de colonnes, on ne prend que le nombre attendu
            if (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }
            
            // Création d'un tableau associatif en combinant les en-têtes et la ligne
            $rowData = array_combine($header, $row);

            // Conversion de la date du format dd/mm/yyyy au format yyyy-mm-dd
            $dateRaw = $rowData['DATE'] ?? null;
            $date = null;
            if ($dateRaw) {
                try {
                    $date = Carbon::createFromFormat('d/m/Y', trim($dateRaw))->format('Y-m-d');
                } catch (\Exception $e) {
                    $date = null;
                }
            }

            // Extraction des autres valeurs depuis le CSV
            $nom_du_navire                = $rowData['NOM DU NAVIRE'] ?? null;
            $pavillon                     = $rowData['PAVILLON'] ?? null;
            $immatriculation_callsign     = $rowData['IMMATRICULATION/CALLSIGN'] ?? null;
            $armateur_proprietaire        = $rowData['ARMATEUR/PROPRIETAIRE'] ?? null;
            $type_du_navire               = $rowData['TYPE DU NAVIRE'] ?? null;
            $coque                        = $rowData['COQUE'] ?? null;
            $propulsion                   = $rowData['PROPULSION'] ?? null;
            $moyen_d_alerte               = $rowData['MOYEN D\'ALERTE'] ?? null;
            $typeEvenementName            = $rowData["TYPE D'EVENEMENT"] ?? null;
            $causeEvenementName           = $rowData["CAUSE DE L'EVENEMENT"] ?? null;
            $description_de_l_evenement   = $rowData["DESCRIPTION DE L'EVENEMENT"] ?? null;
            $lieu_de_l_evenement          = $rowData["LIEU DE L'EVENEMENT"] ?? null;
            $regionName                   = $rowData["REGION"] ?? null;
            $type_d_intervention          = $rowData["TYPE D'INTERVENTION"] ?? null;
            $description_de_l_intervention= $rowData["DESCRIPTION DE L'INTERVENTION"] ?? null;
            $source_de_l_information      = $rowData["SOURCE DE L'INFORMATION"] ?? null;

            // Conversion de l'encodage en UTF-8 pour les champs texte sensibles
            if ($nom_du_navire) {
                $nom_du_navire = mb_convert_encoding($nom_du_navire, 'UTF-8', 'auto');
            }
            if ($armateur_proprietaire) {
                $armateur_proprietaire = mb_convert_encoding($armateur_proprietaire, 'UTF-8', 'auto');
            }
            if ($description_de_l_evenement) {
                $description_de_l_evenement = mb_convert_encoding($description_de_l_evenement, 'UTF-8', 'auto');
            }
            if ($type_du_navire) {
                $type_du_navire = mb_convert_encoding($type_du_navire, 'UTF-8', 'auto');
            }
            if ($lieu_de_l_evenement) {
                $lieu_de_l_evenement = mb_convert_encoding($lieu_de_l_evenement, 'UTF-8', 'auto');
            }
            if ($source_de_l_information) {
                $source_de_l_information = mb_convert_encoding($source_de_l_information, 'UTF-8', 'auto');
            }
            if ($description_de_l_intervention) {
                $description_de_l_intervention = mb_convert_encoding($description_de_l_intervention, 'UTF-8', 'auto');
            }
            if ($type_d_intervention) {
                $type_d_intervention = mb_convert_encoding($type_d_intervention, 'UTF-8', 'auto');
            }
            if ($immatriculation_callsign) {
                $immatriculation_callsign = mb_convert_encoding($immatriculation_callsign, 'UTF-8', 'auto');
            }
            if ($pavillon) {
                $pavillon = mb_convert_encoding($pavillon, 'UTF-8', 'auto');
            }
            

            // Traitement des colonnes numériques pour éviter d'insérer des valeurs non numériques
            $pob = trim($rowData["POB"] ?? '');
            $pob = ($pob === '_' || $pob === '' || $pob === '-') ? null : $pob;
            
            $survivants = trim($rowData["SURVIVANTS"] ?? '');
            $survivants = ($survivants === '_' || $survivants === '' || $survivants === '-') ? null : $survivants;
            
            $blesses = trim($rowData["BLESSES"] ?? '');
            $blesses = ($blesses === '_' || $blesses === '' || $blesses === '-') ? null : $blesses;
            
            $morts = trim($rowData["MORTS"] ?? '');
            $morts = ($morts === '_' || $morts === '' || $morts === '-') ? null : $morts;
            
            $disparus = trim($rowData["DISPARUS"] ?? '');
            $disparus = ($disparus === '_' || $disparus === '' || $disparus === '-') ? null : $disparus;

            $evasan = trim($rowData["EVASAN"] ?? '');
            $evasan = ($evasan === '_' || $evasan === '' || $evasan === '-') ? null : $evasan;

            $bilan_materiel = trim($rowData["BILAN MATERIEL"] ?? '');
            $bilan_materiel = ($bilan_materiel === '_' || $bilan_materiel === '' || $bilan_materiel === '-') ? null : $bilan_materiel;
    
            // Récupération (ou création) du Type d'Événement
            $typeEvenement = null;
            if ($typeEvenementName) {
                $typeEvenement = TypeEvenement::firstOrCreate(['nom' => trim($typeEvenementName)]);
            }
    
            // Récupération (ou création) de la Cause d'Événement
            $causeEvenement = null;
            if ($causeEvenementName) {
                $causeEvenement = CauseEvenement::firstOrCreate(['nom' => trim($causeEvenementName)]);
            }
    
            // Récupération (ou création) de la Région
            $region = null;
            if ($regionName) {
                $region = Region::firstOrCreate(['nom' => trim($regionName)]);
            }
            
            // Création du Bilan SAR avec les données extraites
            BilanSar::create([
                'date'                         => $date,
                'nom_du_navire'                => $nom_du_navire,
                'pavillon'                     => $pavillon,
                'immatriculation_callsign'     => $immatriculation_callsign,
                'armateur_proprietaire'        => $armateur_proprietaire,
                'type_du_navire'               => $type_du_navire,
                'coque'                        => $coque,
                'propulsion'                   => $propulsion,
                'moyen_d_alerte'               => $moyen_d_alerte,
                'type_d_evenement_id'          => $typeEvenement ? $typeEvenement->id : null,
                'cause_de_l_evenement_id'      => $causeEvenement ? $causeEvenement->id : null,
                'description_de_l_evenement'   => $description_de_l_evenement,
                'lieu_de_l_evenement'          => $lieu_de_l_evenement,
                'region_id'                    => $region ? $region->id : null,
                'type_d_intervention'          => $type_d_intervention,
                'description_de_l_intervention'=> $description_de_l_intervention,
                'source_de_l_information'      => $source_de_l_information,
                'pob'                          => $pob,
                'survivants'                   => $survivants,
                'blesses'                      => $blesses,
                'morts'                        => $morts,
                'disparus'                     => $disparus,
                'evasan'                       => $evasan,
                'bilan_materiel'               => $bilan_materiel,
            ]);
        }
    
        fclose($handle);
    
        return redirect()->route('bilan_sars.index')->with('success', 'Fichier CSV importé avec succès.');
    }
}
