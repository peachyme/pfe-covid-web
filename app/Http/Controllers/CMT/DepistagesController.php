<?php

namespace App\Http\Controllers\CMT;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Depistage;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDF;

class DepistagesController extends Controller
{
    public function showSituationDepistage(Request $request)
    {
        Carbon::setLocale('fr');
        $cmts = CMT::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $current_week = Carbon::now()->weekOfYear;
        $count = 0;
        $situation = '';
        $code = '';
        $months = [];

        if (isset($_GET['cmt']) || isset($_GET['week']) || isset($_GET['month']) || isset($_GET['year'])) {
            $cmt = '';
            $start = Carbon::parse('01-03-2020')->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');

            if ($request->filled('cmt')) {
                $cmt = $_GET['cmt'];
                $count = 1;
                $code_cmt = CMT::find($cmt);
                $code = 'du CMT-' . $code_cmt->code_cmt;
            }

            if ($request->filled('year')) {
                $start = Carbon::createFromDate($_GET['year'])
                    ->startOfYear()
                    ->format('Y-m-d');
                $end = Carbon::createFromDate($_GET['year'])
                    ->endOfYear()
                    ->format('Y-m-d');
                $year = $_GET['year'];
                $months = [$year . '-01', $year . '-02', $year . '-03', $year . '-04', $year . '-05', $year . '-06', $year . '-07', $year . '-08', $year . '-09', $year . '-10', $year . '-11', $year . '-12'];


                $situation = 'annuel ' . $year;

                $report_total = [];
                $total_depistatges = Depistage::select(['couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as total')])
                    ->whereBetween('date_test', [$start, $end])
                    ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                    ->groupBy('month')
                    ->groupBy('couverture')
                    ->get();

                $total_depistatges->each(function ($item) use (&$report_total) {
                    $report_total[$item->month][$item->couverture] = [
                        'total' => $item->total,
                    ];
                });
                $report = [];
                $depistages = Depistage::select(['type_test', 'couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as depistage')])
                    ->whereBetween('date_test', [$start, $end])
                    ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                    ->groupBy('type_test')
                    ->groupBy('month')
                    ->groupBy('couverture')
                    ->get();

                $depistages->each(function ($item) use (&$report) {
                    $report[$item->month][$item->type_test][$item->couverture] = [
                        'depistage' => $item->depistage,
                    ];
                });

                $report_total_positif = [];
                $total_depistatges_positif = Depistage::select(['couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as total_positif')])
                    ->whereBetween('date_test', [$start, $end])
                    ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                    ->where('resultat_test', 'Positif')
                    ->groupBy('month')
                    ->groupBy('couverture')
                    ->get();

