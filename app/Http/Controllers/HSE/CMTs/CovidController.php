<?php

namespace App\Http\Controllers\HSE\CMTs;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class CovidController extends Controller
{
    public function showSituationCovid(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');

        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::now()->format('d-m-Y');
        $situation = "Situation du jour le " . $today . " : ";
        $consultations = Consultation::where('date_consultation', $date)
            ->orderBy('date_consultation')->paginate(5);
        $cmts = CMT::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $month = Carbon::now()->format('m-Y');
        $current_week = Carbon::now()->weekOfYear;

        if ((isset($_GET['cmt'])) || (isset($_GET['couverture'])) || (isset($_GET['week'])) || (isset($_GET['month'])) || (isset($_GET['year']))) {

            $cmt = "";
            $couverture = "";
            $start = Carbon::now()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');

            if ($request->filled('year')) {
                $start = Carbon::createFromDate($_GET['year'])->startOfYear()->format('Y-m-d');
                $end = Carbon::createFromDate($_GET['year'])->endOfYear()->format('Y-m-d');
                $year = $_GET['year'];
                $situation = "Situation annuel " . $year . " : ";
            }
            if ($request->filled('month')) {
                $start = Carbon::createFromDate($_GET['month'])->startOfMonth()->format('Y-m-d');
                $end = Carbon::createFromDate($_GET['month'])->endOfMonth()->format('Y-m-d');
                $month = Carbon::createFromDate($_GET['month'])->format('m-Y');
                $situation = "Situation mensuel " . $month . " : ";
            }
            if ($request->filled('week')) {
                $start = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('Y-m-d');
                $s = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('d-m-Y');
                $end = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('Y-m-d');
                $e = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('d-m-Y');
                $situation = "Situation hebdomadaire du " . $s . " au " . $e . " : ";
            }
            if ($request->filled('cmt')) {
                $cmt = $_GET['cmt'];
            }
            if ($request->filled('couverture')) {
                $couverture = $_GET['couverture'];
            }

            if ($couverture == 'ST') {
                $consultations = Consultation::whereNotNull('sousTraitant_id')
                    ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->orderBy('date_consultation')
                    ->paginate(5);
            } else {
                if ($couverture == 'O') {
                    $consultations = Consultation::whereNotNull('organique_id')
                        ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                        ->whereBetween('date_consultation', [$start, $end])
                        ->orderBy('date_consultation')
                        ->paginate(5);
                } else {
                    $consultations = Consultation::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                        ->whereBetween('date_consultation', [$start, $end])
                        ->orderBy('date_consultation')
                        ->paginate(5);
                }
            }
            $consultations->appends($request->all());
        } else {
            return view('HSE.CMTs.covid.situationCovid', [
                'consultations' => $consultations,
                'cmts' => $cmts,
                'current_year' => $current_year,
                'current_month' => $current_month,
                'current_week' => $current_week,
                'situation' => $situation,
            ]);
        }

        return view('HSE.CMTs.covid.situationCovid', [
            'consultations' => $consultations,
            'cmts' => $cmts,
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'situation' => $situation,
        ]);
    }

