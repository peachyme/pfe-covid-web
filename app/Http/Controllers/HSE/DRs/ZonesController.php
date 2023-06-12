<?php

namespace App\Http\Controllers\HSE\DRs;

use Carbon\Carbon;
use App\Models\Zone;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class ZonesController extends Controller
{
    public function showSituationZones(Request $request)
    {
        $region = "";

        $regions = Region::all();

        if ($request->filled('region')) {
            $no_region = false;
            $region = $request->region;
            $code_region = 'de la region ' . Region::find($region)->code_region;
            $zones = Zone::where('region_id', $region)
                        ->orderBy('id', 'ASC')
                        ->get();
        }
        else{
            $no_region = true;
            $zones = [];
            $code_region = "";
        }
        return view('HSE.DRs.zones.situationZones', [
                'zones' => $zones,
                'regions' => $regions,
                'no_region' => $no_region,
                'code_region' => $code_region,
        ]);
    }

    public function generateReporting(Request $request)
    {
        $date = Carbon::now()->format('d-m-Y');
        $regions = Region::whereIn('id', ['2','3','4','5','6','7','8','9','10','11'])->get();

        $pdf = PDF::loadView('HSE.DRs.zones.pdf.reportingZonesSitesDP', [
            'regions' => $regions,
            'date' => $date,

        ])->setPaper('a4', 'landscape');

        return $pdf->download('Reporting de la situation actuelle des zones de confinement des sites DP.pdf');
    }
}
