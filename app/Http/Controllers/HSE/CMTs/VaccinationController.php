<?php

namespace App\Http\Controllers\HSE\CMTs;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDF;

class VaccinationController extends Controller
{
    public function showSituationVaccination(Request $request)
    {
        $start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end = Carbon::now()->endOfWeek()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $situation = "hebdomadaire du " . $s . " au " . $e;
        $vaccinations = Vaccination::whereBetween('date_vaccination', [$start, $end])->paginate(5);
        $cmts = CMT::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $current_week = Carbon::now()->weekOfYear;
        $date = Carbon::now()->format('d-m-Y');

        if ((isset($_GET['cmt'])) || (isset($_GET['couverture'])) || (isset($_GET['week'])) || (isset($_GET['month'])) || (isset($_GET['year']))) {

            $cmt = "";
            $couverture = "";

            $start = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->endOfWeek()->format('Y-m-d');
            $s = Carbon::createFromDate($start)->format('d-m-Y');
            $e = Carbon::createFromDate($end)->format('d-m-Y');
            $situation = "hebdomadaire du " . $s . " au " . $e;

            if ($request->filled('year')) {
                $start = Carbon::createFromDate($_GET['year'])->startOfYear()->format('Y-m-d');
                $end = Carbon::createFromDate($_GET['year'])->endOfYear()->format('Y-m-d');
                $year = Carbon::createFromDate($_GET['year'])->format('Y');
                $situation = "annuel " . $year;
            }
            if ($request->filled('month')) {
                $start = Carbon::createFromDate($_GET['month'])->startOfMonth()->format('Y-m-d');
                $end = Carbon::createFromDate($_GET['month'])->endOfMonth()->format('Y-m-d');
                $month = Carbon::createFromDate($_GET['month'])->format('m-Y');
                $situation = "mensuel " . $month;
            }
            if ($request->filled('week')) {
                $start = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('Y-m-d');
                $s = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('d-m-Y');
                $end = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('Y-m-d');
                $e = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('d-m-Y');
                $situation = "hebdomadaire du " . $s . " au " . $e;
            }
            if ($request->filled('cmt')) {
                $cmt = $_GET['cmt'];
            }
            if ($request->filled('couverture')) {
                $couverture = $_GET['couverture'];
            }

            if ($couverture == 'ST') {
                $vaccinations = Vaccination::whereNotNull('sousTraitant_id')
                    ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                    ->whereBetween('date_vaccination', [$start, $end])
                    ->paginate(5);
            } else {
                if ($couverture == 'O') {
                    $vaccinations = Vaccination::whereNotNull('organique_id')
                        ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                        ->whereBetween('date_vaccination', [$start, $end])
                        ->paginate(5);
                } else {
                    $vaccinations = Vaccination::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                        ->whereBetween('date_vaccination', [$start, $end])
                        ->paginate(5);
                }
            }
            $vaccinations->appends($request->all());
        } else {
            return view('HSE.CMTs.vaccination.situationVaccination', [
                'vaccinations' => $vaccinations,
                'cmts' => $cmts,
                'current_year' => $current_year,
                'current_month' => $current_month,
                'current_week' => $current_week,
                'situation' => $situation,
                'date' => $date
            ]);
        }

        return view('HSE.CMTs.vaccination.situationVaccination', [
            'vaccinations' => $vaccinations,
            'cmts' => $cmts,
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'situation' => $situation,
            'date' => $date
        ]);
    }

    public function generateReporting(Request $request)
    {
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $code = "global du " . $s . " au " . $e;
        $cmts = CMT::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $current_week = Carbon::now()->weekOfYear;
        $date = Carbon::now()->format('d-m-Y');

        if ($request->filled('year')) {
            $start = Carbon::createFromDate($_GET['year'])->startOfYear()->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['year'])->endOfYear()->format('Y-m-d');
            $year = Carbon::createFromDate($_GET['year'])->format('Y');
            $code = "annuel " . $year;
        }
        if ($request->filled('month')) {
            $start = Carbon::createFromDate($_GET['month'])->startOfMonth()->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['month'])->endOfMonth()->format('Y-m-d');
            $month = Carbon::createFromDate($_GET['month'])->format('m-Y');
            $code = "mensuel " . $month;
        }
        if ($request->filled('week')) {
            $start = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('Y-m-d');
            $s = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('d-m-Y');
            $end = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('Y-m-d');
            $e = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('d-m-Y');
            $code = "hebdomadaire du " . $s . " au " . $e;
        }

        //tableau 3 CMT
        $report_vacc_sh = [];
        $vacc_sh = Vaccination::select([
            'cmt_id', 'dose_vaccination',
            DB::raw('COUNT(id) as total')
        ])
        ->whereBetween('date_vaccination', [$start, $end])
        ->whereNotNull('organique_id')
        ->groupBy('cmt_id')
        ->groupBy('dose_vaccination')
        ->get();

        $vacc_sh->each(function ($item) use (&$report_vacc_sh) {
            $report_vacc_sh[$item->cmt_id][$item->dose_vaccination] = [
                'total' => $item->total,
            ];
        });

        $report_vacc_st = [];
        $vacc_st = Vaccination::select([
            'cmt_id', 'dose_vaccination',
            DB::raw('COUNT(id) as total')
        ])
        ->whereBetween('date_vaccination', [$start, $end])
        ->whereNotNull('sousTraitant_id')
        ->groupBy('cmt_id')
        ->groupBy('dose_vaccination')
        ->get();

        $vacc_st->each(function ($item) use (&$report_vacc_st) {
            $report_vacc_st[$item->cmt_id][$item->dose_vaccination] = [
                'total' => $item->total,
            ];
        });

        // each CMT
        $vaccinations_siege = Vaccination::where('cmt_id', 1)
                    ->whereBetween('date_vaccination', [$start, $end])
                    ->get();

        $vaccinations_os = Vaccination::where('cmt_id', 2)
                    ->whereBetween('date_vaccination', [$start, $end])
                    ->get();

        $vaccinations_rds = Vaccination::where('cmt_id', 3)
                    ->whereBetween('date_vaccination', [$start, $end])
                    ->get();

        $pdf = PDF::loadView('HSE.CMTs.vaccination.pdf.etatNominatif3CMT', [
            'cmts' => $cmts,
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'code' => $code,
            'date' => $date,
            'report_vacc_sh' => $report_vacc_sh,
            'report_vacc_st' => $report_vacc_st,
            'vaccinations_siege' => $vaccinations_siege,
            'vaccinations_os' => $vaccinations_os,
            'vaccinations_rds' => $vaccinations_rds,
            'date' => $date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Reporting ' . $code . ' de la situation de l\'opération de la vaccination des 3 CMT.pdf');
    }
}
