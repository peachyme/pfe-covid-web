<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Reunion;
use App\Mail\ReunionsMail;
use Illuminate\Http\Request;
use App\Mail\CancelReunionMail;
use App\Mail\UpdateReunionMail;
use App\Models\EmployeOrganique;
use Illuminate\Support\Facades\Mail;

class ReunionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organiques = EmployeOrganique::where('region_id', 1)->get();
        if ($request->ajax()) {
            $events = array();
            $reunions = Reunion::all();
            foreach ($reunions as $reunion) {
                $events[] = [
                    'id' => $reunion->id,
                    'title' => $reunion->title,
                    'start' => $reunion->debut_reunion,
                    'end' => $reunion->fin_reunion,
                ];
            }
            return $events;
        }
        return view('reunions.index', compact('organiques'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Carbon::setLocale('fr');
        $start =  date('Y-m-d H:i:s', strtotime("$request->date_reunion $request->start_time"));
        $end =  date('Y-m-d H:i:s', strtotime("$request->date_reunion $request->end_time"));
        $reunion = new Reunion([
            'debut_reunion' => $start,
            'fin_reunion' => $end,
            'title' => $request->title
        ]);

        $reunion->save();

        $membres = EmployeOrganique::whereIn('id', $request->nom)->get();
        foreach ($membres as $membre) {
            $reunion->employes()->attach($membre);
        }
        $date_reunion =  Carbon::parse($reunion->debut_reunion)->translatedFormat('l d F Y');
        $heure_deb =  Carbon::parse($reunion->debut_reunion)->format('h:i');
        $heure_fin =  Carbon::parse($reunion->fin_reunion)->format('h:i');
        foreach ($membres as $membre) {
            $info = [
                'nom' =>$membre->nom,
                'prenom' => $membre->prenom,
                'date_reunion' => $date_reunion,
                'heure_deb' => $heure_deb,
                'heure_fin' => $heure_fin,
                'objet' => $reunion->title,
                'sexe' => $membre->sexe,
                'situation_familiale' => $membre->situation_familiale,
                'membres' => $membres,
            ];
            Mail::to($membre->email)->send(new ReunionsMail($info));
        }

        return redirect()->route('reunions.index')->with('status', 'Réunion enregistrée avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reunion  $reunion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Carbon::setLocale('fr');
        $reunion = Reunion::find($id);
        $organiques = EmployeOrganique::where('region_id', 1)->get();
        return view('reunions.showReunion', compact('reunion', 'organiques'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reunion  $reunion
     * @return \Illuminate\Http\Response
     */
    public function edit(Reunion $reunion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reunion  $reunion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Carbon::setLocale('fr');
        $reunion = Reunion::find($id);
        if($request->has('protocole'))
        {
            $file_name = 'Protocôle Sanitaire du ' . date('Y-m-d', strtotime($reunion->debut_reunion)) . '.' . $request->protocole->extension();
            $protocole = $request->protocole->storeAs('pdfs' , $file_name , 'public');
            $reunion->protocole = $protocole;
            $reunion->save();
            return redirect()->route('reunions.show', $id)->with('status', 'Protocole enregistré avec succès !');
        }
        if($request->has('pv'))
        {
            $file_name = 'PV de la réunion du ' . date('Y-m-d', strtotime($reunion->debut_reunion)) . '.' . $request->pv->extension();
            $pv = $request->pv->storeAs('pdfs' , $file_name , 'public');
            $reunion->pv = $pv;
            $reunion->save();
            return redirect()->route('reunions.show', $id)->with('status', 'PV enregistré avec succès !');
        }
        if ($request->filled('nom')) {
            $start_date = date('Y-m-d', strtotime($reunion->debut_reunion));
            $end_date = date('Y-m-d', strtotime($reunion->fin_reunion));
            $start =  date('Y-m-d H:i:s', strtotime("$start_date $request->start_time"));
            $end =  date('Y-m-d H:i:s', strtotime("$end_date $request->end_time"));
            $date_reunion =  Carbon::parse($reunion->debut_reunion)->translatedFormat('l d F Y');
            $heure_deb =  Carbon::parse($reunion->debut_reunion)->format('h:i');
            $heure_fin =  Carbon::parse($reunion->fin_reunion)->format('h:i');
            foreach ($reunion->employes as $membre) {
                $info = [
                    'nom' =>$membre->nom,
                    'prenom' => $membre->prenom,
                    'date_reunion' => $date_reunion,
                    'heure_deb' => $heure_deb,
                    'heure_fin' => $heure_fin,
                    'objet' => $reunion->title,
                    'sexe' => $membre->sexe,
                    'situation_familiale' => $membre->situation_familiale,
                    'membres' => $reunion->employes,
                ];
            }
            $reunion->employes()->sync($request->nom); //sync cuz we're using an array
            $reunion->update(
                [
                    'debut_reunion' => $start,
                    'fin_reunion' => $end,
                    'title' => $request->title,

                ]
            );
            $date_reunion =  Carbon::parse($reunion->debut_reunion)->translatedFormat('l d F Y');
            $heure_deb =  Carbon::parse($reunion->debut_reunion)->format('h:i');
            $heure_fin =  Carbon::parse($reunion->fin_reunion)->format('h:i');
            foreach ($reunion->employes as $membre) {
                $info_nv = [
                    'nom' =>$membre->nom,
                    'prenom' => $membre->prenom,
                    'date_reunion' => $date_reunion,
                    'heure_deb' => $heure_deb,
                    'heure_fin' => $heure_fin,
                    'objet' => $reunion->title,
                    'sexe' => $membre->sexe,
                    'situation_familiale' => $membre->situation_familiale,
                    'membres' => $reunion->employes,
                ];
                Mail::to($membre->email)->send(new UpdateReunionMail($info, $info_nv));
            }
            return redirect()->route('reunions.show', $id)->with('status', 'Réunion modifiée avec succès !');
        }
        if (!$reunion) {
            return response()->json(['error' => 'reunion introuvable !'], 404);
        }
        $start_time = date('H:i:s', strtotime($reunion->debut_reunion));
        $end_time = date('H:i:s', strtotime($reunion->fin_reunion));
        $start =  date('Y-m-d H:i:s', strtotime("$request->start_date $start_time"));
        $end =  date('Y-m-d H:i:s', strtotime("$request->end_date $end_time"));
        $date_reunion =  Carbon::parse($reunion->debut_reunion)->translatedFormat('l d F Y');
            $heure_deb =  Carbon::parse($reunion->debut_reunion)->format('h:i');
            $heure_fin =  Carbon::parse($reunion->fin_reunion)->format('h:i');
            foreach ($reunion->employes as $membre) {
                $info = [
                    'nom' =>$membre->nom,
                    'prenom' => $membre->prenom,
                    'date_reunion' => $date_reunion,
                    'heure_deb' => $heure_deb,
                    'heure_fin' => $heure_fin,
                    'objet' => $reunion->title,
                    'sexe' => $membre->sexe,
                    'situation_familiale' => $membre->situation_familiale,
                    'membres' => $reunion->employes,
                ];
            }
        $reunion->update(
            [
                'debut_reunion' => $start,
                'fin_reunion' => $end
            ]
        );
        $date_reunion =  Carbon::parse($reunion->debut_reunion)->translatedFormat('l d F Y');
            $heure_deb =  Carbon::parse($reunion->debut_reunion)->format('h:i');
            $heure_fin =  Carbon::parse($reunion->fin_reunion)->format('h:i');
            foreach ($reunion->employes as $membre) {
                $info_nv = [
                    'nom' =>$membre->nom,
                    'prenom' => $membre->prenom,
                    'date_reunion' => $date_reunion,
                    'heure_deb' => $heure_deb,
                    'heure_fin' => $heure_fin,
                    'objet' => $reunion->title,
                    'sexe' => $membre->sexe,
                    'situation_familiale' => $membre->situation_familiale,
                    'membres' => $reunion->employes,
                ];
                Mail::to($membre->email)->send(new UpdateReunionMail($info, $info_nv));
            }
        return response()->json('Réunion modifiée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reunion  $reunion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Carbon::setLocale('fr');
        $reunion = Reunion::find($id);
        $date_reunion =  Carbon::parse($reunion->debut_reunion)->translatedFormat('l d F Y');
        $heure_deb =  Carbon::parse($reunion->debut_reunion)->format('h:i');
        $heure_fin =  Carbon::parse($reunion->fin_reunion)->format('h:i');
        foreach ($reunion->employes as $membre) {
            $info = [
                'nom' =>$membre->nom,
                'prenom' => $membre->prenom,
                'date_reunion' => $date_reunion,
                'heure_deb' => $heure_deb,
                'heure_fin' => $heure_fin,
                'objet' => $reunion->title,
                'sexe' => $membre->sexe,
                'situation_familiale' => $membre->situation_familiale,
            ];
            Mail::to($membre->email)->send(new CancelReunionMail($info));
        }

        $reunion->employes()->detach();
        $reunion->delete();

        return redirect()->route('reunions.index')->with('status', 'Réunion supprimée avec succès !');
    }
}
