<?php

namespace App\Http\Controllers\CMT;

use App\Models\CMT;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CmtsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cmts = CMT::orderBy('id','ASC')->paginate(8);

        // cas positifs
        $report_pos = [];
        $consultations_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['depistages.cmt_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->where('depistages.resultat_test', 'Positif')
            ->groupBy('depistages.cmt_id')
            ->get();

        $consultations_pos->each(function ($item) use (&$report_pos) {
            $report_pos[$item->cmt_id] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });

        // cas decedés
        $report_deces = [];
        $consultations_deces = Consultation::select(['cmt_id', DB::raw('COUNT(consultations.id) as cas_deces')])
            ->where('evolution_maladie', 'D')
            ->groupBy('cmt_id')
            ->get();

        $consultations_deces->each(function ($item) use (&$report_deces) {
            $report_deces[$item->cmt_id] = [
                'cas_deces' => $item->cas_deces,
            ];
        });

        return view('CMT.cmts.index', [
            'cmts' => $cmts,
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
        $cmt = new CMT(['code_cmt' => $request->code_cmt ,'libellé_cmt' => $request->libelle_cmt]);
        $cmt->save();
        return redirect()->route('CMT.cmts.index')->with('status', 'CMT ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CMT  $cMT
     * @return \Illuminate\Http\Response
     */
    public function show(CMT $cMT)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CMT  $cMT
     * @return \Illuminate\Http\Response
     */
    public function edit(CMT $cMT)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CMT  $cMT
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CMT $cmt)
    {
        $cmt->code_cmt = $request->code_cmt;
        $cmt->libellé_cmt = $request->libelle_cmt;

        $cmt->save();

        return redirect()->route('CMT.cmts.index')->with('status', 'CMT modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CMT  $cMT
     * @return \Illuminate\Http\Response
     */
    public function destroy(CMT $cmt)
    {
        $cmt->delete();

        return redirect()->route('CMT.cmts.index')->with('status', 'CMT supprimé avec succès !');
    }
}
