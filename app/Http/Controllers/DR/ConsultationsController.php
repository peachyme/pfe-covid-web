<?php

namespace App\Http\Controllers\DR;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Zone;
use App\Models\Region;
use App\Models\Depistage;
use App\Models\Consultation;
use App\Models\SousTraitant;
use Illuminate\Http\Request;
use App\Models\EmployeOrganique;
use App\Http\Controllers\Controller;
use App\Models\Releve;

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
        $region = Region::where('code_region', auth()->user()->region_cmt)->first();

        $consultations = Consultation::where('date_consultation', $date)
            ->where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->paginate(4);
        return view('dr.covid.index', [
            'consultations' => $consultations,
            'today' => $today,
        ]);
    }

    public function findEmploye(Request $request)
    {
        if (EmployeOrganique::where('matricule', $request->matricule)->exists()) {
            $data = EmployeOrganique::where('matricule', $request->matricule)->first();
            return response()->json($data);
        } else {
            if (SousTraitant::where('matricule', $request->matricule)->exists()) {
                $data = SousTraitant::where('matricule', $request->matricule)->first();
                return response()->json($data);
            }
        }
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
        $organiques = EmployeOrganique::where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->get();
        $sousTraitants = SousTraitant::where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->get();
        $zones = Zone::where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->get();

        return view('DR.covid.addConsultation', [
            'organiques' => $organiques,
            'sousTraitants' => $sousTraitants,
            'region' => $region,
            'cmt' => $cmt,
            'zones' => $zones,
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
        if (
            EmployeOrganique::where('matricule', $request->matricule)
                ->where('nom', $request->nom)
                ->where('prenom', $request->prenom)
                ->exists()
        ) {
            $employe = EmployeOrganique::where('matricule', $request->matricule)->first();

            //inserer releve
            if (
                Releve::where('organique_id', $employe->id)
                    ->where('etat_releve', 'entrant')
                    ->where('date_releve', '>', Carbon::parse($request->date_consultation)->subDays(30))
                    ->exists()
            ) {
                $releve_prec = Releve::where('organique_id', $employe->id)
                    ->where('etat_releve', 'entrant')
                    ->where('date_releve', '>', Carbon::parse($request->date_consultation)->subDays(30))
                    ->first();
                $zone = Zone::find($releve_prec->zone_id);
                if ($request->filled('evolution_maladie')) {
                    if ($request->evolution_maladie == 'G' || $request->evolution_maladie == 'D') {
                        $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'sortant', 'couverture' => 'O', 'zone_id' => $releve_prec->zone_id, 'region_id' => $region, 'organique_id' => $employe->id]);
                        $employe->releves()->save($releve);

                        //augmenter la capcité de la zone
                        $zone->nb_places = $zone->nb_places + 1;
                        $zone->save();
                    }
                }
            } else {
                if ($request->filled('zone') && !$request->filled('evolution_maladie')) {
                    //verifier la disponibilté des places dans la zone
                    $zone = Zone::find($request->zone);
                    if ($zone->nb_places > 0) {
                        $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'entrant', 'couverture' => 'O', 'zone_id' => $request->zone, 'region_id' => $region, 'organique_id' => $employe->id]);

                        $employe->releves()->save($releve);

                        //diminuer la capcité de la zone
                        $zone->nb_places = $zone->nb_places - 1;
                        $zone->save();
                    } else {
                        return redirect()
                            ->route('DR.consultations.index')
                            ->with('error', 'Pas de places disponibles dans cette zone de confinement !');
                    }
                }
            }
            //inserer depistage
            $depistage = new Depistage(['date_test' => $request->date_consultation, 'type_test' => $request->type_test, 'resultat_test' => $request->resultat_test, 'couverture' => 'O', 'cmt_id' => $cmt]);

            $depistage->save();

            //inserer consultation
            $consultation = new Consultation(['date_consultation' => $request->date_consultation, 'symptomes' => $request->symptomes, 'maladies_chroniques' => $request->maladies_chroniques, 'modalités_priseEnCharge' => $request->modalite_priseEnCharge, 'periode_confinement' => $request->periode_confinement, 'evolution_maladie' => $request->evolution_maladie, 'observation' => $request->observation, 'organique_id' => $employe->id, 'depistage_id' => $depistage->id, 'cmt_id' => $cmt, 'region_id' => $region, 'zone_id' => $request->zone]);

            $employe->consultations()->save($consultation);

            return redirect()
                ->route('DR.consultations.index')
                ->with('status', 'Consultation crée avec succès !');
        } else {
            if (
                SousTraitant::where('matricule', $request->matricule)
                    ->where('nom', $request->nom)
                    ->where('prenom', $request->prenom)
                    ->exists()
            ) {
                $sousTraitant = SousTraitant::where('matricule', $request->matricule)->first();

                //inserer releve
                if (
                    Releve::where('sousTraitant_id', $sousTraitant->id)
                        ->where('etat_releve', 'entrant')
                        ->where('date_releve', '>', Carbon::parse($request->date_consultation)->subDays(30))
                        ->exists()
                ) {
                    $releve_prec = Releve::where('sousTraitant_id', $sousTraitant->id)
                        ->where('etat_releve', 'entrant')
                        ->where('date_releve', '>', Carbon::parse($request->date_consultation)->subDays(30))
                        ->first();
                    $zone = Zone::find($releve_prec->zone_id);
                    if ($request->filled('evolution_maladie')) {
                        if ($request->evolution_maladie == 'G' || $request->evolution_maladie == 'D') {
                            $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'sortant', 'couverture' => 'ST', 'zone_id' => $releve_prec->zone_id, 'region_id' => $region, 'sousTraitant_id' => $sousTraitant->id]);
                            $sousTraitant->releves()->save($releve);

                            //augmenter la capcité de la zone
                            $zone->nb_places = $zone->nb_places + 1;
                            $zone->save();
                        }
                    }
                } else {
                    if ($request->filled('zone') && !$request->filled('evolution_maladie')) {
                        //verifier la disponibilté des places dans la zone
                        $zone = Zone::find($request->zone);
                        if ($zone->nb_places > 0) {
                            $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'entrant', 'couverture' => 'ST', 'zone_id' => $request->zone, 'region_id' => $region, 'sousTraitant_id' => $sousTraitant->id]);

                            $sousTraitant->releves()->save($releve);

                            //diminuer la capcité de la zone
                            $zone->nb_places = $zone->nb_places - 1;
                            $zone->save();
                        } else {
                            return redirect()
                                ->route('DR.consultations.index')
                                ->with('error', 'Pas de places disponibles dans cette zone de confinement !');
                        }
                    }
                }

                //inserer depistage
                $depistage = new Depistage(['date_test' => $request->date_consultation, 'type_test' => $request->type_test, 'resultat_test' => $request->resultat_test, 'couverture' => 'ST', 'cmt_id' => $cmt]);

                $depistage->save();

                //inserer consultation
                $consultation = new Consultation(['date_consultation' => $request->date_consultation, 'symptomes' => $request->symptomes, 'maladies_chroniques' => $request->maladies_chroniques, 'modalités_priseEnCharge' => $request->modalite_priseEnCharge, 'periode_confinement' => $request->periode_confinement, 'evolution_maladie' => $request->evolution_maladie, 'observation' => $request->observation, 'sousTraitant_id' => $sousTraitant->id, 'depistage_id' => $depistage->id, 'cmt_id' => $cmt, 'region_id' => $region, 'zone_id' => $request->zone]);

                $sousTraitant->consultations()->save($consultation);

                return redirect()
                    ->route('DR.consultations.index')
                    ->with('status', 'Consultation crée avec succès !');
            } else {
                return redirect()
                    ->route('DR.consultations.index')
                    ->with('error', 'Employé n\'existe pas !');
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

        if (!empty($consultation->organique_id)) {
            $age_organique = Carbon::parse($consultation->employeOrganique->date_naissance)->age;
        }
        if (!empty($consultation->sousTraitant_id)) {
            $age_sousTraitant = Carbon::parse($consultation->sousTraitant->date_naissance)->age;
        }

        $region = Region::where('code_region', auth()->user()->region_cmt)->first();
        $cmt = CMT::find(3);
        $organiques = EmployeOrganique::where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->get();
        $sousTraitants = SousTraitant::where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->get();
        $zones = Zone::where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->get();

        return view('DR.covid.consultation', [
            'consultation' => $consultation,
            'age_organique' => $age_organique,
            'age_sousTraitant' => $age_sousTraitant,
            'organiques' => $organiques,
            'sousTraitants' => $sousTraitants,
            'region' => $region,
            'cmt' => $cmt,
            'zones' => $zones,
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
        if (!empty($consultation->organique_id)) {
            $employe = EmployeOrganique::where('matricule', $consultation->employeOrganique->matricule)->first();

            if (
                $employe->releves()->where('date_releve', $consultation->date_consultation)->exists()) {
                $r = $employe
                    ->releves()
                    ->where('date_releve', $consultation->date_consultation)
                    ->first();
                $z = Zone::find($r->zone_id);
                if ($r->etat_releve == 'entrant') {
                    //augmenter la capcité de la zone
                    $z->nb_places = $z->nb_places + 1;
                    $z->save();
                } else {
                    //diminuer la capcité de la zone
                    $z->nb_places = $z->nb_places - 1;
                    $z->save();
                }
                $r->delete();

                //inserer la nouvelle releve
                if (
                    Releve::where('organique_id', $employe->id)
                        ->where('etat_releve', 'entrant')
                        ->where('date_releve', '>=', Carbon::parse($request->date_consultation)->subDays(30))
                        ->exists()
                ) {
                    $releve_entrant = Releve::where('organique_id', $employe->id)
                        ->where('etat_releve', 'entrant')
                        ->where('date_releve', '>', Carbon::parse($request->date_consultation)->subDays(30))
                        ->first();
                    $zone = Zone::find($releve_entrant->zone_id);
                    if ($request->filled('evolution_maladie')) {
                        if ($request->evolution_maladie == 'G' || $request->evolution_maladie == 'D') {
                            $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'sortant', 'couverture' => 'O', 'zone_id' => $releve_entrant->zone_id, 'region_id' => $request->region, 'organique_id' => $employe->id]);
                            $employe->releves()->save($releve);

                            //augmenter la capcité de la zone
                            $zone->nb_places = $zone->nb_places + 1;
                            $zone->save();
                        }
                    }
                } else {
                    if ($request->filled('zone') && !$request->filled('evolution_maladie')) {
                        //verifier la disponibilté des places dans la zone
                        $zone = Zone::find($request->zone);

                        if ($zone->nb_places > 0) {
                            $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'entrant', 'couverture' => 'O', 'zone_id' => $request->zone, 'region_id' => $request->region, 'organique_id' => $employe->id]);

                            $employe->releves()->save($releve);

                            //diminuer la capcité de la zone
                            $zone->nb_places = $zone->nb_places - 1;
                            $zone->save();
                        } else {
                            return redirect()
                                ->route('DR.consultations.index')
                                ->with('error', 'Pas de places disponibles dans cette zone de confinement !');
                        }
                    }
                }
            }

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
            $consultation->zone_id = $request->zone;
            $consultation->save();
        }
        if (!empty($consultation->sousTraitant_id)) {
            $sousTraitant = SousTraitant::where('matricule', $consultation->sousTraitant->matricule)->first();

            if (
                $sousTraitant
                    ->releves()
                    ->where('date_releve', $consultation->date_consultation)
                    ->exists()
            ) {
                $r = $sousTraitant
                    ->releves()
                    ->where('date_releve', $consultation->date_consultation)
                    ->first();
                $z = Zone::find($r->zone_id);
                if ($r->etat_releve == 'entrant') {
                    //augmenter la capcité de la zone
                    $z->nb_places = $z->nb_places + 1;
                    $z->save();
                } else {
                    //diminuer la capcité de la zone
                    $z->nb_places = $z->nb_places - 1;
                    $z->save();
                }
                $r->delete();

                //inserer la nouvelle releve
                if (
                    Releve::where('sousTraitant_id', $sousTraitant->id)
                        ->where('etat_releve', 'entrant')
                        ->where('date_releve', '>', Carbon::parse($request->date_consultation)->subDays(30))
                        ->exists()
                ) {
                    $releve_entrant = Releve::where('sousTraitant_id', $sousTraitant->id)
                        ->where('etat_releve', 'entrant')
                        ->where('date_releve', '>', Carbon::parse($request->date_consultation)->subDays(30))
                        ->first();
                    $zone = Zone::find($releve_entrant->zone_id);
                    if ($request->filled('evolution_maladie')) {
                        if ($request->evolution_maladie == 'G' || $request->evolution_maladie == 'D') {
                            $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'sortant', 'couverture' => 'ST', 'zone_id' => $releve_entrant->zone_id, 'region_id' => $request->region, 'sousTraitant_id' => $sousTraitant->id]);
                            $sousTraitant->releves()->save($releve);

                            //augmenter la capcité de la zone
                            $zone->nb_places = $zone->nb_places + 1;
                            $zone->save();
                        }
                    }
                } else {
                    if ($request->filled('zone') && !$request->filled('evolution_maladie')) {
                        //verifier la disponibilté des places dans la zone
                        $zone = Zone::find($request->zone);
                        if ($zone->nb_places > 0) {
                            $releve = new Releve(['date_releve' => $request->date_consultation, 'etat_releve' => 'entrant', 'couverture' => 'ST', 'zone_id' => $request->zone, 'region_id' => $request->region, 'sousTraitant_id' => $sousTraitant->id]);

                            $sousTraitant->releves()->save($releve);

                            //diminuer la capcité de la zone
                            $zone->nb_places = $zone->nb_places - 1;
                            $zone->save();
                        } else {
                            return redirect()
                                ->route('DR.consultations.index')
                                ->with('error', 'Pas de places disponibles dans cette zone de confinement !');
                        }
                    }
                }
            }

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
            $consultation->zone_id = $request->zone;
            $consultation->save();
        }
        return redirect()
            ->route('DR.consultations.show', $consultation)
            ->with('status', 'Consultation modifiée avec succès !');
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

        if (!empty($consultation->organique_id)) {
            $employe = EmployeOrganique::where('matricule', $consultation->employeOrganique->matricule)->first();
            if (
                $employe
                    ->releves()
                    ->where('date_releve', $consultation->date_consultation)
                    ->exists()
            ) {
                $employe
                    ->releves()
                    ->where('date_releve', $consultation->date_consultation)
                    ->first()
                    ->delete();
            }
        }
        if (!empty($consultation->sousTraitant_id)) {
            $sousTraitant = SousTraitant::where('matricule', $consultation->sousTraitant->matricule)->first();
            if (
                $sousTraitant
                    ->releves()
                    ->where('date_releve', $consultation->date_consultation)
                    ->exists()
            ) {
                $sousTraitant
                    ->releves()
                    ->where('date_releve', $consultation->date_consultation)
                    ->first()
                    ->delete();
            }
        }
        $consultation->delete();
        $depistage->delete();

        return redirect()
            ->route('DR.consultations.index')
            ->with('status', 'Consultation supprimée avec succès');
    }
}
