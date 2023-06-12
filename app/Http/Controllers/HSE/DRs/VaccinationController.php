<?php

namespace App\Http\Controllers\HSE\DRs;

use Carbon\Carbon;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeOrganique;
use App\Models\SousTraitant;
use App\Models\Vaccination;
use Illuminate\Support\Facades\DB;
use PDF;

class VaccinationController extends Controller
{
    public function showSituationVaccination(Request $request)
    {
        $regions = Region::paginate(5);
        $regions_select = Region::all();
        $current_week = Carbon::now()->weekOfYear;
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $month = Carbon::now()->format('m-Y');
        $effectif_st = '';
        $total_vacc_st = '';
        $cumul_st = '';
        $pourcentage_st = '';
        $report_effectif_st = '';
        $report_total_vacc_st = '';
        $report_cumul_st = '';
        $report_pourcentage_st = '';
        $effectif_agents = '';
        $total_vacc_agents = '';
        $cumul_agents = '';
        $pourcentage_agents = '';
        $report_effectif_agents = '';
        $report_total_vacc_agents = '';
        $report_cumul_agents = '';
        $report_pourcentage_agents = '';

        $start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $end = Carbon::now()->endOfWeek()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $situation = "hebdomadaire du " . $s . " au " . $e;
        $region = "";

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

        if ($request->filled('region')) {
            $region = $_GET['region'];
            $no_region = false;
            $code_region = Region::find($region)->code_region;
            if ($request->filled('couverture')) {
                $couverture = $_GET['couverture'];
            } else {
                $couverture = 'O';
            }
            if ($couverture == 'O') {
                $couv = 'O';
                $effectif_agents = EmployeOrganique::where('region_id', $region)->count();
                $total_vacc_agents = Vaccination::whereNotNull('organique_id')
                    ->where('region_id', $region)
                    ->where('dose_vaccination', 1)
                    ->whereBetween('date_vaccination', [$start, $end])
                    ->count();
                $cumul_agents = Vaccination::whereNotNull('organique_id')
                    ->where('region_id', $region)
                    ->where('dose_vaccination', 1)
                    ->count();
                $pourcentage_agents = round((($cumul_agents * 100) / $effectif_agents), 2);
            } else {
                if ($couverture == 'ST') {
                    $couv = 'ST';
                    $effectif_st = SousTraitant::count();
                    $total_vacc_st = Vaccination::whereNotNull('sousTraitant_id')
                        ->where('region_id', $region)
                        ->where('dose_vaccination', 1)
                        ->whereBetween('date_vaccination', [$start, $end])
                        ->count();
                    $cumul_st = Vaccination::whereNotNull('sousTraitant_id')
                        ->where('region_id', $region)
                        ->where('dose_vaccination', 1)
                        ->count();
                    $pourcentage_st = round((($cumul_st * 100) / $effectif_st), 2);
                }
            }
            $regions->appends($request->all());
            return view('HSE.DRS.vaccination.situationVaccination', [
                'effectif_agents' => $effectif_agents,
                'total_vacc_agents' => $total_vacc_agents,
                'cumul_agents' => $cumul_agents,
                'pourcentage_agents' => $pourcentage_agents,
                'effectif_st' => $effectif_st,
                'total_vacc_st' => $total_vacc_st,
                'cumul_st' => $cumul_st,
                'pourcentage_st' => $pourcentage_st,
                'current_year' => $current_year,
                'current_month' => $current_month,
                'current_week' => $current_week,
                'situation' => $situation,
                'regions' => $regions,
                'regions_select' => $regions_select,
                'no_region' => $no_region,
                'code_region' => $code_region,
                'couv' => $couv,
            ]);
        } else {

            $no_region = true;
            if ($request->filled('couverture')) {
                $couverture = $_GET['couverture'];
            } else {
                $couverture = 'O';
            }
            if ($couverture == 'O') {
                $couv = 'O';
                // effectif agents
                $report_effectif_agents = [];
                $effectif_agents = EmployeOrganique::select([
                    'region_id',
                    DB::raw('COUNT(id) as total')
                ])
                    ->groupBy('region_id')
                    ->get();

                $effectif_agents->each(function ($item) use (&$report_effectif_agents) {
                    $report_effectif_agents[$item->region_id] = [
                        'total' => $item->total,
                    ];
                });
                //total vacc
                $report_total_vacc_agents = [];
                $total_vacc_agents = Vaccination::select([
                    'region_id',
                    DB::raw('COUNT(vaccinations.id) as total')
                ])
                    ->where('dose_vaccination', 1)
                    ->whereBetween('date_vaccination', [$start, $end])
                    ->whereNotNull('organique_id')
                    ->groupBy('region_id')
                    ->get();

                $total_vacc_agents->each(function ($item) use (&$report_total_vacc_agents) {
                    $report_total_vacc_agents[$item->region_id] = [
                        'total' => $item->total,
                    ];
                });
                //cumul agents
                $report_cumul_agents = [];
                $cumul_agents = Vaccination::select([
                    'region_id',
                    DB::raw('COUNT(vaccinations.id) as total')
                ])
                    ->where('dose_vaccination', 1)
                    ->whereNotNull('organique_id')
                    ->groupBy('region_id')
                    ->get();

                $cumul_agents->each(function ($item) use (&$report_cumul_agents) {
                    $report_cumul_agents[$item->region_id] = [
                        'total' => $item->total,
                    ];
                });

                // % agents
                $report_pourcentage_agents = [];
                foreach ($regions_select as $region) {
                    $report_pourcentage_agents[$region->id]['total'] = round(((($report_cumul_agents[$region->id]['total'] ?? 0) * 100) / ($report_effectif_agents[$region->id]['total'] ?? 0)), 2);
                }
            } else {
                if ($couverture == 'ST') {
                    $couv = 'ST';
                    // effectif agents
                    $report_effectif_st = [];
                    $effectif_st = SousTraitant::select([
                        'region_id',
                        DB::raw('COUNT(id) as total')
                    ])
                        ->groupBy('region_id')
                        ->get();

                    $effectif_st->each(function ($item) use (&$report_effectif_st) {
                        $report_effectif_st[$item->region_id] = [
                            'total' => $item->total,
                        ];
                    });
                    //total vacc
                    $report_total_vacc_st = [];
                    $total_vacc_st = Vaccination::select([
                        'region_id',
                        DB::raw('COUNT(vaccinations.id) as total')
                    ])
                        ->where('dose_vaccination', 1)
                        ->whereBetween('date_vaccination', [$start, $end])
                        ->whereNotNull('sousTraitant_id')
                        ->groupBy('region_id')
                        ->get();

                    $total_vacc_st->each(function ($item) use (&$report_total_vacc_st) {
                        $report_total_vacc_st[$item->region_id] = [
                            'total' => $item->total,
                        ];
                    });
                    //cumul agents
                    $report_cumul_st = [];
                    $cumul_st = Vaccination::select([
                        'region_id',
                        DB::raw('COUNT(vaccinations.id) as total')
                    ])
                        ->where('dose_vaccination', 1)
                        ->whereNotNull('sousTraitant_id')
                        ->groupBy('region_id')
                        ->get();

                    $cumul_st->each(function ($item) use (&$report_cumul_st) {
                        $report_cumul_st[$item->region_id] = [
                            'total' => $item->total,
                        ];
                    });

                    // % agents
                    $report_pourcentage_st = [];
                    foreach ($regions_select as $region) {
                        $report_pourcentage_st[$region->id]['total'] = round(((($report_cumul_st[$region->id]['total'] ?? 0) * 100) / ($report_effectif_st[$region->id]['total'] ?? 0)), 2);
                    }
                }
            }
        }

        $regions->appends($request->all());
        return view('HSE.DRS.vaccination.situationVaccination', [
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'regions' => $regions,
            'regions_select' => $regions_select,
            'no_region' => $no_region,
            'situation' => $situation,
            'report_effectif_agents' => $report_effectif_agents,
            'report_total_vacc_agents' => $report_total_vacc_agents,
            'report_cumul_agents' => $report_cumul_agents,
            'report_pourcentage_agents' => $report_pourcentage_agents,
            'report_effectif_st' => $report_effectif_st,
            'report_total_vacc_st' => $report_total_vacc_st,
            'report_cumul_st' => $report_cumul_st,
            'report_pourcentage_st' => $report_pourcentage_st,
            'couv' => $couv,
        ]);
    }

