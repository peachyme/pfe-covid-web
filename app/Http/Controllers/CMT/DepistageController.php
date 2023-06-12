<?php

namespace App\Http\Controllers\CMT;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Depistage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepistageController extends Controller
{
    /**
     * afficher l'activité quotidienne du cmt.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDepistageCMT()
    {
        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::now()->format('d-m-Y');
        $cmt = CMT::where('code_cmt', auth()->user()->region_cmt)->first();

        //couverture SH
        $sh_pcr = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('type_test', 'PCR')
                            ->where('date_test', $date)
                            ->count();

        $sh_pcr_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('type_test', 'PCR')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        $sh_antigenique = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('type_test', 'Antigénique')
                            ->where('date_test', $date)
                            ->count();

        $sh_antigenique_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('type_test', 'Antigénique')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        $sh_serologique = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('type_test', 'Sérologique')
                            ->where('date_test', $date)
                            ->count();

        $sh_serologique_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('type_test', 'Sérologique')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        $sh_depistage = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('date_test', $date)
                            ->count();

        $sh_depistage_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'O')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        //couverture ST
        $st_pcr = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('type_test', 'PCR')
                            ->where('date_test', $date)
                            ->count();

        $st_pcr_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('type_test', 'PCR')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        $st_antigenique = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('type_test', 'Antigénique')
                            ->where('date_test', $date)
                            ->count();

        $st_antigenique_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('type_test', 'Antigénique')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        $st_serologique = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('type_test', 'Sérologique')
                            ->where('date_test', $date)
                            ->count();

        $st_serologique_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('type_test', 'Sérologique')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        $st_depistage = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('date_test', $date)
                            ->count();

        $st_depistage_pos = Depistage::where('cmt_id', $cmt->id)
                            ->where('couverture', 'ST')
                            ->where('resultat_test', 'Positif')
                            ->where('date_test', $date)
                            ->count();

        return view('cmt.depistage.index', [
            'sh_pcr' => $sh_pcr,
            'sh_pcr_pos' => $sh_pcr_pos,
            'sh_antigenique' => $sh_antigenique,
            'sh_antigenique_pos' => $sh_antigenique_pos,
            'sh_serologique' => $sh_serologique,
            'sh_serologique_pos' => $sh_serologique_pos,
            'sh_depistage' => $sh_depistage,
            'sh_depistage_pos' => $sh_depistage_pos,
            'st_pcr' => $st_pcr,
            'st_pcr_pos' => $st_pcr_pos,
            'st_antigenique' => $st_antigenique,
            'st_antigenique_pos' => $st_antigenique_pos,
            'st_serologique' => $st_serologique,
            'st_serologique_pos' => $st_serologique_pos,
            'st_depistage' => $st_depistage,
            'st_depistage_pos' => $st_depistage_pos,
            'today' => $today,
        ]);
    }
}
