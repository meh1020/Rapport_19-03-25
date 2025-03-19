<?php
namespace App\Http\Controllers;
use App\Models\Pollution;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class PollutionController extends Controller
{
    public function index()
    {
        $pollutions = Pollution::all();
        return view('surveillance.pollutions.index', compact('pollutions'));
    }

    public function create()
    {
        return view('surveillance.pollutions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'numero' => 'required|string',
            'zone' => 'required|string',
            'coordonnees' => 'required|string',
            'type_pollution' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        $pollution = Pollution::create($request->only(['date','numero', 'zone', 'coordonnees', 'type_pollution']));
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $pollution->images()->create(['image_path' => $path]);
            }
        }
    
        return redirect()->route('pollutions.index')->with('success', 'Donnée ajoutée avec succès.');
    }
    

    public function show(Pollution $pollution)
    {
        return view('pollutions.show', compact('pollution'));
    }

    public function edit(Pollution $pollution)
    {
        return view('pollutions.edit', compact('pollution'));
    }

    public function update(Request $request, Pollution $pollution)
    {
        $request->validate([
            'date' => 'required|date',
            'numero' => 'required|string',
            'zone' => 'required|string',
            'coordonnees' => 'required|string',
            'type_pollution' => 'required|string',
            'image_satellite' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('image_satellite')) {
            $data['image_satellite'] = $request->file('image_satellite')->store('images', 'public');
        }

        $pollution->update($data);

        return redirect()->route('pollutions.index')->with('success', 'Donnée mise à jour.');
    }

    public function destroy(Pollution $pollution)
    {
        foreach ($pollution->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
    
        $pollution->delete();
        return redirect()->route('pollutions.index')->with('success', 'Donnée supprimée.');
    }
    

   
    public function exportPDF($id)
    {
        $pollution = Pollution::findOrFail($id);
        
        $pdf = Pdf::loadView('surveillance.pollutions.pdf', compact('pollution'));

        return $pdf->download("pollution_{$pollution->id}.pdf");
    }
}