    public function generateReporting(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::now()->format('d-m-Y');
        $regions = Region::all();
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('Y-m');
        $month = Carbon::now()->format('m-Y');
        $current_week = Carbon::now()->weekOfYear;


        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $s = Carbon::createFromDate($start)->format('d-m-Y');
        $e = Carbon::createFromDate($end)->format('d-m-Y');
        $situation = "global du " . $s . " au " . $e;
        $region = "";

        if ($request->filled('year')) {
            $start = Carbon::createFromDate($_GET['year'])->startOfYear()->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['year'])->endOfYear()->format('Y-m-d');
            $situation = "annuel " . $current_year;
        }
        if ($request->filled('month')) {
            $start = Carbon::createFromDate($_GET['month'])->startOfMonth()->format('Y-m-d');
            $end = Carbon::createFromDate($_GET['month'])->endOfMonth()->format('Y-m-d');
            $situation = "mensuel " . $month;
        }
        if ($request->filled('week')) {
            $start = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('Y-m-d');
            $s = Carbon::createFromDate($_GET['week'])->startOfWeek()->format('d-m-Y');
            $end = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('Y-m-d');
            $e = Carbon::createFromDate($_GET['week'])->endOfWeek()->format('d-m-Y');
            $situation = "hebdomadaire du " . $s . " au " . $e;
        }

