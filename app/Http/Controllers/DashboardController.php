<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Zone;
use Dompdf\Css\Color;
use App\Models\Region;
use App\Charts\AgeChart;
use App\Charts\CMTChart;
use App\Charts\DoseChart;
use App\Charts\SexeChart;
use App\Models\Depistage;
use App\Charts\CovidChart;
use App\Charts\ZonesChart;
use App\Charts\VaccinChart;
use App\Models\Vaccination;
use App\Models\Consultation;
use App\Models\SousTraitant;
use Illuminate\Http\Request;
use App\Charts\RegionalChart;
use App\Charts\CovidTestChart;
use App\Charts\DepistageChart;
use App\Charts\StructureChart;
use App\Charts\CouvertureChart;
use App\Models\EmployeOrganique;
use Illuminate\Support\Facades\DB;
use App\Charts\CouvertureLineChart;
use App\Charts\VaccinationDoughnutChart;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $type)
    {
        $consultations = Consultation::all();

        $nb_employes = EmployeOrganique::count() + SousTraitant::count();

        //*******************************************************CARDS*************************************************************
        // cas positifs total :
        $cas_positifs_total =
            Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->where('resultat_test', 'Positif')
                ->count();

        // cas potifis actives last consultation where modalite != RT && evolution = null
        $cas_positif_actives =
            EmployeOrganique::whereHas('latest_consultation', function ($query) {
                $query->whereNull('evolution_maladie')->where('modalités_priseEnCharge', '<>', 'RT');
            })->count() +
            SousTraitant::whereHas('latest_consultation', function ($query) {
                $query->whereNull('evolution_maladie')->where('modalités_priseEnCharge', '<>', 'RT');
            })->count();

        // cas hospitalisés total :
        $cas_hospitalisés_total = Consultation::where('modalités_priseEnCharge', 'H')->count();

        // cas hospitalisés actives last consultation where modalite = H
        $cas_hospitalisés_actives =
            EmployeOrganique::whereHas('latest_consultation', function ($query) {
                $query->where('modalités_priseEnCharge', 'H');
            })->count() +
            SousTraitant::whereHas('latest_consultation', function ($query) {
                $query->where('modalités_priseEnCharge', 'H');
            })->count();

        // cas guéris :
        $cas_guéris = Consultation::where('evolution_maladie', 'G')->count();

        // deces
        $deces = Consultation::where('evolution_maladie', 'D')->count();

        // vaccination total :
        $vaccination_total = Vaccination::where('dose_vaccination', 1)->count();

        // vaccination %
        $vaccination_pourcentage = round(($vaccination_total * 100) / $nb_employes, 2);

        // ********************************************COVID DEFAULT LINE CHART****************************************************

        // covid chart data
        $covid_chart = new CovidChart();
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $covid_chart_data = [];
        $covid_data = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y-%m') as month"), DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->groupBy('month')
            ->get();

        $covid_data->each(function ($item) use (&$covid_chart_data) {
            $covid_chart_data[$item->month] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });

        $start_year = Carbon::parse('01-02-2020');
        $end_year = Carbon::now()->subMonth();

        // $start_year = Carbon::now()->startOfYear()->subMonth();
        // $end_year = Carbon::now()->subMonth();

        $months = [];
        while ($start_year < $end_year) {
            $months[] = $start_year->addMonth()->format('Y-m');
        }
        $labels = $months;
        $data = [];
        foreach ($months as $month) {
            $data[] = $covid_chart_data[$month]['cas_positifs'] ?? '0';
        }

        $covid_chart->labels($labels);
        $covid_chart->dataset('Cas confirmés positifs', 'line', $data)->options(['backgroundColor' => '#f2f2f2', 'borderColor' => '#737373']);

        // ********************************************COVID PAR COUVERTURE CHART****************************************************

        // covid chart data
        $couverture_chart = new CouvertureChart();

        if ((!$request->filled('hebdo')) && (!$request->filled('mensu')) && (!$request->filled('annu'))) {
            $start = Carbon::now()
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
            $org_data = [];
            $org = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y-%m') as month"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.organique_id')
                ->groupBy('month')
                ->get();

            $org->each(function ($item) use (&$org_data) {
                $org_data[$item->month] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $st_data = [];
            $st = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y-%m') as month"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.sousTraitant_id')
                ->groupBy('month')
                ->get();

            $st->each(function ($item) use (&$st_data) {
                $st_data[$item->month] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $start_year = Carbon::now()
                ->startOfYear()
                ->subMonth();
            $end_year = Carbon::now()->subMonth();

            $months = [];
            while ($start_year < $end_year) {
                $months[] = $start_year->addMonth()->format('Y-m');
            }
            $labels = $months;
            $data = [];
            foreach ($months as $month) {
                $data_org[] = $org_data[$month]['cas_positifs'] ?? '0';
                $data_st[] = $st_data[$month]['cas_positifs'] ?? '0';
            }
        }
        if ($request->filled('hebdo')) {
            $start = Carbon::now()
                ->StartOfWeek()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
            $org_data = [];
            $org = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y-%m-%d') as day"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.organique_id')
                ->groupBy('day')
                ->get();

            $org->each(function ($item) use (&$org_data) {
                $org_data[$item->day] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $st_data = [];
            $st = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y-%m-%d') as day"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.sousTraitant_id')
                ->groupBy('day')
                ->get();

            $st->each(function ($item) use (&$st_data) {
                $st_data[$item->day] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $start_week = Carbon::now()
                ->startOfWeek()
                ->subDays(2);
            // ->subMonth();
            // $end_year = Carbon::now()->subMonth();
            $end_week = Carbon::now()->subDay();
            // dd($end_week->format('Y-m-d'));

            $days = [];
            while ($start_week < $end_week) {
                $days[] = $start_week->addDay()->format('Y-m-d');
            }
            $labels = $days;
            $data = [];
            foreach ($days as $day) {
                $data_org[] = $org_data[$day]['cas_positifs'] ?? '0';
                $data_st[] = $st_data[$day]['cas_positifs'] ?? '0';
            }
        }

        if ($request->filled('annu')) {
            $start = Carbon::parse('01-03-2020')->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');

            $org_data = [];
            $org = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y') as year"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.organique_id')
                ->groupBy('year')
                ->get();

            $org->each(function ($item) use (&$org_data) {
                $org_data[$item->year] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $st_data = [];
            $st = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y') as year"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.sousTraitant_id')
                ->groupBy('year')
                ->get();

            $st->each(function ($item) use (&$st_data) {
                $st_data[$item->year] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $covid_year = Carbon::parse('01-03-2020')->subYear();
            $current_year = Carbon::now()->subYear();

            $years = [];
            while ($covid_year < $current_year) {
                $years[] = $covid_year->addYear()->format('Y');
            }
            $labels = $years;
            $data = [];
            foreach ($years as $year) {
                $data_org[] = $org_data[$year]['cas_positifs'] ?? '0';
                $data_st[] = $st_data[$year]['cas_positifs'] ?? '0';
            }
        }

        if ($request->filled('mensu')) {
            $start = Carbon::now()
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
            $org_data = [];
            $org = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y-%m') as month"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.organique_id')
                ->groupBy('month')
                ->get();

            $org->each(function ($item) use (&$org_data) {
                $org_data[$item->month] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $st_data = [];
            $st = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                ->select([DB::raw("DATE_FORMAT(date_consultation, '%Y-%m') as month"), DB::raw('COUNT(consultations.id) as cas_positifs')])
                ->whereBetween('date_consultation', [$start, $end])
                ->where('depistages.resultat_test', 'Positif')
                ->whereNotNull('consultations.sousTraitant_id')
                ->groupBy('month')
                ->get();

            $st->each(function ($item) use (&$st_data) {
                $st_data[$item->month] = [
                    'cas_positifs' => $item->cas_positifs,
                ];
            });

            $start_year = Carbon::now()
                ->startOfYear()
                ->subMonth();
            $end_year = Carbon::now()->subMonth();

            $months = [];
            while ($start_year < $end_year) {
                $months[] = $start_year->addMonth()->format('Y-m');
            }
            $labels = $months;
            $data = [];
            foreach ($months as $month) {
                $data_org[] = $org_data[$month]['cas_positifs'] ?? '0';
                $data_st[] = $st_data[$month]['cas_positifs'] ?? '0';
            }
        }

        $couverture_chart->labels($labels);
        $couverture_chart->dataset('Organique-SH', 'bar', $data_org)->options(['backgroundColor' => '#febd81', 'borderColor' => '#febd81']);
        $couverture_chart->dataset('Sous-Traitans', 'bar', $data_st)->options(['backgroundColor' => '#a6a6a6', 'borderColor' => '#a6a6a6']);
        $couverture_chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // ********************************************DEPISTAGE DOUGHNUT CHART*******************************************

        // covid test data
        $nb_tests = Depistage::count();
        $covid_test_chart_data_positif = (Depistage::where('resultat_test', 'Positif')->count() * 100) / $nb_tests;
        $covid_test_chart_data_negatif = (Depistage::where('resultat_test', 'Négatif')->count() * 100) / $nb_tests;
        $covid_test_chart_labels = ['Tests positifs', 'Tests négatifs'];
        $covid_test_chart_data = [$covid_test_chart_data_positif, $covid_test_chart_data_negatif];

        $covid_test_chart = new CovidTestChart();
        $covid_test_chart->labels($covid_test_chart_labels);
        $covid_test_chart->dataset('Tests', 'doughnut', $covid_test_chart_data)->options(['backgroundColor' => ['#a6a6a6', '#febd81']]);
        $covid_test_chart->minimalist(true);
        $covid_test_chart->displayLegend(true);
        $covid_test_chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // ********************************************VACCINATION DOUGHNUT CHART*******************************************

        //vaccination chart
        $organiques_vacc = round((Vaccination::whereNotNull('organique_id')->count() * 100) / $nb_employes, 2);
        $sousTraitans_vacc = round((Vaccination::whereNotNull('sousTraitant_id')->count() * 100) / $nb_employes, 2);
        $non_vacc = round((($nb_employes - $vaccination_total) * 100) / $nb_employes, 2);
        $vaccination_chart_labels = ['Organique SH vaccinés', 'Sous-Traitants vaccinés', 'Non Vaccinés'];
        $vaccination_chart_data = [$organiques_vacc, $sousTraitans_vacc, $non_vacc];

        $vaccination_chart = new VaccinationDoughnutChart();
        $vaccination_chart->labels($vaccination_chart_labels);
        $vaccination_chart->dataset('Tests', 'doughnut', $vaccination_chart_data)->options(['backgroundColor' => ['#a6a6a6', '#febd81']]);
        $vaccination_chart->minimalist(true);
        $vaccination_chart->displayLegend(true);
        $vaccination_chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // *****************************************INSPECTEUR DE PREVENTION CHARTS*******************************************
        // *************************************************REGIONAL CHART************************************************

        $regional_chart = new RegionalChart();
        $start = Carbon::parse('2020-03-01')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->filled('quotidien')) {
            $start = Carbon::now()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('hebdomadaire')) {
            $start = Carbon::now()
                ->StartOfWeek()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('mensuel')) {
            $start = Carbon::now()
                ->startOfMonth()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('anuel')) {
            $start = Carbon::now()
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }

        // cas positifs
        $cas_pos_data = [];
        $cas_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->groupBy('region_id')
            ->get();

        $cas_pos->each(function ($item) use (&$cas_pos_data) {
            $cas_pos_data[$item->region_id] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });
        // cas prolongé
        $cas_pro_data = [];
        $cas_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_pro')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->where('consultations.evolution_maladie', 'P')
            ->groupBy('region_id')
            ->get();

        $cas_pro->each(function ($item) use (&$cas_pro_data) {
            $cas_pro_data[$item->region_id] = [
                'cas_pro' => $item->cas_pro,
            ];
        });
        // cas decedés
        $cas_deces_data = [];
        $cas_deces = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_deces')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->groupBy('region_id')
            ->get();

        $cas_deces->each(function ($item) use (&$cas_deces_data) {
            $cas_deces_data[$item->region_id] = [
                'cas_deces' => $item->cas_deces,
            ];
        });
        // cas guéris
        $cas_gueris_data = [];
        $cas_gueris = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_gueris')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'G')
            ->groupBy('region_id')
            ->get();

        $cas_gueris->each(function ($item) use (&$cas_gueris_data) {
            $cas_gueris_data[$item->region_id] = [
                'cas_gueris' => $item->cas_gueris,
            ];
        });
        // cas hospitalisés
        $cas_hosp_data = [];
        $cas_hosp = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_hosp')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->groupBy('region_id')
            ->get();

        $cas_hosp->each(function ($item) use (&$cas_hosp_data) {
            $cas_hosp_data[$item->region_id] = [
                'cas_hosp' => $item->cas_hosp,
            ];
        });

        // filling chart labels and data
        $regions = Region::all();
        $labels = [];
        $data_pos = [];
        foreach ($regions as $region) {
            $labels[] = $region->code_region;
            $data_pos[] = ($cas_pos_data[$region->id]['cas_positifs'] ?? '0') - ($cas_pro_data[$region->id]['cas_pro'] ?? '0') - ($cas_deces_data[$region->id]['cas_deces'] ?? '0');
            $data_gueris[] = $cas_gueris_data[$region->id]['cas_gueris'] ?? '0';
            $data_hosp[] = $cas_hosp_data[$region->id]['cas_hosp'] ?? '0';
            $data_deces[] = $cas_deces_data[$region->id]['cas_deces'] ?? '0';
        }

        $regional_chart->labels($labels);
        $regional_chart->dataset('Cas positifs', 'bar', $data_pos)->options(['backgroundColor' => '#fda34e', 'borderColor' => '#fda34e']);
        $regional_chart->dataset('Cas guéris', 'bar', $data_gueris)->options(['backgroundColor' => '#a6a6a6', 'borderColor' => '#a6a6a6']);
        $regional_chart->dataset('Cas hospitalisés', 'bar', $data_hosp)->options(['backgroundColor' => '#feca9a', 'borderColor' => '#feca9a']);
        $regional_chart->dataset('décès', 'bar', $data_deces)->options(['backgroundColor' => '#262626', 'borderColor' => '#262626']);
        $regional_chart->options([
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'padding' => 10,
                ],
            ],
        ]);

        // *************************************************COUVERTURE CHART************************************************

        $couverture_line_chart = new CouvertureLineChart();
        $start = Carbon::parse('2020-03-01')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->filled('quot')) {
            $start = Carbon::now()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('hebd')) {
            $start = Carbon::now()
                ->StartOfWeek()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('mens')) {
            $start = Carbon::now()
                ->startOfMonth()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('ann')) {
            $start = Carbon::now()
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }

        // organique sh **********************
        // cas positifs
        $cas_pos_sh_data = [];
        $cas_pos_sh = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->whereNotNull('organique_id')
            ->groupBy('region_id')
            ->get();

        $cas_pos_sh->each(function ($item) use (&$cas_pos_sh_data) {
            $cas_pos_sh_data[$item->region_id] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });
        // cas prolongé
        $cas_pro_sh_data = [];
        $cas_pro_sh = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_pro')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->where('consultations.evolution_maladie', 'P')
            ->whereNotNull('organique_id')
            ->groupBy('region_id')
            ->get();

        $cas_pro_sh->each(function ($item) use (&$cas_pro_sh_data) {
            $cas_pro_sh_data[$item->region_id] = [
                'cas_pro' => $item->cas_pro,
            ];
        });
        // cas decedés
        $cas_deces_sh_data = [];
        $cas_deces_sh = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_deces')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->whereNotNull('organique_id')
            ->groupBy('region_id')
            ->get();

        $cas_deces_sh->each(function ($item) use (&$cas_deces_sh_data) {
            $cas_deces_sh_data[$item->region_id] = [
                'cas_deces' => $item->cas_deces,
            ];
        });

        // sous traitant***************************
        // cas positifs
        $cas_pos_st_data = [];
        $cas_pos_st = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->whereNotNull('sousTraitant_id')
            ->groupBy('region_id')
            ->get();

        $cas_pos_st->each(function ($item) use (&$cas_pos_st_data) {
            $cas_pos_st_data[$item->region_id] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });
        // cas prolongé
        $cas_pro_st_data = [];
        $cas_pro_st = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['region_id', DB::raw('COUNT(consultations.id) as cas_pro')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->where('consultations.evolution_maladie', 'P')
            ->whereNotNull('sousTraitant_id')
            ->groupBy('region_id')
            ->get();

        $cas_pro_st->each(function ($item) use (&$cas_pro_st_data) {
            $cas_pro_st_data[$item->region_id] = [
                'cas_pro' => $item->cas_pro,
            ];
        });
        // cas decedés
        $cas_deces_st_data = [];
        $cas_deces_st = Consultation::select(['region_id', DB::raw('COUNT(consultations.id) as cas_deces')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->whereNotNull('sousTraitant_id')
            ->groupBy('region_id')
            ->get();

        $cas_deces_st->each(function ($item) use (&$cas_deces_st_data) {
            $cas_deces_st_data[$item->region_id] = [
                'cas_deces' => $item->cas_deces,
            ];
        });

        // filling chart labels and data
        $regions = Region::all();
        $labels = [];
        $data_pos = [];
        foreach ($regions as $region) {
            $labels[] = $region->code_region;
            $data_pos_sh[] = ($cas_pos_sh_data[$region->id]['cas_positifs'] ?? '0') - ($cas_pro_sh_data[$region->id]['cas_pro'] ?? '0') - ($cas_deces_sh_data[$region->id]['cas_deces'] ?? '0');
            $data_pos_st[] = ($cas_pos_st_data[$region->id]['cas_positifs'] ?? '0') - ($cas_pro_st_data[$region->id]['cas_pro'] ?? '0') - ($cas_deces_st_data[$region->id]['cas_deces'] ?? '0');
        }

        $couverture_line_chart->labels($labels);
        $couverture_line_chart->dataset('Organique SH', 'line', $data_pos_sh)->options(['backgroundColor' => 'transparent', 'borderColor' => '#fd9635']);
        $couverture_line_chart->dataset('Sous-Traitants', 'line', $data_pos_st)->options(['backgroundColor' => 'transparent', 'borderColor' => '#a6a6a6']);
        $couverture_line_chart->options([
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'padding' => 10,
                ],
            ],
        ]);

        // *************************************************ZONES CHART************************************************

        // zones chart region : REB
        $zones_chart = new ZonesChart();
        $region_zones_capacite = [];
        $region_zones_nb_places = [];
        if ($request->filled('region')) {
            $zones_region = Zone::where('region_id', $request->region)
                ->orderBY('id')
                ->get();
        } else {
            $zones_region = Zone::where('region_id', 11)
                ->orderBY('id')
                ->get();
        }

        foreach ($zones_region as $zone) {
            $region_zones_capacite[] = $zone->capacité_zone;
            $region_zones_nb_places[] = $zone->capacité_zone - $zone->nb_places;
            $labelss[] = $zone->code_zone;
        }
        // foreach ($region_zones_nb_places as $region_zones_nb_place) {
        //     echo $region_zones_nb_place .  ' | ';
        // }
        // dd('fml');

        $zones_chart->labels($labelss);
        $zones_chart->dataset('Capacité', 'bar', $region_zones_capacite)->options(['backgroundColor' => '#febd81', 'borderColor' => '#febd81']);
        $zones_chart->dataset('Actuellement confinés', 'bar', $region_zones_nb_places)->options(['backgroundColor' => '#a6a6a6', 'borderColor' => '#a6a6a6']);
        $zones_chart->options([
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'padding' => 20,
                ],
            ],
        ]);

        // **********************************************COORDINATEUR CHARTS********************************************
        // ********************************-*****************CMT CHART**************************************************

        $cmt_chart = new CMTChart();
        $start = Carbon::parse('2020-03-01')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->filled('Q')) {
            $start = Carbon::now()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('H')) {
            $start = Carbon::now()
                ->StartOfWeek()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('M')) {
            $start = Carbon::now()
                ->startOfMonth()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('A')) {
            $start = Carbon::now()
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        // cas positifs
        $cmt_cas_pos_data = [];
        $cmt_cas_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['consultations.cmt_id', DB::raw('COUNT(consultations.id) as cas_positifs')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->groupBy('consultations.cmt_id')
            ->get();

        $cmt_cas_pos->each(function ($item) use (&$cmt_cas_pos_data) {
            $cmt_cas_pos_data[$item->cmt_id] = [
                'cas_positifs' => $item->cas_positifs,
            ];
        });
        // cas prolongé
        $cmt_cas_pro_data = [];
        $cmt_cas_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->select(['consultations.cmt_id', DB::raw('COUNT(consultations.id) as cas_pro')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('depistages.resultat_test', 'Positif')
            ->where('consultations.evolution_maladie', 'P')
            ->groupBy('consultations.cmt_id')
            ->get();

        $cmt_cas_pro->each(function ($item) use (&$cmt_cas_pro_data) {
            $cmt_cas_pro_data[$item->cmt_id] = [
                'cas_pro' => $item->cas_pro,
            ];
        });
        // cas decedés
        $cmt_cas_deces_data = [];
        $cmt_cas_deces = Consultation::select(['consultations.cmt_id', DB::raw('COUNT(consultations.id) as cas_deces')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->groupBy('consultations.cmt_id')
            ->get();

        $cmt_cas_deces->each(function ($item) use (&$cmt_cas_deces_data) {
            $cmt_cas_deces_data[$item->cmt_id] = [
                'cas_deces' => $item->cas_deces,
            ];
        });
        // cas guéris
        $cmt_cas_gueris_data = [];
        $cmt_cas_gueris = Consultation::select(['consultations.cmt_id', DB::raw('COUNT(consultations.id) as cas_gueris')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'G')
            ->groupBy('consultations.cmt_id')
            ->get();

        $cmt_cas_gueris->each(function ($item) use (&$cmt_cas_gueris_data) {
            $cmt_cas_gueris_data[$item->cmt_id] = [
                'cas_gueris' => $item->cas_gueris,
            ];
        });
        // cas hospitalisés
        $cmt_cas_hosp_data = [];
        $cmt_cas_hosp = Consultation::select(['consultations.cmt_id', DB::raw('COUNT(consultations.id) as cas_hosp')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->groupBy('consultations.cmt_id')
            ->get();

        $cmt_cas_hosp->each(function ($item) use (&$cmt_cas_hosp_data) {
            $cmt_cas_hosp_data[$item->cmt_id] = [
                'cas_hosp' => $item->cas_hosp,
            ];
        });

        // filling chart labels and data
        $cmts = CMT::all();
        $labels_cmt = [];
        foreach ($cmts as $cmt) {
            $labels_cmt[] = $cmt->code_cmt;
            $cmt_data_pos[] = ($cmt_cas_pos_data[$cmt->id]['cas_positifs'] ?? '0') - ($cmt_cas_pro_data[$cmt->id]['cas_pro'] ?? '0') - ($cmt_cas_deces_data[$cmt->id]['cas_deces'] ?? '0');
            $cmt_data_gueris[] = $cmt_cas_gueris_data[$cmt->id]['cas_gueris'] ?? '0';
            $cmt_data_hosp[] = $cmt_cas_hosp_data[$cmt->id]['cas_hosp'] ?? '0';
            $cmt_data_deces[] = $cmt_cas_deces_data[$cmt->id]['cas_deces'] ?? '0';
        }

        $cmt_chart->labels($labels_cmt);
        $cmt_chart->dataset('Cas positifs', 'bar', $cmt_data_pos)->options(['backgroundColor' => '#fda34e', 'borderColor' => '#fda34e']);
        $cmt_chart->dataset('guéris', 'bar', $cmt_data_gueris)->options(['backgroundColor' => '#a6a6a6', 'borderColor' => '#a6a6a6']);
        $cmt_chart->dataset('hospitalisés', 'bar', $cmt_data_hosp)->options(['backgroundColor' => '#feca9a', 'borderColor' => '#feca9a']);
        $cmt_chart->dataset('décès', 'bar', $cmt_data_deces)->options(['backgroundColor' => '#262626', 'borderColor' => '#262626']);
        $cmt_chart->options([
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'padding' => 20,
                ],
            ],
        ]);

        // *************************************************DEPISTAGE CHART************************************************

        $depistage_chart = new DepistageChart(); //par defaut quotidien
        $start = Carbon::parse('2020-03-01')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $report_depistage = [];
        $report_depistage_pos = [];
        if ($request->filled('cmt')) {
            // depistages
            $cmt = $_GET['cmt'];
            $depistatge = Depistage::select(['type_test', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', $cmt)
                ->groupBy('type_test')
                ->get();

            $depistatge->each(function ($item) use (&$report_depistage) {
                $report_depistage[$item->type_test] = [
                    'total' => $item->total,
                ];
            });

            // depistages positifs
            $depistatge_pos = Depistage::select(['type_test', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('cmt_id', $cmt)
                ->where('resultat_test', 'Positif')
                ->groupBy('type_test')
                ->get();

            $depistatge_pos->each(function ($item) use (&$report_depistage_pos) {
                $report_depistage_pos[$item->type_test] = [
                    'total' => $item->total,
                ];
            });
        } else {
            // depistages
            $depistatge = Depistage::select(['type_test', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->groupBy('type_test')
                ->get();

            $depistatge->each(function ($item) use (&$report_depistage) {
                $report_depistage[$item->type_test] = [
                    'total' => $item->total,
                ];
            });

            // depistages positifs
            $depistatge_pos = Depistage::select(['type_test', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_test', [$start, $end])
                ->where('resultat_test', 'Positif')
                ->groupBy('type_test')
                ->get();

            $depistatge_pos->each(function ($item) use (&$report_depistage_pos) {
                $report_depistage_pos[$item->type_test] = [
                    'total' => $item->total,
                ];
            });
        }
        // filling chart labels and data
        $tests = Depistage::select('type_test')
            ->distinct()
            ->get();

        $labels_depistage = [];
        foreach ($tests as $test) {
            $labels_depistage[] = $test->type_test;
            $depistage_data[] = $report_depistage[$test->type_test]['total'] ?? '0';
            $depistage_pos_data[] = $report_depistage_pos[$test->type_test]['total'] ?? '0';
        }

        $depistage_chart->labels($labels_depistage);
        $depistage_chart->dataset('total dépisté', 'bar', $depistage_data)->options(['backgroundColor' => '#febd81', 'borderColor' => '#febd81']);
        $depistage_chart->dataset('total positif', 'bar', $depistage_pos_data)->options(['backgroundColor' => '#a6a6a6', 'borderColor' => '#a6a6a6']);
        $depistage_chart->options([
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'padding' => 20,
                ],
            ],
        ]);

        // *************************************************PRESIDENT CHARTS************************************************
        // *************************************************STRUCTURE CHART************************************************

        $structure_chart = new StructureChart();
        $start = Carbon::parse('2020-03-01')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->filled('quo')) {
            $start = Carbon::now()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('heb')) {
            $start = Carbon::now()
                ->StartOfWeek()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('men')) {
            $start = Carbon::now()
                ->startOfMonth()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }
        if ($request->filled('an')) {
            $start = Carbon::now()
                ->startOfYear()
                ->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
        }

        // cas positif
        $report_structure_pos = [];

        $structure_pos = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
            ->select(['structure', DB::raw('COUNT(consultations.id) as total')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('resultat_test', 'Positif')
            ->groupBy('structure')
            ->get();

        $structure_pos->each(function ($item) use (&$report_structure_pos) {
            $report_structure_pos[$item->structure] = [
                'total' => $item->total,
            ];
        });

        // cas prolongé
        $report_structure_pro = [];

        $structure_pro = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
            ->select(['structure', DB::raw('COUNT(consultations.id) as total')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('consultations.evolution_maladie', 'P')
            ->groupBy('structure')
            ->get();

        $structure_pro->each(function ($item) use (&$report_structure_pro) {
            $report_structure_pro[$item->structure] = [
                'total' => $item->total,
            ];
        });

        // cas decedés
        $report_structure_deces = [];

        $structure_deces = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
            ->select(['structure', DB::raw('COUNT(consultations.id) as total')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'D')
            ->groupBy('structure')
            ->get();

        $structure_deces->each(function ($item) use (&$report_structure_deces) {
            $report_structure_deces[$item->structure] = [
                'total' => $item->total,
            ];
        });

        // cas guéris
        $report_structure_gueris = [];

        $structure_gueris = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
            ->select(['structure', DB::raw('COUNT(consultations.id) as total')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('evolution_maladie', 'G')
            ->groupBy('structure')
            ->get();

        $structure_gueris->each(function ($item) use (&$report_structure_gueris) {
            $report_structure_gueris[$item->structure] = [
                'total' => $item->total,
            ];
        });

        // cas hospitalisés
        $report_structure_hosp = [];

        $structure_hosp = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
            ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
            ->select(['structure', DB::raw('COUNT(consultations.id) as total')])
            ->whereBetween('date_consultation', [$start, $end])
            ->where('modalités_priseEnCharge', 'H')
            ->groupBy('structure')
            ->get();

        $structure_hosp->each(function ($item) use (&$report_structure_hosp) {
            $report_structure_hosp[$item->structure] = [
                'total' => $item->total,
            ];
        });

        // filling chart labels and data
        $structures = EmployeOrganique::select('structure')
            ->distinct()
            ->get();
        $labels_structure = [];
        foreach ($structures as $structure) {
            $labels_structure[] = $structure->structure;
            $structure_data_pos[] = ($report_structure_pos[$structure->structure]['total'] ?? '0') - ($report_structure_pro[$structure->structure]['total'] ?? '0') - ($report_structure_deces[$structure->structure]['total'] ?? '0');
            $structure_data_gueris[] = $report_structure_gueris[$structure->structure]['total'] ?? '0';
            $structure_data_hosp[] = $report_structure_hosp[$structure->structure]['total'] ?? '0';
            $structure_data_deces[] = $report_structure_deces[$structure->structure]['total'] ?? '0';
        }

        $structure_chart->labels($labels_structure);
        $structure_chart->dataset('Cas positifs', 'bar', $structure_data_pos)->options(['backgroundColor' => '#fda34e', 'borderColor' => '#fda34e']);
        $structure_chart->dataset('guéris', 'bar', $structure_data_gueris)->options(['backgroundColor' => '#a6a6a6', 'borderColor' => '#a6a6a6']);
        $structure_chart->dataset('hospitalisés', 'bar', $structure_data_hosp)->options(['backgroundColor' => '#feca9a', 'borderColor' => '#feca9a']);
        $structure_chart->dataset('décès', 'bar', $structure_data_deces)->options(['backgroundColor' => '#262626', 'borderColor' => '#262626']);
        $structure_chart->options([
            'legend' => [
                'position' => 'bottom',
                'labels' => [
                    'padding' => 20,
                ],
            ],
        ]);

        // *************************************************DOSES CHART************************************************

        $dose_chart = new DoseChart();
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->filled('cmt')) {
            $cmt = $_GET['cmt'];
            $dose1 = Vaccination::whereBetween('date_vaccination', [$start, $end])
                ->where('cmt_id', $cmt)
                ->where('dose_vaccination', 1)
                ->count();

            $dose2 = Vaccination::whereBetween('date_vaccination', [$start, $end])
                ->where('cmt_id', $cmt)
                ->where('dose_vaccination', 2)
                ->count();
        } else {
            $dose1 = Vaccination::whereBetween('date_vaccination', [$start, $end])
                ->where('dose_vaccination', 1)
                ->count();

            $dose2 = Vaccination::whereBetween('date_vaccination', [$start, $end])
                ->where('dose_vaccination', 2)
                ->count();
        }
        // filling chart labels and data

        $labels_dose = ['1ère dose', '2ème dose'];
        $dose_data = [$dose1, $dose2];

        $dose_chart->labels($labels_dose);
        $dose_chart->dataset('Vaccination', 'pie', $dose_data)->options(['backgroundColor' => ['#e6e6e6', '#febd81']]);
        $dose_chart->minimalist(true);
        $dose_chart->displayLegend(true);
        $dose_chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // *************************************************VACCINS CHART************************************************

        $vaccin_chart = new VaccinChart();
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $report_vaccins = [];
        if ($request->filled('cmtt')) {
            $cmt = $_GET['cmtt'];
            $vaccins = Vaccination::select(['type_vaccin', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_vaccination', [$start, $end])
                ->where('cmt_id', $cmt)
                ->groupBy('type_vaccin')
                ->get();

            $vaccins->each(function ($item) use (&$report_vaccins) {
                $report_vaccins[$item->type_vaccin] = [
                    'total' => $item->total,
                ];
            });
        } else {
            $vaccins = Vaccination::select(['type_vaccin', DB::raw('COUNT(id) as total')])
                ->whereBetween('date_vaccination', [$start, $end])
                ->groupBy('type_vaccin')
                ->get();

            $vaccins->each(function ($item) use (&$report_vaccins) {
                $report_vaccins[$item->type_vaccin] = [
                    'total' => $item->total,
                ];
            });
        }
        // filling chart labels and data
        $types_vaccins = Vaccination::select('type_vaccin')
            ->distinct()
            ->get();

        $vaccins_data = [];
        $labels_vaccins = [];
        foreach ($types_vaccins as $vaccin) {
            $labels_vaccins[] = $vaccin->type_vaccin;
            $vaccins_data[] = $report_vaccins[$vaccin->type_vaccin]['total'] ?? '0';
        }

        $vaccin_chart->labels($labels_vaccins);
        $vaccin_chart->dataset('Vaccins', 'pie', $vaccins_data)->options(['backgroundColor' => ['#febd81', '#e6e6e6', '#a6a6a6']]);
        $vaccin_chart->minimalist(true);
        $vaccin_chart->displayLegend(true);
        $vaccin_chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // *************************************************sexe CHART************************************************

        $sexe_chart = new SexeChart();
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->filled('couverture')) {
            $couverture = $request->couverture;
            if ($couverture == 'O') {
                $f = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'F')
                    ->count();

                $m = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'M')
                    ->count();
            }
            if ($couverture == 'ST') {
                $f = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'F')
                    ->count();

                $m = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'M')
                    ->count();
            }
        } else {
            $f =
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'F')
                    ->count() +
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'F')
                    ->count();

            $m =
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'M')
                    ->count() +
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->where('depistages.resultat_test', 'Positif')
                    ->where('sexe', 'M')
                    ->count();
        }
        // filling chart labels and data

        $labels_sexe = ['Féminin', 'Masculin'];
        $sexe_data = [$f, $m];

        $sexe_chart->labels($labels_sexe);
        $sexe_chart->dataset('Sexe', 'pie', $sexe_data)->options(['backgroundColor' => ['#febd81', '#e6e6e6']]);
        $sexe_chart->minimalist(true);
        $sexe_chart->displayLegend(true);
        $sexe_chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        // *************************************************AGE CHART************************************************

        $age_chart = new AgeChart();
        $start = Carbon::parse('01-03-2020')->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        if ($request->filled('couverturee')) {
            $couverture = $request->couverturee;
            if ($couverture == 'O') {
                $nb_20_30 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 20 AND 30')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

                $nb_30_40 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 30 AND 40')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

                $nb_40_50 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 40 AND 50')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

                $nb_50_60 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 50 AND 60')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();
            }
            if ($couverture == 'ST') {
                $nb_20_30 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 20 AND 30')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

                $nb_30_40 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 30 AND 40')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

                $nb_40_50 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 40 AND 50')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

                $nb_50_60 = Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 50 AND 60')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();
            }
        } else {
            $nb_20_30 =
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 20 AND 30')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count() +
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 20 AND 30')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

            $nb_30_40 =
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 30 AND 40')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count() +
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 30 AND 40')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

            $nb_40_50 =
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 40 AND 50')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count() +
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 40 AND 50')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();

            $nb_50_60 =
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('employe_organiques', 'employe_organiques.id', '=', 'consultations.organique_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 50 AND 60')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count() +
                Consultation::join('depistages', 'depistages.id', '=', 'consultations.depistage_id')
                    ->join('sous_traitants', 'sous_traitants.id', '=', 'consultations.sousTraitant_id')
                    ->whereBetween('date_consultation', [$start, $end])
                    ->whereRaw('TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 50 AND 60')
                    ->where('depistages.resultat_test', 'Positif')
                    ->count();
        }
        // filling chart labels and data

        $labels_age = ['20-30', '30-40', '40-50', '50-60'];
        $age_data = [$nb_20_30, $nb_30_40, $nb_40_50, $nb_50_60];

        $age_chart->labels($labels_age);
        $age_chart->dataset('age', 'pie', $age_data)->options(['backgroundColor' => ['#fda34e', '#a6a6a6', '#e6e6e6', '#feca9a']]);
        $age_chart->minimalist(true);
        $age_chart->displayLegend(true);
        $age_chart->options([
            'legend' => [
                'position' => 'bottom',
            ],
        ]);

        return view('dashboard', compact('covid_chart', 'covid_test_chart', 'vaccination_chart', 'regional_chart', 'couverture_line_chart', 'zones_chart', 'cmt_chart', 'depistage_chart', 'dose_chart', 'vaccin_chart', 'sexe_chart', 'age_chart', 'structure_chart', 'couverture_chart', 'vaccination_total', 'vaccination_pourcentage', 'cas_positifs_total', 'cas_positif_actives', 'cas_hospitalisés_total', 'cas_hospitalisés_actives', 'cas_guéris', 'deces', 'type', 'regions', 'cmts'));
    }

    public function changeChart(Request $request, $type)
    {
        return $this->index($request, $type);
    }
}
