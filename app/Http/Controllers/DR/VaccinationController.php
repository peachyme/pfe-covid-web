<?php

namespace App\Http\Controllers\DR;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Zone;
use App\Models\Region;
use App\Models\Vaccination;
use App\Models\SousTraitant;
use Illuminate\Http\Request;
use App\Models\EmployeOrganique;
use App\Http\Controllers\Controller;

class VaccinationController extends Controller
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

        $vaccinations = Vaccination::where('date_vaccination', $date)
                                    ->where('region_id', $region->id)
                                    ->orderBy('id','ASC')->paginate(3);
        return view('dr.vaccination.index', [
            'vaccinations' => $vaccinations,
            'today' => $today,
            'date' => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = Carbon::now()->format('Y-m-d');
        $region = Region::where('code_region', auth()->user()->region_cmt)->first();
        $cmt = CMT::find(3);
        $organiques = EmployeOrganique::where('region_id', $region->id)->orderBy('id','ASC')->get();
        $sousTraitants = SousTraitant::where('region_id', $region->id)->orderBy('id','ASC')->get();

        return view('DR.vaccination.addVaccination', [
            'organiques' => $organiques,
            'sousTraitants' => $sousTraitants,
            'region' => $region,
            'cmt' => $cmt,
            'today' => $today,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cmt = 3;
        $region = Region::where('code_region', auth()->user()->region_cmt)->first()->id;
        if(EmployeOrganique::where('matricule', $request->matricule)->
        where('nom', $request->nom)->where('prenom', $request->prenom)->exists())
        {
            $employe = EmployeOrganique::where('matricule', $request->matricule)->first();

            //verifier si employé deja vacciné  (ida ye9der ydir 2 types de vaccin tsema nzid type f condition)
            if(Vaccination::where('organique_id', $employe->id)->where('dose_vaccination', $request->dose_vaccination)->exists())
            {
                if ($request->dose_vaccination == 1) {
                        return redirect()->route('DR.vaccinations.index')->with('error', 'Employé à déjà pris sa première dose !');
                    }
                    if ($request->dose_vaccination == 2) {
                        return redirect()->route('DR.vaccinations.index')->with('error', 'Employé à déjà pris sa deuxième dose !');
                    }
            }
            else
            {
                //inserer vaccination
                $vaccination = new Vaccination(['date_vaccination' => $request->date_vaccination,
                                                'dose_vaccination' => $request->dose_vaccination,
                                                'type_vaccin' => $request->type_vaccin,
                                                'observation' => $request->observation,
                                                'organique_id' => $employe->id,
                                                'cmt_id' => $cmt,
                                                'region_id' => $region,
                                                ]);

                $employe->vaccinations()->save($vaccination);

                return redirect()->route('DR.vaccinations.index')->with('status', 'Vaccination crée avec succès !');
            }

        }
        else
        {
            if(SousTraitant::where('matricule', $request->matricule)->
            where('nom', $request->nom)->where('prenom', $request->prenom)->exists())
            {
                $sousTraitant = SousTraitant::where('matricule', $request->matricule)->first();

                //verifier si employé deja vacciné  (ida ye9der ydir 2 types de vaccin tsema nzid type f condition)
                if(Vaccination::where('sousTraitant_id', $sousTraitant->id)->where('dose_vaccination', $request->dose_vaccination)->exists())
                {
                    if ($request->dose_vaccination == 1) {
                        return redirect()->route('DR.vaccinations.index')->with('error', 'Employé à déjà pris sa première dose !');
                    }
                    if ($request->dose_vaccination == 2) {
                        return redirect()->route('DR.vaccinations.index')->with('error', 'Employé à déjà pris sa deuxième dose !');
                    }
                }
                else
                {
                    //inserer vaccination
                    $vaccination = new Vaccination(['date_vaccination' => $request->date_vaccination,
                                                    'dose_vaccination' => $request->dose_vaccination,
                                                    'type_vaccin' => $request->type_vaccin,
                                                    'observation' => $request->observation,
                                                    'sousTraitant_id' => $sousTraitant->id,
                                                    'cmt_id' => $cmt,
                                                    'region_id' => $region,
                                                    ]);

                    $sousTraitant->vaccinations()->save($vaccination);

                    return redirect()->route('DR.vaccinations.index')->with('status', 'Vaccination crée avec succès !');
                }
            }
            else
            {
                return redirect()->route('DR.vaccinations.index')->with('error', 'Employé n\'existe pas !');
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function show(Vaccination $vaccination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function edit(Vaccination $vaccination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vaccination $vaccination)
    {
        $vaccination->date_vaccination = $request->date_vaccination;
        $vaccination->type_vaccin = $request->type_vaccin;
        $vaccination->dose_vaccination = $request->dose_vaccination;
        $vaccination->observation = $request->observation;
        $vaccination->save();

        return redirect()->route('DR.vaccinations.index', $vaccination)->with('status', 'Vaccination modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vaccination $vaccination)
    {
        $vaccination->delete();

        return redirect()->route('DR.vaccinations.index')->with('status', 'Vaccination supprimée avec succès !');
    }
}
