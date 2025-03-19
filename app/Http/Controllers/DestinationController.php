<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{
    /**
     * Affiche la liste de toutes les destinations.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        if ($query) {
            // Recherche partielle sur le champ 'name'
            $destinations = Destination::where('name', 'LIKE', '%' . $query . '%')->paginate(10);
        } else {
            $destinations = Destination::paginate(10);
        }
    
        return view('destinationmada.index', compact('destinations'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle destination.
     */
    public function create()
    {
        return view('destinationmada.create');
    }

    /**
     * Stocke une nouvelle destination dans la base de données.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) {
                // On effectue une comparaison sensible à la casse grâce à l'opérateur BINARY.
                if (Destination::whereRaw('BINARY name = ?', [$value])->exists()) {
                    $fail('La destination existe déjà avec cette écriture exacte.');
                }
            },
        ],
    ]);

    Destination::create([
        'name' => $request->input('name'),
    ]);

    return redirect()->route('destinations.index')->with('success', 'Destination ajoutée avec succès.');
}


    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('destinations.index')
                         ->with('success', 'Destination supprimée avec succès.');
    }
}
