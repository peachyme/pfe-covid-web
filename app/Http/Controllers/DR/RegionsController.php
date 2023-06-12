<?php

namespace App\Http\Controllers\DR;

use App\Models\Region;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Zone;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::orderBy('id','ASC')->paginate(8);

        // cas positifs
        $report_pos = [];
        $consultations_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->where('depistages.resultat_test', 'Positif')
            ->groupBy('region_id')
            ->get();

        $consultations_pos->each(function ($item) use (&$report_pos) {
            $report_pos[$item->region_id] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });

        // cas decedés
        $report_deces = [];
        $consultations_deces = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_deces')])
            ->where('evolution_maladie', 'D')
            ->groupBy('region_id')
            ->get();

        $consultations_deces->each(function ($item) use (&$report_deces) {
            $report_deces[$item->region_id] = [
                'cas_deces' => $item->cas_deces,
            ];
        });

        return view('DR.regions.index', [
            'regions' => $regions,
            'report_pos' => $report_pos,
            'report_deces' => $report_deces,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $region = new Region(['code_region' => $request->code_region , 'libellé_region' => $request->libelle_region]);

        $region->save();
        return redirect()->route('DR.regions.index')->with('status', 'Direction régionale ajoutée avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $region->code_region = $request->code_region;
        $region->libellé_region = $request->libelle_region;

        $region->save();

        return redirect()->route('DR.regions.index')->with('status', 'Direction régionale modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        Zone::where('region_id', $region->id)->delete();
        $region->delete();

        return redirect()->route('DR.regions.index')->with('status', 'Direction régionale supprimée avec succès !');
    }
}