        // effectif agents
        $report_effectif_agents = [];
        $effectif_agents = EmployeOrganique::select([
            'region_id',
            DB::raw('COUNT(id) as total')
        ])
            ->groupBy('region_id')
            ->get();

        $effectif_agents->each(function ($item) use (&$report_effectif_agents) {
            $report_effectif_agents[$item->region_id] = [
                'total' => $item->total,
            ];
        });
        //total vacc
        $report_total_vacc_agents = [];
        $total_vacc_agents = Vaccination::select([
            'region_id',
            DB::raw('COUNT(vaccinations.id) as total')
        ])
            ->where('dose_vaccination', 1)
            ->whereBetween('date_vaccination', [$start, $end])
            ->whereNotNull('organique_id')
            ->groupBy('region_id')
            ->get();

        $total_vacc_agents->each(function ($item) use (&$report_total_vacc_agents) {
            $report_total_vacc_agents[$item->region_id] = [
                'total' => $item->total,
            ];
        });
        //cumul agents
        $report_cumul_agents = [];
        $cumul_agents = Vaccination::select([
            'region_id',
            DB::raw('COUNT(vaccinations.id) as total')
        ])
            ->where('dose_vaccination', 1)
            ->whereNotNull('organique_id')
            ->groupBy('region_id')
            ->get();

        $cumul_agents->each(function ($item) use (&$report_cumul_agents) {
            $report_cumul_agents[$item->region_id] = [
                'total' => $item->total,
            ];
        });

        // % agents
        $report_pourcentage_agents = [];
        foreach ($regions as $region) {
            $report_pourcentage_agents[$region->id]['total'] = round(((($report_cumul_agents[$region->id]['total'] ?? 0) * 100) / ($report_effectif_agents[$region->id]['total'] ?? 0)), 2);
        }

        // total agents regions
        $total_effectif_agents = EmployeOrganique::count();
        //total vacc regions
        $total_vacc_agents_regions = Vaccination::where('dose_vaccination', 1)
            ->whereBetween('date_vaccination', [$start, $end])
            ->whereNotNull('organique_id')
            ->count();
        //cumul agents regions
        $cumul_vacc_agents_regions = Vaccination::where('dose_vaccination', 1)
            ->whereNotNull('organique_id')
            ->count();
        // % agents regions
        $pourcentage_agents_regions = round(((($cumul_vacc_agents_regions * 100) / $total_effectif_agents )), 2);

