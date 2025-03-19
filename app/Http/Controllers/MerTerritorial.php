<?php

namespace App\Http\Controllers;

use App\Models\Mer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MerTerritorial extends Controller
{
    public function index()
    {
        $mers = Mer::paginate(20);
        return view('mer_territorial.index', compact('mers'));
    }

    public function destroy(Mer $mers)
    {
        $mers->delete();
        return redirect()->route('mer_territorial.index')->with('success', 'mers territorial supprimé.');
    }


    public function importCSV(Request $request)
    {
        $file = $request->file('csv_file');
        set_time_limit(1200);

        if ($file) {
            $handle = fopen($file->getPathname(), 'r');
            fgetcsv($handle, 1000, ';'); // Ignorer la ligne d'en-tête

            $mers = [];

            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                if (count($data) < 14) {
                    continue; // Ignorer les lignes incomplètes
                }

                $timeOfFix = $this->formatTimeOfFix($data[13]);

                $mers[] = [
                    'flag' => $data[0],
                    'vessel_name' => $data[1],
                    'registered_owner' => $data[2],
                    'call_sign' => $data[3],
                    'mmsi' => $data[4],
                    'imo' => $data[5],
                    'ship_type' => $data[6],
                    'destination' => $data[7],
                    'eta' => $data[8],
                    'navigation_status' => $data[9],
                    'latitude' => $data[10],
                    'longitude' => $data[11],
                    'age' => $data[12],
                    'time_of_fix' => $timeOfFix,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($mers) >= 1000) {
                    Mer::insert($peches);
                    $mers = [];
                }
            }

            if (!empty($mers)) {
                Mer::insert($mers);
            }

            fclose($handle);
        }

        return back()->with('success', 'Importation terminée.');
    }

    private function formatTimeOfFix(?string $timeOfFix): ?string
    {
        if ($timeOfFix) {
            try {
                if (strpos($timeOfFix, 'Z') !== false) {
                    return Carbon::parse($timeOfFix)->timezone(config('app.timezone'))->toDateTimeString();
                } else {
                    return Carbon::parse($timeOfFix)->timezone(config('app.timezone'))->toDateTimeString();
                }
            } catch (\Throwable $th) {
                Log::error("Erreur lors du formatage de time_of_fix : " . $th->getMessage());
                return null;
            }
        }

        return null;
    }
}
