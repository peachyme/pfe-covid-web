<?php

namespace App\Http\Controllers\DR;

use App\Http\Controllers\Controller;
use App\Models\EmployeOrganique;
use App\Models\Region;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $region = Region::where('code_region', auth()->user()->region_cmt)->first();
        $regions = Region::orderBy('id','ASC')->get();
        // dd(get_class($region));
        $zones = Zone::where('region_id', $region->id)->orderBy('id','ASC')->paginate(5);
        $zones_all = Zone::orderBy('id','ASC')->paginate(5);
        $employes = EmployeOrganique::where('region_id', $region->id)->orderBy('id','ASC')->get();

        return view('DR.zones.index', [
            'regions' => $regions,
            'zones' => $zones,
            'zones_all' => $zones_all,
            'employes' => $employes,
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
        $request->validate(
            [
                'capacite_zone' => 'numeric',
                'medecins_zone' => 'numeric',
                'infermiers_zone' => 'numeric',
                // 'code_zone' => 'unique:zones'
            ]
            );

        $zone = new Zone(['code_zone' => $request->code_zone , 'libelle_zone' => $request->libelle_zone ,
                              'capacité_zone' => $request->capacite_zone ,
                              'nb_places' => $request->capacite_zone,
                              'effectif_medecins' => $request->medecins_zone ,
                              'effectif_infermiers' => $request->infermiers_zone ,
                              'responsable_zone' => $request->responsable_zone]);

        $region = Region::where('id', $request->region_zone)->first();
        $region->zones()->save($zone);
        return redirect()->route('DR.zones.index')->with('status', 'Zone ajoutée avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zone)
    {
        $zone->code_zone = $request->code_zone;
        $zone->libelle_zone = $request->libelle_zone;
        $zone->capacité_zone = $request->capacite_zone;
        $zone->effectif_medecins = $request->medecins_zone;
        $zone->effectif_infermiers = $request->infermiers_zone;
        $zone->responsable_zone = $request->responsable_zone;

        $zone->save();

        return redirect()->route('DR.zones.index')->with('status', 'Zone modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();

        return redirect()->route('DR.zones.index')->with('status', 'Zone supprimée avec succès !');
    }
}