        // Sous Traitants
        // effectif sous traitants
        $report_effectif_st = [];
        $effectif_st = SousTraitant::select([
            'region_id',
            DB::raw('COUNT(id) as total')
        ])
            ->groupBy('region_id')
            ->get();

        $effectif_st->each(function ($item) use (&$report_effectif_st) {
            $report_effectif_st[$item->region_id] = [
                'total' => $item->total,
            ];
        });
        //total vacc
        $report_total_vacc_st = [];
        $total_vacc_st = Vaccination::select([
            'region_id',
            DB::raw('COUNT(vaccinations.id) as total')
        ])
            ->where('dose_vaccination', 1)
            ->whereBetween('date_vaccination', [$start, $end])
            ->whereNotNull('sousTraitant_id')
            ->groupBy('region_id')
            ->get();

        $total_vacc_st->each(function ($item) use (&$report_total_vacc_st) {
            $report_total_vacc_st[$item->region_id] = [
                'total' => $item->total,
            ];
        });
        //cumul agents
        $report_cumul_st = [];
        $cumul_st = Vaccination::select([
            'region_id',
            DB::raw('COUNT(vaccinations.id) as total')
        ])
            ->where('dose_vaccination', 1)
            ->whereNotNull('sousTraitant_id')
            ->groupBy('region_id')
            ->get();

        $cumul_st->each(function ($item) use (&$report_cumul_st) {
            $report_cumul_st[$item->region_id] = [
                'total' => $item->total,
            ];
        });

        // % agents
        $report_pourcentage_st = [];
        foreach ($regions as $region) {
            $report_pourcentage_st[$region->id]['total'] = round(((($report_cumul_st[$region->id]['total'] ?? 0) * 100) / ($report_effectif_st[$region->id]['total'] ?? 0)), 2);
        }

        // total sous traitans regions
        $total_effectif_st = SousTraitant::count();
        //total vacc regions sous traitants
        $total_vacc_st_regions = Vaccination::where('dose_vaccination', 1)
            ->whereBetween('date_vaccination', [$start, $end])
            ->whereNotNull('sousTraitant_id')
            ->count();
        //cumul sous traitants regions
        $cumul_vacc_st_regions = Vaccination::where('dose_vaccination', 1)
            ->whereNotNull('sousTraitant_id')
            ->count();
        // %  sous traitants regions
        $pourcentage_st_regions = round(((($cumul_vacc_st_regions * 100) / $total_effectif_st )), 2);


        $pdf = PDF::loadView('HSE.DRs.vaccination.pdf.reportingSitesDP', [
            'current_year' => $current_year,
            'current_month' => $current_month,
            'current_week' => $current_week,
            'situation' => $situation,
            'report_effectif_agents' => $report_effectif_agents,
            'report_total_vacc_agents' => $report_total_vacc_agents,
            'report_cumul_agents' => $report_cumul_agents,
            'report_pourcentage_agents' => $report_pourcentage_agents,
            'report_effectif_st' => $report_effectif_st,
            'report_total_vacc_st' => $report_total_vacc_st,
            'report_cumul_st' => $report_cumul_st,
            'report_pourcentage_st' => $report_pourcentage_st,
            'regions' => $regions,
            'date' => $date,
            'total_effectif_agents' => $total_effectif_agents,
            'total_vacc_agents_regions' => $total_vacc_agents_regions,
            'cumul_vacc_agents_regions' => $cumul_vacc_agents_regions,
            'pourcentage_agents_regions' => $pourcentage_agents_regions,
            'total_effectif_st' => $total_effectif_st,
            'total_vacc_st_regions' => $total_vacc_st_regions,
            'cumul_vacc_st_regions' => $cumul_vacc_st_regions,
            'pourcentage_st_regions' => $pourcentage_st_regions
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Reporting ' . $situation . ' de la situation de l\'opération de vaccination des sites DP.pdf');
    }
}
