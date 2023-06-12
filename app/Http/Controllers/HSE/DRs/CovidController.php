<?php

namespace App\Http\Controllers\HSE\DRs;

use Carbon\Carbon;
use App\Models\Region;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDF;

class CovidController extends Controller
{
    public function showSituationCovid(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::now()->format('d-m-Y');
        $situation = 'Situation du jour le ' . $today . ' : ';
        $regions = Region::paginate(5);
        $regions_select = Region::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $month = Carbon::now()->format('m-Y');
        $current_week = Carbon::now()->weekOfYear;

        // if ((isset($_GET['region'])) || (isset($_GET['date'])) || (isset($_GET['week'])) || (isset($_GET['month'])) || (isset($_GET['year']))) {

        $start = Carbon::now()->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $region = '';

        if ($request->filled('year')) {
            $start = Carbon::createFromDate($_GET['year'])
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['year'])
                ->endOfYear()
                ->format('Y-m-d');
            $situation = 'Situation annuel ' . $current_year . ' : ';
        }
        if ($request->filled('month')) {
            $start = Carbon::createFromDate($_GET['month'])
                ->startOfMonth()
                ->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['month'])
                ->endOfMonth()
                ->format('Y-m-d');
            $situation = 'Situation mensuel ' . $month . ' : ';
        }
        if ($request->filled('week')) {
            $start = Carbon::createFromDate($_GET['week'])
                ->startOfWeek()
                ->format('Y-m-d');
            $s = Carbon::createFromDate($_GET['week'])
                ->startOfWeek()
                ->format('d-m-Y');
            $end = Carbon::createFromDate($_GET['week'])
                ->endOfWeek()
                ->format('Y-m-d');
            $e = Carbon::createFromDate($_GET['week'])
                ->endOfWeek()
                ->format('d-m-Y');
            $situation = 'Situation hebdomadaire du ' . $s . ' au ' . $e . ' : ';
        }
        if ($request->filled('date')) {
            $start = Carbon::createFromDate($_GET['date'])->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['date'])->format('Y-m-d');
            $situation = 'Situation quotidienne le ' . Carbon::createFromDate($_GET['date'])->format('d-m-Y') . ' : ';
        }
        if ($request->filled('region')) {
            $region = $_GET['region'];
            $no_region = false;
            $code_region = Region::find($region)->code_region;

            //start
            $consultations_total = Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('region_id', $region)
                ->count();

            $consultations_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->whereBetween('date_consultation', [$start, $end])
                ->where('region_id', $region)
                ->where('depistages.resultat_test', 'Positif')
                ->count();

            $consultations_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->whereBetween('date_consultation', [$start, $end])
                ->where('consultations.evolution_maladie', 'P')
                ->where('region_id', $region)
                ->where('depistages.resultat_test', 'Positif')
                ->count();

            $consultations_hosp = Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'H')
                ->where('region_id', $region)
                ->count();

            $consultations_deces = Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('evolution_maladie', 'D')
                ->where('region_id', $region)
                ->count();

            $consultations_conf =
                Consultation::whereBetween('date_consultation', [$start, $end])
                    ->where('modalités_priseEnCharge', 'BDV')
                    ->where('region_id', $region)
                    ->count() +
                Consultation::whereBetween('date_consultation', [$start, $end])
                    ->where('modalités_priseEnCharge', 'D')
                    ->where('region_id', $region)
                    ->count() +
                Consultation::whereBetween('date_consultation', [$start, $end])
                    ->where('modalités_priseEnCharge', 'H')
                    ->where('region_id', $region)
                    ->count() -
                Consultation::whereBetween('date_consultation', [$start, $end])
                    ->where('modalités_priseEnCharge', 'RT')
                    ->where('region_id', $region)
                    ->count() -
                Consultation::whereBetween('date_consultation', [$start, $end])
                    ->where('evolution_maladie', 'D')
                    ->where('region_id', $region)
                    ->count() -
                Consultation::whereBetween('date_consultation', [$start, $end])
                    ->where('evolution_maladie', 'P')
                    ->where('region_id', $region)
                    ->count();

            $consultations_gueris = Consultation::whereBetween('date_consultation', [$start, $end])
                ->where('evolution_maladie', 'G')
                ->where('region_id', $region)
                ->count();
            //end
            $regions->appends($request->all());
            return view('HSE.DRs.covid.situationCovid', [
                'regions' => $regions,
                'situation' => $situation,
                'date' => $date,
                'current_year' => $current_year,
                'current_month' => $current_month,
                'current_week' => $current_week,
                'regions_select' => $regions_select,
                'consultations_pos' => $consultations_pos - $consultations_pro - $consultations_deces,
                'consultations_hosp' => $consultations_hosp,
                'consultations_deces' => $consultations_deces,
                'consultations_conf' => $consultations_conf,
                'consultations_gueris' => $consultations_gueris,
                'consultations_total' => $consultations_total,
                'no_region' => $no_region,
                'code_region' => $code_region,
            ]);
        } else {
            //start
            $no_region = true;
            // total
            $report_total = [];
            $consultations_total = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select(['region_id', DB::raw('COUNT(consultations.id) as total')])
                ->whereBetween('date_consultation', [$start, $end])
                ->groupBy('region_id')
                ->get();

            $consultations_total->each(function ($item) use (&$report_total) {
                $report_total[$item->region_id] = [
                    'total' => $item->total,
                ];
            });

            // cas positifs
            $report_pos = [];
            $consultations_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->groupBy('region_id')
                ->get();

            $consultations_pos->each(function ($item) use (&$report_pos) {
                $report_pos[$item->region_id] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            // cas prolongé
            $report_pro = [];
            $consultations_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_pro')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->where('consultations.evolution_maladie', 'P')
                ->groupBy('region_id')
                ->get();

            $consultations_pro->each(function ($item) use (&$report_pro) {
                $report_pro[$item->region_id] = [
                    'cas_pro' => $item->cas_pro,
                ];
            });

            // cas hospitalisés
            $report_hosp = [];
            $consultations_hosp = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_hosp')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'H')
                ->groupBy('region_id')
                ->get();

            $consultations_hosp->each(function ($item) use (&$report_hosp) {
                $report_hosp[$item->region_id] = [
                    'cas_hosp' => $item->cas_hosp,
                ];
            });

            // cas decedés
            $report_deces = [];
            $consultations_deces = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_deces')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('evolution_maladie', 'D')
                ->groupBy('region_id')
                ->get();

            $consultations_deces->each(function ($item) use (&$report_deces) {
                $report_deces[$item->region_id] = [
                    'cas_deces' => $item->cas_deces,
                ];
            });

            // cas en confinement
            $consultations_conf = '';

            $report_bdv = [];
            $count_bdv = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_bdv')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'BDV')
                ->groupBy('region_id')
                ->get();

            $count_bdv->each(function ($item) use (&$report_bdv) {
                $report_bdv[$item->region_id] = [
                    'cas_bdv' => $item->cas_bdv,
                ];
            });
            //+
            $report_dom = [];
            $count_dom = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_dom')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'D')
                ->groupBy('region_id')
                ->get();

            $count_dom->each(function ($item) use (&$report_dom) {
                $report_dom[$item->region_id] = [
                    'cas_dom' => $item->cas_dom,
                ];
            });
            //+
            $report_hosp = [];
            $count_hosp = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_hosp')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'H')
                ->groupBy('region_id')
                ->get();

            $count_hosp->each(function ($item) use (&$report_hosp) {
                $report_hosp[$item->region_id] = [
                    'cas_hosp' => $item->cas_hosp,
                ];
            });
            //-
            $report_rt = [];
            $count_rt = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_rt')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('modalités_priseEnCharge', 'RT')
                ->groupBy('region_id')
                ->get();

            $count_rt->each(function ($item) use (&$report_rt) {
                $report_rt[$item->region_id] = [
                    'cas_rt' => $item->cas_rt,
                ];
            });
            //-
            $report_d = [];
            $count_d = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_d')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('evolution_maladie', 'D')
                ->groupBy('region_id')
                ->get();

            $count_d->each(function ($item) use (&$report_d) {
                $report_d[$item->region_id] = [
                    'cas_d' => $item->cas_d,
                ];
            });
            //-
            $report_p = [];
            $count_p = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_p')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('evolution_maladie', 'P')
                ->groupBy('region_id')
                ->get();

            $count_p->each(function ($item) use (&$report_p) {
                $report_p[$item->region_id] = [
                    'cas_p' => $item->cas_p,
                ];
            });
            // cas guéris
            $report_gueris = [];
            $consultations_gueris = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_gueris')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('evolution_maladie', 'G')
                ->groupBy('region_id')
                ->get();

            $consultations_gueris->each(function ($item) use (&$report_gueris) {
                $report_gueris[$item->region_id] = [
                    'cas_gueris' => $item->cas_gueris,
                ];
            });
        }
        $regions->appends($request->all());
        return view('HSE.DRs.covid.situationCovid', [
            'regions' => $regions,
            'situation' => $situation,
            'date' => $date,
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'regions' => $regions,
            'regions_select' => $regions_select,
            'no_region' => $no_region,
            'report_pos' => $report_pos,
            'report_pro' => $report_pro,
            'report_hosp' => $report_hosp,
            'report_deces' => $report_deces,
            'report_bdv' => $report_bdv,
            'report_dom' => $report_dom,
            'report_hosp' => $report_hosp,
            'report_rt' => $report_rt,
            'report_d' => $report_d,
            'report_p' => $report_p,
            'report_gueris' => $report_gueris,
            'report_total' => $report_total,
        ]);
    }

    public function generateReporting(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');
        $regions = Region::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $month = Carbon::now()->format('m-Y');
        $current_week = Carbon::now()->weekOfYear;

        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $code = 'global du ' . $s . ' au ' . $e;

        if ($request->filled('year')) {
            $start = Carbon::createFromDate($request->year)
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::createFromDate($request->year)
                ->endOfYear()
                ->format('Y-m-d');
            $code = 'annuel ' . $current_year;
        }
        if ($request->filled('month')) {
            $start = Carbon::createFromDate($request->month)
                ->startOfMonth()
                ->format('Y-m-d');
            $end = Carbon::createFromDate($request->month)
                ->endOfMonth()
                ->format('Y-m-d');
            $code = 'mensuel ' . $month;
        }
        if ($request->filled('week')) {
            $start = Carbon::createFromDate($request->week)
                ->startOfWeek()
                ->format('Y-m-d');
            $s = Carbon::createFromDate($request->week)
                ->startOfWeek()
                ->format('d-m-Y');
            $end = Carbon::createFromDate($request->week)
                ->endOfWeek()
                ->format('Y-m-d');
            $e = Carbon::createFromDate($request->week)
                ->endOfWeek()
                ->format('d-m-Y');
            $code = 'hebdomadaire du ' . $s . ' au ' . $e;
        }
        if ($request->filled('date')) {
            $start = Carbon::createFromDate($request->date)->format('Y-m-d');
            $end = Carbon::createFromDate($request->date)->format('Y-m-d');
            $code = 'quotidien du ' . Carbon::createFromDate($request->date)->format('d-m-Y');
        }

        //start
        // total
        $report_total = [];
        $consultations_total = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as total')])
            ->whereBetween('date_consultation', [$start, $end])
            ->groupBy('region_id')
            ->get();

        $consultations_total->each(function ($item) use (&$report_total) {
            $report_total[$item->region_id] = [
                'total' => $item->total,
            ];
        });

        // cas positifs
        $report_pos = [];
        $consultations_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->groupBy('region_id')
            ->get();

        $consultations_pos->each(function ($item) use (&$report_pos) {
            $report_pos[$item->region_id] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });

        // cas prolongé
        $report_pro = [];
        $consultations_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_pro')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->where('consultations.evolution_maladie', 'P')
            ->groupBy('region_id')
            ->get();

        $consultations_pro->each(function ($item) use (&$report_pro) {
            $report_pro[$item->region_id] = [
                'cas_pro' => $item->cas_pro,
            ];
        });

        // cas hospitalisés
        $report_hosp = [];
        $consultations_hosp = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_hosp')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->groupBy('region_id')
            ->get();

        $consultations_hosp->each(function ($item) use (&$report_hosp) {
            $report_hosp[$item->region_id] = [
                'cas_hosp' => $item->cas_hosp,
            ];
        });

        // cas decedés
        $report_deces = [];
        $consultations_deces = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_deces')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->groupBy('region_id')
            ->get();

        $consultations_deces->each(function ($item) use (&$report_deces) {
            $report_deces[$item->region_id] = [
                'cas_deces' => $item->cas_deces,
            ];
        });

        // cas en confinement
        $consultations_conf = '';

        $report_bdv = [];
        $count_bdv = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_bdv')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'BDV')
            ->groupBy('region_id')
            ->get();

        $count_bdv->each(function ($item) use (&$report_bdv) {
            $report_bdv[$item->region_id] = [
                'cas_bdv' => $item->cas_bdv,
            ];
        });
        //+
        $report_dom = [];
        $count_dom = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_dom')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'D')
            ->groupBy('region_id')
            ->get();

        $count_dom->each(function ($item) use (&$report_dom) {
            $report_dom[$item->region_id] = [
                'cas_dom' => $item->cas_dom,
            ];
        });
        //+
        $report_hosp = [];
        $count_hosp = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_hosp')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->groupBy('region_id')
            ->get();

        $count_hosp->each(function ($item) use (&$report_hosp) {
            $report_hosp[$item->region_id] = [
                'cas_hosp' => $item->cas_hosp,
            ];
        });
        //-
        $report_rt = [];
        $count_rt = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_rt')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'RT')
            ->groupBy('region_id')
            ->get();

        $count_rt->each(function ($item) use (&$report_rt) {
            $report_rt[$item->region_id] = [
                'cas_rt' => $item->cas_rt,
            ];
        });
        //-
        $report_d = [];
        $count_d = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_d')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->groupBy('region_id')
            ->get();

        $count_d->each(function ($item) use (&$report_d) {
            $report_d[$item->region_id] = [
                'cas_d' => $item->cas_d,
            ];
        });
        //-
        $report_p = [];
        $count_p = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_p')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'P')
            ->groupBy('region_id')
            ->get();

        $count_p->each(function ($item) use (&$report_p) {
            $report_p[$item->region_id] = [
                'cas_p' => $item->cas_p,
            ];
        });
        // cas guéris
        $report_gueris = [];
        $consultations_gueris = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_gueris')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'G')
            ->groupBy('region_id')
            ->get();

        $consultations_gueris->each(function ($item) use (&$report_gueris) {
            $report_gueris[$item->region_id] = [
                'cas_gueris' => $item->cas_gueris,
            ];
        });

        $cas_positif_total = 0;
        $cas_gueris_toal = 0;
        $cas_conf_total = 0;
        $cas_hosp_total = 0;
        $cas_deces_total = 0;
        $cas_total = 0;

        foreach ($regions as $reg) {
            $cas_positif_total = $cas_positif_total + ($report_pos[$reg->id]['cas_positifs'] ?? 0) - ($report_pro[$reg->id]['cas_pro'] ?? 0) - ($report_deces[$reg->id]['cas_deces'] ?? 0);
            $cas_gueris_toal = $cas_gueris_toal + ($report_gueris[$reg->id]['cas_gueris'] ?? 0);
            $cas_conf_total = $cas_conf_total + (($report_bdv[$reg->id]['cas_bdv'] ?? 0) + ($report_dom[$reg->id]['cas_dom'] ?? 0) + ($report_hosp[$reg->id]['cas_hosp'] ?? 0)) - ($report_rt[$reg->id]['cas_rt'] ?? 0) - ($report_d[$reg->id]['cas_d'] ?? 0) - ($report_p[$reg->id]['cas_p'] ?? 0);
            $cas_hosp_total = $cas_hosp_total + ($report_hosp[$reg->id]['cas_hosp'] ?? 0);
            $cas_deces_total = $cas_deces_total + ($report_deces[$reg->id]['cas_deces'] ?? 0);
            $cas_total = $cas_total + ($report_total[$reg->id]['total'] ?? 0);
        }

        $observation_siege = $request->observation_siege;
        $observation_hmd = $request->observation_hmd;
        $observation_rns = $request->observation_rns;
        $observation_stah = $request->observation_stah;
        $observation_ohanet = $request->observation_ohanet;
        $observation_hbk = $request->observation_hbk;
        $observation_hrm = $request->observation_hrm;
        $observation_inas = $request->observation_inas;
        $observation_gtl = $request->observation_gtl;
        $observation_tft = $request->observation_tft;
        $observation_reb = $request->observation_reb;

        $observations = [
            '1' => $observation_siege,
            '2' => $observation_hmd,
            '3' => $observation_rns,
            '4' => $observation_stah,
            '5' => $observation_ohanet,
            '6' => $observation_hbk,
            '7' => $observation_hrm,
            '8' => $observation_inas,
            '9' => $observation_gtl,
            '10' => $observation_tft,
            '11' => $observation_reb,
    ];

        $pdf = PDF::loadView('HSE.DRs.covid.pdf.reportingSitesDP', [
            'regions' => $regions,
            'date' => $date,
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'regions' => $regions,
            'report_pos' => $report_pos,
            'report_pro' => $report_pro,
            'report_hosp' => $report_hosp,
            'report_deces' => $report_deces,
            'report_bdv' => $report_bdv,
            'report_dom' => $report_dom,
            'report_hosp' => $report_hosp,
            'report_rt' => $report_rt,
            'report_d' => $report_d,
            'report_p' => $report_p,
            'report_gueris' => $report_gueris,
            'report_total' => $report_total,
            'code' => $code,
            'cas_positif_total' => $cas_positif_total,
            'cas_gueris_toal' => $cas_gueris_toal,
            'cas_conf_total' => $cas_conf_total,
            'cas_hosp_total' => $cas_hosp_total,
            'cas_deces_total' => $cas_deces_total,
            'cas_total' => $cas_total,
            'observations' => $observations,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Reporting ' . $code . ' de la situation COVID-19 des sites DP.pdf');
    }
}
