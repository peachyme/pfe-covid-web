<?php

namespace App\Http\Controllers\CMT;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class VaccinationsController extends Controller
{
    public function showSituationVaccination(Request $request)
    {
        $start = Carbon::now()->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $today = Carbon::now()->format('d-m-Y');
        $situation = "Situation du jour le " . $today . " : ";
        $vaccinations = Vaccination::whereBetween('date_vaccination', [$start, $end])->orderBy('date_vaccination')->paginate(5);
        $cmts = CMT::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $current_week = Carbon::now()->weekOfYear;

        if ((isset($_GET['cmt'])) || (isset($_GET['couverture'])) || (isset($_GET['week'])) || (isset($_GET['month'])) || (isset($_GET['year']))) {
            $start = Carbon::now()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
            $cmt = "";
            $couverture = "";

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
            if ($request->filled('month')) {
                $month = Carbon::parse($_GET['month'])->month;
                $year = Carbon::parse($_GET['month'])->year;
            }
            if ($request->filled('year')) {
                $year = $_GET['year'];
            }
            if ($request->filled('couverture')) {
                $couverture = $_GET['couverture'];
            }

            if ($couverture == 'ST') {
                $vaccinations = Vaccination::whereNotNull('sousTraitant_id')
                    ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                    ->whereBetween('date_vaccination', [$start, $end])
                    ->orderBy('date_vaccination')
                    ->paginate(5);
            } else {
                if ($couverture == 'O') {
                    $vaccinations = Vaccination::whereNotNull('organique_id')
                        ->where('cmt_id', 'LIKE', '%' . $cmt . '%')
                        ->whereBetween('date_vaccination', [$start, $end])
                        ->orderBy('date_vaccination')
                        ->paginate(5);
                } else {
                    $vaccinations = Vaccination::where('cmt_id', 'LIKE', '%' . $cmt . '%')
                        ->whereBetween('date_vaccination', [$start, $end])
                        ->orderBy('date_vaccination')
                        ->paginate(5);
                }
            }
            $vaccinations->appends($request->all());
        } else {
            return view('cmt.vaccination.situationVaccination', [
                'vaccinations' => $vaccinations,
                'cmts' => $cmts,
                'current_year' => $current_year,
                'current_month' => $current_month,
                'current_week' => $current_week,
                'situation' => $situation,
            ]);
        }

        return view('cmt.vaccination.situationVaccination', [
            'vaccinations' => $vaccinations,
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
        $start = Carbon::now()->parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $code = "global du " . $s . " au " . $e;

        $cmt = "";
        $code_cmt = "";

        if ($request->filled('year')) {
            $start = Carbon::createFromDate($_GET['year'])->startOfYear()->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['year'])->endOfYear()->format('Y-m-d');
            $year = $_GET['year'];
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

        if ($request->filled('cmt')) {
            $cmt = $_GET['cmt'];
            $code_cmt = CMT::find($cmt)->code_cmt;

            $vaccinations = Vaccination::where('cmt_id', $cmt)
                ->whereBetween('date_vaccination', [$start, $end])
                ->get();

            $pdf = PDF::loadView('cmt.vaccination.pdf.etatNominatifCMT', [
                'vaccinations' => $vaccinations,
                'code_cmt' => $code_cmt,
                'date' => $date,
                'code' => $code,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Reporting ' . $code . ' de la situation de l\'opération de vaccination du CMT ' . $code_cmt . '.pdf');
        } else {

            $vaccinations_siege = Vaccination::where('cmt_id', 1)
                ->whereBetween('date_vaccination', [$start, $end])
                ->get();

            $nb_siege = $vaccinations_siege->count();

            $vaccinations_os = Vaccination::where('cmt_id', 2)
                ->whereBetween('date_vaccination', [$start, $end])
                ->get();

            $nb_os = $vaccinations_os->count();

            $vaccinations_rds = Vaccination::where('cmt_id', 3)
                ->whereBetween('date_vaccination', [$start, $end])
                ->get();

            $nb_rds = $vaccinations_rds->count();

            $pdf = PDF::loadView('cmt.vaccination.pdf.etatNominatif3CMT', [
                'vaccinations_siege' => $vaccinations_siege,
                'vaccinations_os' => $vaccinations_os,
                'vaccinations_rds' => $vaccinations_rds,
                'date' => $date,
                'code' => $code,
                'nb_siege' => $nb_siege,
                'nb_os' => $nb_os,
                'nb_rds' => $nb_rds,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('Reporting ' . $code . ' de la situation de l\'opération de vaccination des 3 CMT.pdf');
        }
    }

    public function generateReportingHebdomadaire()
    {

        Carbon::setLocale('fr');
        $date = Carbon::now()->format('d-m-Y');
        $start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $s = Carbon::now()->startOfWeek()->format('d-m-Y');
        $end = Carbon::now()->endOfWeek()->format('Y-m-d');
        $e = Carbon::now()->endOfWeek()->format('d-m-Y');
        $code = "hebdomadaire du " . $s . " au " . $e;

        $vaccinations_siege = Vaccination::where('cmt_id', 1)
            ->whereBetween('date_vaccination', [$start, $end])
            ->get();

        $nb_siege = $vaccinations_siege->count();

        $vaccinations_os = Vaccination::where('cmt_id', 2)
            ->whereBetween('date_vaccination', [$start, $end])
            ->get();

        $nb_os = $vaccinations_os->count();

        $vaccinations_rds = Vaccination::where('cmt_id', 3)
            ->whereBetween('date_vaccination', [$start, $end])
            ->get();

        $nb_rds = $vaccinations_rds->count();

        $pdf = PDF::loadView('cmt.vaccination.pdf.etatNominatif3CMTHebdomadaire', [
            'vaccinations_siege' => $vaccinations_siege,
            'vaccinations_os' => $vaccinations_os,
            'vaccinations_rds' => $vaccinations_rds,
            'date' => $date,
            'code' => $code,
            'nb_siege' => $nb_siege,
            'nb_os' => $nb_os,
            'nb_rds' => $nb_rds,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Reporting ' . $code . ' de la situation de l\'opération de vaccination des 3 CMT.pdf');
    }
}