    public function generateReporting(Request $request)
    {
        Carbon::setLocale('fr');
        $date = Carbon::now()->format('d-m-Y');
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $code = "global du " . $s . " au " . $e;

        if ($request->filled('year')) {
            $start = Carbon::createFromDate($_GET['year'])->startOfYear()->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['year'])->endOfYear()->format('Y-m-d');
            $year = $_GET['year'];
            $code = "Situation annuel " . $year;
        }
        if ($request->filled('month')) {
            $start = Carbon::createFromDate($_GET['month'])->startOfMonth()->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['month'])->endOfMonth()->format('Y-m-d');
            $month = Carbon::createFromDate($_GET['month'])->format('m-Y');
            $code = "Situation mensuel " . $month;
        }
        if ($request->filled('week')) {
            $start = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('Y-m-d');
            $s = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('d-m-Y');
            $end = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('Y-m-d');
            $e = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('d-m-Y');
            $code = "hebdomadaire du " . $s . " au " . $e;
        }

        $consultations_siege = Consultation::where('cmt_id', 1)
            ->whereBetween('date_consultation', [$start, $end])
            ->orderBy('date_consultation')
            ->get();

        $consultations_siege_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->whereBetween('date_consultation', [$start, $end])
            ->where('resultat_test', 'Positif')
            ->where('consultations.cmt_id', 1)
            ->count();

        $consultations_siege_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->whereBetween('date_consultation', [$start, $end])
            ->where('resultat_test', 'Positif')
            ->where('evolution_maladie', 'P')
            ->where('consultations.cmt_id', 1)
            ->count();

        $consultations_siege_hosp = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->where('consultations.cmt_id', 1)
            ->count();

        $consultations_siege_deces = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->where('consultations.cmt_id', 1)
            ->count();

        $consultations_siege_conf =
            (Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'BDV')
                ->where('consultations.cmt_id', 1)
                ->count()
                +
                Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'D')
                ->where('consultations.cmt_id', 1)
                ->count()
                +
                Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'H')
                ->where('consultations.cmt_id', 1)
                ->count())
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'RT')
            ->where('consultations.cmt_id', 1)
            ->count()
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->where('consultations.cmt_id', 1)
            ->count()
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'P')
            ->where('consultations.cmt_id', 1)
            ->count();

        $consultations_siege_gueris = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'G')
            ->where('consultations.cmt_id', 1)
            ->count();

        $consultations_os = Consultation::where('cmt_id', 2)
            ->whereBetween('date_consultation', [$start, $end])
            ->orderBy('date_consultation')
            ->get();

        $consultations_os_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->whereBetween('date_consultation', [$start, $end])
            ->where('resultat_test', 'Positif')
            ->where('consultations.cmt_id', 2)
            ->count();

        $consultations_os_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->whereBetween('date_consultation', [$start, $end])
            ->where('resultat_test', 'Positif')
            ->where('evolution_maladie', 'P')
            ->where('consultations.cmt_id', 2)
            ->count();

        $consultations_os_hosp = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->where('consultations.cmt_id', 2)
            ->count();

        $consultations_os_deces = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->where('consultations.cmt_id', 2)
            ->count();

        $consultations_os_conf =
            (Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'BDV')
                ->where('consultations.cmt_id', 2)
                ->count()
                +
                Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'D')
                ->where('consultations.cmt_id', 2)
                ->count()
                +
                Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'H')
                ->where('consultations.cmt_id', 2)
                ->count())
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'RT')
            ->where('consultations.cmt_id', 2)
            ->count()
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->where('consultations.cmt_id', 2)
            ->count()
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'P')
            ->where('consultations.cmt_id', 2)
            ->count();

        $consultations_os_gueris = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'G')
            ->where('consultations.cmt_id', 2)
            ->count();

        $consultations_rds = Consultation::where('cmt_id', 3)
            ->whereBetween('date_consultation', [$start, $end])
            ->orderBy('date_consultation')
            ->get();

        $consultations_rds_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->whereBetween('date_consultation', [$start, $end])
            ->where('consultations.cmt_id', 3)
            ->where('depistages.resultat_test', 'Positif')
            ->count();

        $consultations_rds_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->whereBetween('date_consultation', [$start, $end])
            ->where('consultations.evolution_maladie', 'P')
            ->where('consultations.cmt_id', 3)
            ->where('depistages.resultat_test', 'Positif')
            ->count();

        $consultations_rds_hosp = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->where('consultations.cmt_id', 3)
            ->count();

        $consultations_rds_deces = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->where('consultations.cmt_id', 3)
            ->count();

        $consultations_rds_conf =
            (Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'BDV')
                ->where('consultations.cmt_id', 3)
                ->count()
                +
                Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'D')
                ->where('consultations.cmt_id', 3)
                ->count()
                +
                Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'H')
                ->where('consultations.cmt_id', 3)
                ->count())
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'RT')
            ->where('consultations.cmt_id', 3)
            ->count()
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->where('consultations.cmt_id', 3)
            ->count()
            -
            Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'P')
            ->where('consultations.cmt_id', 3)
            ->count();

        $consultations_rds_gueris = Consultation::whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'G')
            ->where('consultations.cmt_id', 3)
            ->count();

        $pdf = PDF::loadView('HSE.CMTs.covid.pdf.etatNominatif3CMT', [
            'consultations_siege' => $consultations_siege,
            'consultations_siege_pos' => $consultations_siege_pos - $consultations_siege_pro - $consultations_siege_deces,
            'consultations_siege_hosp' => $consultations_siege_hosp,
            'consultations_siege_deces' => $consultations_siege_deces,
            'consultations_siege_conf' => $consultations_siege_conf,
            'consultations_siege_gueris' => $consultations_siege_gueris,
            'consultations_os' => $consultations_os,
            'consultations_os_pos' => $consultations_os_pos - $consultations_os_pro - $consultations_os_deces,
            'consultations_os_hosp' => $consultations_os_hosp,
            'consultations_os_deces' => $consultations_os_deces,
            'consultations_os_conf' => $consultations_os_conf,
            'consultations_os_gueris' => $consultations_os_gueris,
            'consultations_rds' => $consultations_rds,
            'consultations_rds_pos' => $consultations_rds_pos - $consultations_rds_pro - $consultations_rds_deces,
            'consultations_rds_hosp' => $consultations_rds_hosp,
            'consultations_rds_deces' => $consultations_rds_deces,
            'consultations_rds_conf' => $consultations_rds_conf,
            'consultations_rds_gueris' => $consultations_rds_gueris,
            'date' => $date,
            'code' => $code,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Reporting ' . $code . ' de la situation COVID-19 des 3 CMT.pdf');
    }
}
