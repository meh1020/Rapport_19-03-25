<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabotage;

class CabotageController extends Controller
{
     // Affiche la liste de tous les enregistrements
     public function index()
     {
         $cabotages = Cabotage::all();
         return view('surveillance.cabotage.index', compact('cabotages'));

     }
 
     // Affiche le formulaire de création
     public function create()
     {
         return view('surveillance.cabotage.create');
     }
 
     // Enregistre un nouveau enregistrement dans la base de données
     public function store(Request $request)
     {
         // Valider les données du formulaire
         $validated = $request->validate([
             'date' => 'required|date',
             'provenance' => 'required|string|max:255',
             'navires' => 'required|string|max:255',
             'equipage' => 'required|integer',
             'passagers' => 'required|integer',
         ]);
 
         // Créer le nouvel enregistrement
         Cabotage::create($validated);
 
         // Rediriger vers la liste avec un message de succès
         return redirect()->route('cabotage.index')->with('success', 'Enregistrement effectué avec succès !');
     }

     // Supprime un enregistrement spécifique
        public function destroy($id)
        {
            $cabotage = Cabotage::find($id);

            if (!$cabotage) {
                return redirect()->route('cabotage.index')->with('error', 'Enregistrement non trouvé.');
            }

            $cabotage->delete();

            return redirect()->route('cabotage.index')->with('success', 'Enregistrement supprimé avec succès.');
        }

        public function importCSV(Request $request)
{
    // Validation du fichier
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt'
    ]);

    $file = $request->file('csv_file');
    $handle = fopen($file->getRealPath(), 'r');

    if ($handle === false) {
        return redirect()->route('cabotage.index')->with('error', 'Impossible d\'ouvrir le fichier CSV.');
    }

    // Lire la première ligne pour récupérer les en-têtes (séparateur point-virgule)
    $header = fgetcsv($handle, 1000, ';');
    if (!$header) {
        return redirect()->route('cabotage.index')->with('error', 'Le fichier CSV est vide ou invalide.');
    }

    // Mise en forme des en-têtes : conversion en minuscules et suppression des espaces
    $header = array_map(function($value) {
        return strtolower(trim($value));
    }, $header);

    // Si le fichier contient une colonne "id" en première position, on l'ignore
    if (($key = array_search('id', $header)) !== false) {
        unset($header[$key]);
        $header = array_values($header); // Réindexer le tableau
    }

    // Vérification que le CSV contient bien toutes les colonnes attendues
    $expected = ['date', 'provenance', 'navires', 'equipage', 'passagers'];
    foreach ($expected as $col) {
        if (!in_array($col, $header)) {
            fclose($handle);
            return redirect()->route('cabotage.index')
                ->with('error', "Le fichier CSV doit contenir la colonne '$col'.");
        }
    }

    // Parcours de chaque ligne du CSV et insertion en base
    while (($row = fgetcsv($handle, 1000, ';')) !== false) {
        // S'assurer que la ligne a le même nombre de colonnes que l'en-tête
        if (count($row) == count($header)) {
            $data = array_combine($header, $row);
            // Créer un nouvel enregistrement avec les données importées
            Cabotage::create([
                'date'       => $data['date'],
                'provenance' => $data['provenance'],
                'navires'    => $data['navires'],
                'equipage'   => (int)$data['equipage'],
                'passagers'  => (int)$data['passagers'],
            ]);
        }
    }

    fclose($handle);

    return redirect()->route('cabotage.index')->with('success', 'CSV importé avec succès !');
}

        

}