                $total_depistatges_positif->each(function ($item) use (&$report_total_positif) {
                    $report_total_positif[$item->month][$item->couverture] = [
                        'total_positif' => $item->total_positif,
                    ];
                });
                $report_positif = [];
                $depistages_positif = Depistage::select(['type_test', 'couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as depistage_positif')])
                    ->whereBetween('date_test', [$start, $end])
                    ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                    ->where('resultat_test', 'Positif')
                    ->groupBy('type_test')
                    ->groupBy('month')
                    ->groupBy('couverture')
                    ->get();

                $depistages_positif->each(function ($item) use (&$report_positif) {
                    $report_positif[$item->month][$item->type_test][$item->couverture] = [
                        'depistage_positif' => $item->depistage_positif,
                    ];
                });

                $types_test = Depistage::select('type_test')->distinct()->get();
                $couvertures = ['O', 'ST'];
                $count = 2;

                return view('cmt.depistage.situationDepistage', compact('report', 'months', 'report_positif', 'report_total', 'report_total_positif', 'types_test', 'couvertures', 'count', 'cmts', 'current_year', 'current_month', 'current_week', 'situation', 'code'));
            }

            if ($request->filled('month')) {
                $start = Carbon::createFromDate($_GET['month'])
                    ->startOfMonth()
                    ->format('Y-m-d');
                $end = Carbon::createFromDate($_GET['month'])
                    ->endOfMonth()
                    ->format('Y-m-d');
                $month = Carbon::createFromDate($_GET['month'])->format('m-Y');
                $situation = 'mensuel ' . $month;
                $count = 1;
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
                $situation = 'hebdomadaire du ' . $s . ' au ' . $e;
                $count = 1;
            }

            //couverture SH
            $sh_pcr = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'PCR')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_pcr_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'PCR')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_antigenique = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'Antigénique')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_antigenique_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'Antigénique')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_serologique = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'Sérologique')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_serologique_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'Sérologique')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_scanner = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'Scanner')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_scanner_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('type_test', 'Scanner')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_depistage = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $sh_depistage_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'O')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            //couverture ST
            $st_pcr = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'PCR')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_pcr_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'PCR')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_antigenique = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'Antigénique')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_antigenique_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'Antigénique')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_serologique = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'Sérologique')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_serologique_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'Sérologique')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_scanner = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'Scanner')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_scanner_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('type_test', 'Scanner')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_depistage = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            $st_depistage_pos = Depistage::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                ->where('couverture', 'ST')
                ->where('resultat_test', 'Positif')
                ->whereBetween('date_test', [$start, $end])
                ->count();

            return view('cmt.depistage.situationDepistage', [
                'sh_pcr' => $sh_pcr,
                'sh_pcr_pos' => $sh_pcr_pos,
                'sh_antigenique' => $sh_antigenique,
                'sh_antigenique_pos' => $sh_antigenique_pos,
                'sh_serologique' => $sh_serologique,
                'sh_serologique_pos' => $sh_serologique_pos,
                'sh_scanner' => $sh_scanner,
                'sh_scanner_pos' => $sh_scanner_pos,
                'sh_depistage' => $sh_depistage,
                'sh_depistage_pos' => $sh_depistage_pos,
                'st_pcr' => $st_pcr,
                'st_pcr_pos' => $st_pcr_pos,
                'st_antigenique' => $st_antigenique,
                'st_antigenique_pos' => $st_antigenique_pos,
                'st_serologique' => $st_serologique,
                'st_serologique_pos' => $st_serologique_pos,
                'st_scanner' => $st_scanner,
                'st_scanner_pos' => $st_scanner_pos,
                'st_depistage' => $st_depistage,
                'st_depistage_pos' => $st_depistage_pos,
                'cmts' => $cmts,
                'current_year' => $current_year,
                'current_month' => $current_month,
                'current_week' => $current_week,
                'count' => $count,
                'situation' => $situation,
                'code' => $code,
                'months' => $months,
            ]);
        }

        return view('cmt.depistage.situationDepistage', [
            'cmts' => $cmts,
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'count' => $count,
            'situation' => $situation,
            'code' => $code,
            'months' => $months,
        ]);
    }

    public function generateReporting(Request $request)
    {
        Carbon::setLocale('fr');
        $date = Carbon::now()->format('d-m-Y');

        $code = '';
        $cmt = '';
        $code_cmt = '';
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $code = 'global du ' . $s . ' au ' . $e;

        if ($request->filled('cmt')) {
            $cmt = $_GET['cmt'];
            $code_cmt = CMT::find($cmt)->code_cmt;
        }

        if ($request->filled('month') || $request->filled('week')) {
            //Reporting hebdomadaire et mensuel
            if ($request->filled('month')) {
                $start = Carbon::createFromDate($_GET['month'])
                    ->startOfMonth()
                    ->format('Y-m-d');
                $end = Carbon::createFromDate($_GET['month'])
                    ->endOfMonth()
                    ->format('Y-m-d');
                $month = Carbon::createFromDate($_GET['month'])->format('m-Y');
                $code = 'mensuel ' . $month;
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
                $code = 'hebdomadaire du ' . $s . ' au ' . $e;
            }

            // depistages
            $report_depistage = [];
            $depistatge = Depistage::select(['couverture', 'type_test', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', $cmt)
                ->groupBy('couverture')
                ->groupBy('type_test')
                ->get();

            $depistatge->each(function ($item) use (&$report_depistage) {
                $report_depistage[$item->couverture][$item->type_test] = [
                    'total' => $item->total,
                ];
            });

            // depistages positifs
            $report_depistage_pos = [];
            $depistatge_pos = Depistage::select(['couverture', 'type_test', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', $cmt)
                ->where('resultat_test', 'Positif')
                ->groupBy('couverture')
                ->groupBy('type_test')
                ->get();

            $depistatge_pos->each(function ($item) use (&$report_depistage_pos) {
                $report_depistage_pos[$item->couverture][$item->type_test] = [
                    'total' => $item->total,
                ];
            });

            // total depistages
            $report_depistage_total = [];
            $depistatge_total = Depistage::select(['couverture', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', $cmt)
                ->groupBy('couverture')
                ->get();

            $depistatge_total->each(function ($item) use (&$report_depistage_total) {
                $report_depistage_total[$item->couverture] = [
                    'total' => $item->total,
                ];
            });

            // total depistages positifs
            $report_depistage_pos_total = [];
            $depistatge_pos_total = Depistage::select(['couverture', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', $cmt)
                ->where('resultat_test', 'Positif')
                ->groupBy('couverture')
                ->get();

            $depistatge_pos_total->each(function ($item) use (&$report_depistage_pos_total) {
                $report_depistage_pos_total[$item->couverture] = [
                    'total' => $item->total,
                ];
            });

            $pdf = PDF::loadView('cmt.depistage.pdf.rapportActivitéHM', [
                'code_cmt' => $code_cmt,
                'date' => $date,
                'code' => $code,
                'report_depistage' => $report_depistage,
                'report_depistage_pos' => $report_depistage_pos,
                'report_depistage_total' => $report_depistage_total,
                'report_depistage_pos_total' => $report_depistage_pos_total,
                'code' => $code,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Rapport d\'activité ' . $code . ' CMT ' . $code_cmt . '.pdf');
        }

        //Reporting annuel
        if ($request->filled('year')) {
            $start = Carbon::createFromDate($_GET['year'])
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['year'])
                ->endOfYear()
                ->format('Y-m-d');
            $year = $_GET['year'];
            $code = 'annuel ' . $year;
            $months = [$year . '-01', $year . '-02', $year . '-03', $year . '-04', $year . '-05', $year . '-06', $year . '-07', $year . '-08', $year . '-09', $year . '-10', $year . '-11', $year . '-12'];

            $report_total = [];
            $total_depistatges = Depistage::select(['couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', 'LIKE', $cmt)
                ->groupBy('month')
                ->orderBy('month')
                ->groupBy('couverture')
                ->get();

            $total_depistatges->each(function ($item) use (&$report_total) {
                $report_total[$item->month][$item->couverture] = [
                    'total' => $item->total,
                ];
            });
            $report = [];
            $depistages = Depistage::select(['type_test', 'couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as depistage')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', 'LIKE', $cmt)
                ->groupBy('type_test')
                ->groupBy('month')
                ->orderBy('month')
                ->groupBy('couverture')
                ->get();

            $depistages->each(function ($item) use (&$report) {
                $report[$item->month][$item->type_test][$item->couverture] = [
                    'depistage' => $item->depistage,
                ];
            });

            $report_total_positif = [];
            $total_depistatges_positif = Depistage::select(['couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as total_positif')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', 'LIKE', $cmt)
                ->where('resultat_test', 'Positif')
                ->groupBy('month')
                ->orderBy('month')
                ->groupBy('couverture')
                ->get();

            $total_depistatges_positif->each(function ($item) use (&$report_total_positif) {
                $report_total_positif[$item->month][$item->couverture] = [
                    'total_positif' => $item->total_positif,
                ];
            });
            $report_positif = [];
            $depistages_positif = Depistage::select(['type_test', 'couverture', DB::raw("DATE_FORMAT(date_test, '%Y-%m') as month"), DB::raw('COUNT(id) as depistage_positif')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', 'LIKE', $cmt)
                ->where('resultat_test', 'Positif')
                ->groupBy('type_test')
                ->groupBy('month')
                ->orderBy('month')
                ->groupBy('couverture')
                ->get();

            $depistages_positif->each(function ($item) use (&$report_positif) {
                $report_positif[$item->month][$item->type_test][$item->couverture] = [
                    'depistage_positif' => $item->depistage_positif,
                ];
            });

            $types_test = $depistages
                ->pluck('type_test')
                ->sortBy('type_test')
                ->unique();
            $couvertures = ['O', 'ST'];


            $pdf = PDF::loadView('cmt.depistage.pdf.rapportActivitéAnnuel', [
                'report' => $report,
                'report_positif' => $report_positif,
                'report_total' => $report_total,
                'report_total_positif' => $report_total_positif,
                'types_test' => $types_test,
                'couvertures' => $couvertures,
                'code_cmt' => $code_cmt,
                'date' => $date,
                'code' => $code,
                'months' => $months,
            ])->setPaper('a4', 'landscape');
            return $pdf->download('Rapport d\'activité annuel CMT-' . $code_cmt . '.pdf');
        }

        // reporting global
        // depistages
        $report_depistage = [];
        $depistatge = Depistage::select(['couverture', 'type_test', DB::raw('COUNT(id) as total')])
            ->whereBetween('date_test', [$start, $end])
            ->where('cmt_id', $cmt)
            ->groupBy('couverture')
            ->groupBy('type_test')
            ->get();

        $depistatge->each(function ($item) use (&$report_depistage) {
            $report_depistage[$item->couverture][$item->type_test] = [
                'total' => $item->total,
            ];
        });

        // depistages positifs
        $report_depistage_pos = [];
        $depistatge_pos = Depistage::select(['couverture', 'type_test', DB::raw('COUNT(id) as total')])
            ->whereBetween('date_test', [$start, $end])
            ->where('cmt_id', $cmt)
            ->where('resultat_test', 'Positif')
            ->groupBy('couverture')
            ->groupBy('type_test')
            ->get();

        $depistatge_pos->each(function ($item) use (&$report_depistage_pos) {
            $report_depistage_pos[$item->couverture][$item->type_test] = [
                'total' => $item->total,
            ];
        });

        // total depistages
        $report_depistage_total = [];
        $depistatge_total = Depistage::select(['couverture', DB::raw('COUNT(id) as total')])
            ->whereBetween('date_test', [$start, $end])
            ->where('cmt_id', $cmt)
            ->groupBy('couverture')
            ->get();

        $depistatge_total->each(function ($item) use (&$report_depistage_total) {
            $report_depistage_total[$item->couverture] = [
                'total' => $item->total,
            ];
        });

        // total depistages positifs
        $report_depistage_pos_total = [];
        $depistatge_pos_total = Depistage::select(['couverture', DB::raw('COUNT(id) as total')])
            ->whereBetween('date_test', [$start, $end])
            ->where('cmt_id', $cmt)
            ->where('resultat_test', 'Positif')
            ->groupBy('couverture')
            ->get();

        $depistatge_pos_total->each(function ($item) use (&$report_depistage_pos_total) {
            $report_depistage_pos_total[$item->couverture] = [
                'total' => $item->total,
            ];
        });

        $pdf = PDF::loadView('cmt.depistage.pdf.rapportActivitéHM', [
            'code_cmt' => $code_cmt,
            'date' => $date,
            'code' => $code,
            'report_depistage' => $report_depistage,
            'report_depistage_pos' => $report_depistage_pos,
            'report_depistage_total' => $report_depistage_total,
            'report_depistage_pos_total' => $report_depistage_pos_total,
            'code' => $code,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Rapport d\'activité ' . $code . ' CMT ' . $code_cmt . '.pdf');
    }
}
