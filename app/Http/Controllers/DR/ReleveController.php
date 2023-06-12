<?php

namespace App\Http\Controllers\DR;

use Carbon\Carbon;
use App\Models\Zone;
use App\Models\Region;
use App\Models\Releve;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReleveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::now()->format('d-m-Y');
        $region = Region::where('code_region', auth()->user()->region_cmt)->first();

        $releve_entrant_organique = Releve::where('region_id', $region->id)->
                                            where('date_releve',$date)->
                                            where('etat_releve','entrant')->
                                            where('couverture','O')->count();

        $releve_sortant_organique = Releve::where('region_id', $region->id)->
                                            where('date_releve',$date)->
                                            where('etat_releve','sortant')->
                                            where('couverture','O')->count();

        $releve_entrant_sousTraitant = Releve::where('region_id', $region->id)->
                                               where('date_releve',$date)->
                                               where('etat_releve','entrant')->
                                               where('couverture','ST')->count();

        $releve_sortant_sousTraitant = Releve::where('region_id', $region->id)->
                                               where('date_releve',$date)->
                                               where('etat_releve','sortant')->
                                               where('couverture','ST')->count();

        $releves = Releve::where('date_releve',$date)->orderBy('id','ASC')->paginate(4);
        $zones = Zone::where('region_id', $region->id)->orderBy('id','ASC')->get();
        $nb_zones = Zone::where('region_id', $region->id)->orderBy('id','ASC')->count();

        return view('DR.releve.index', [
            'releve_entrant_organique' => $releve_entrant_organique,
            'releve_sortant_organique' => $releve_sortant_organique,
            'releve_entrant_sousTraitant' => $releve_entrant_sousTraitant,
            'releve_sortant_sousTraitant' => $releve_sortant_sousTraitant,
            'releves' => $releves,
            'zones' => $zones,
            'today' => $today,
            'nb_zones' => $nb_zones,
            'region' => $region
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Releve  $releve
     * @return \Illuminate\Http\Response
     */
    public function show(Releve $releve)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Releve  $releve
     * @return \Illuminate\Http\Response
     */
    public function edit(Releve $releve)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Releve  $releve
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Releve $releve)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Releve  $releve
     * @return \Illuminate\Http\Response
     */
    public function destroy(Releve $releve)
    {
        //
    }
}
