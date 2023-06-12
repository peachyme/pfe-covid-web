<?php

namespace App\Http\Controllers\CMT;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Region;
use App\Models\Depistage;
use App\Models\Consultation;
use App\Models\SousTraitant;
use Illuminate\Http\Request;
use App\Models\EmployeOrganique;
use App\Http\Controllers\Controller;

class ConsultationsController extends Controller
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
        $cmt = CMT::where('code_cmt', auth()->user()->region_cmt)->first();

        $consultations = Consultation::where('date_consultation', $date)
                                    ->where('cmt_id', $cmt->id)
                                    ->orderBy('date_consultation','ASC')->paginate(3);

        return view('cmt.covid.index', [
            'consultations' => $consultations,
            'today' => $today,
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
        $region = Region::find(1);
        $cmt = CMT::where('code_cmt', auth()->user()->region_cmt)->first();;
        $organiques = EmployeOrganique::where('region_id', $region->id)->orderBy('id','ASC')->get();
        $sousTraitants = SousTraitant::where('region_id', $region->id)->orderBy('id','ASC')->get();

        return view('CMT.covid.addConsultation', [
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
        $cmt = CMT::where('code_cmt', auth()->user()->region_cmt)->first()->id;
        $region = 1;
        if(EmployeOrganique::where('matricule', $request->matricule)->
        where('nom', $request->nom)->where('prenom', $request->prenom)->exists())
        {
            $employe = EmployeOrganique::where('matricule', $request->matricule)->first();

            //inserer depistage
            $depistage = new Depistage(['date_test' => $request->date_consultation,
                                    'type_test' => $request->type_test,
                                    'resultat_test' => $request->resultat_test,
                                    'couverture' => 'O',
                                    'cmt_id' => $cmt]);

            $depistage->save();

            //inserer consultation
            $consultation = new Consultation(['date_consultation' => $request->date_consultation,
                                            'symptomes' => $request->symptomes,
                                            'maladies_chroniques' => $request->maladies_chroniques,
                                            'modalités_priseEnCharge' => $request->modalite_priseEnCharge,
                                            'periode_confinement' => $request->periode_confinement,
                                            'evolution_maladie' => $request->evolution_maladie,
                                            'observation' => $request->observation,
                                            'organique_id' => $employe->id,
                                            'depistage_id' => $depistage->id,
                                            'cmt_id' => $cmt,
                                            'region_id' => $region,
                                        ]);

            $employe->consultations()->save($consultation);

            return redirect()->route('CMT.consultations.index')->with('status', 'Consultation crée avec succès !');
        }
        else
        {
            if(SousTraitant::where('matricule', $request->matricule)->
            where('nom', $request->nom)->where('prenom', $request->prenom)->exists())
            {
                $sousTraitant = SousTraitant::where('matricule', $request->matricule)->first();

                //inserer depistage
                $depistage = new Depistage(['date_test' => $request->date_consultation,
                                        'type_test' => $request->type_test,
                                        'resultat_test' => $request->resultat_test,
                                        'couverture' => 'ST',
                                        'cmt_id' => $cmt]);

                $depistage->save();

                //inserer consultation
                $consultation = new Consultation(['date_consultation' => $request->date_consultation,
                                                'symptomes' => $request->symptomes,
                                                'maladies_chroniques' => $request->maladies_chroniques,
                                                'modalités_priseEnCharge' => $request->modalite_priseEnCharge,
                                                'periode_confinement' => $request->periode_confinement,
                                                'evolution_maladie' => $request->evolution_maladie,
                                                'observation' => $request->observation,
                                                'sousTraitant_id' => $sousTraitant->id,
                                                'depistage_id' => $depistage->id,
                                                'cmt_id' => $cmt,
                                                'region_id' => $region,
                                            ]);

                $sousTraitant->consultations()->save($consultation);

                return redirect()->route('CMT.consultations.index')->with('status', 'Consultation crée avec succès !');
            }
            else
            {
                return redirect()->route('CMT.consultations.index')->with('error', 'Employé n\'existe pas !');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function show(Consultation $consultation)
    {
        $today = Carbon::now()->format('Y-m-d');
        $age_organique = 0;
        $age_sousTraitant = 0;

        if (!empty($consultation->organique_id))
        {
            $age_organique = Carbon::parse($consultation->employeOrganique->date_naissance)->age;
        }
        if (!empty($consultation->sousTraitant_id)) {
            $age_sousTraitant = Carbon::parse($consultation->sousTraitant->date_naissance)->age;
        }

        $region = Region::find(1);
        $cmt = CMT::where('code_cmt', auth()->user()->region_cmt)->first();
        $organiques = EmployeOrganique::where('region_id', $region->id)->orderBy('id','ASC')->get();
        $sousTraitants = SousTraitant::where('region_id', $region->id)->orderBy('id','ASC')->get();

        return view('CMT.covid.consultation', [
            'consultation' => $consultation,
            'age_organique' => $age_organique,
            'age_sousTraitant' => $age_sousTraitant,
            'organiques' => $organiques,
            'sousTraitants' => $sousTraitants,
            'region' => $region,
            'cmt' => $cmt,
            'today' => $today,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function edit(Consultation $consultation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consultation $consultation)
    {
        if(!empty($consultation->organique_id))
        {
            $employe = EmployeOrganique::where('matricule', $consultation->employeOrganique->matricule)->first();

            //modifier le depistage
            $depistage = Depistage::find($consultation->depistage_id);
            $depistage->type_test = $request->type_test;
            $depistage->resultat_test = $request->resultat_test;
            $depistage->save();

            //modifier la consultation
            $consultation->date_consultation = $request->date_consultation;
            $consultation->symptomes = $request->symptomes;
            $consultation->maladies_chroniques = $request->maladies_chroniques;
            $consultation->modalités_priseEnCharge = $request->modalite_priseEnCharge;
            $consultation->periode_confinement = $request->periode_confinement;
            $consultation->evolution_maladie = $request->evolution_maladie;
            $consultation->observation = $request->observation;
            $consultation->save();


        }
        if(!empty($consultation->sousTraitant_id))
        {
            //modifier le depistage
            $depistage = Depistage::find($consultation->depistage_id);
            $depistage->type_test = $request->type_test;
            $depistage->resultat_test = $request->resultat_test;
            $depistage->save();

            //modifier la consultation
            $consultation->date_consultation = $request->date_consultation;
            $consultation->symptomes = $request->symptomes;
            $consultation->maladies_chroniques = $request->maladies_chroniques;
            $consultation->modalités_priseEnCharge = $request->modalite_priseEnCharge;
            $consultation->periode_confinement = $request->periode_confinement;
            $consultation->evolution_maladie = $request->evolution_maladie;
            $consultation->observation = $request->observation;
            $consultation->save();
        }
        return redirect()->route('CMT.consultations.show', $consultation)->with('status', 'Consultation modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consultation $consultation)
    {
        $depistage = Depistage::find($consultation->depistage_id);
        $consultation->delete();
        $depistage->delete();

        return redirect()->route('CMT.consultations.index')->with('status', 'Consultation supprimée avec succès');
    }
}
