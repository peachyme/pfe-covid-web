<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CMT;
use App\Models\Role;
use App\Models\User;
use App\Models\Zone;
use App\Models\Region;
use App\Models\Depistage;
use App\Models\Vaccination;
use App\Models\Consultation;
use App\Models\SousTraitant;
use Illuminate\Http\Request;
use App\Models\EmployeOrganique;
use Laravel\Ui\Presets\Vue;

class SearchController extends Controller
{
    /**
     * Search users.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchUser(Request $request)
    {
        $roles = Role::orderBy('role', 'ASC')->get();
        $regions = Region::orderBy('id', 'ASC')->get();
        $cmts = CMT::orderBy('id', 'ASC')->get();

        if (isset($_GET['matricule']) || isset($_GET['nom']) || isset($_GET['prenom']) || isset($_GET['email']) || isset($_GET['role'])) {
            $matricule = '';
            $nom = '';
            $prenom = '';
            $email = '';
            $role = null;
            if (isset($_GET['matricule'])) {
                $matricule = $_GET['matricule'];
            }
            if (isset($_GET['nom'])) {
                $nom = $_GET['nom'];
            }
            if (isset($_GET['prenom'])) {
                $prenom = $_GET['prenom'];
            }
            if (isset($_GET['email'])) {
                $email = $_GET['email'];
            }

            $users = User::where('matricule', 'LIKE', '%' . $matricule . '%')
                ->where('nom', 'LIKE', '%' . $nom . '%')
                ->where('prenom', 'LIKE', '%' . $prenom . '%')
                ->where('email', 'LIKE', '%' . $email . '%')
                ->paginate(3);
            $users->appends($request->all());

            if (isset($_GET['role'])) {
                $role = Role::find($_GET['role']);
                $users = $role->users()->paginate(3);
            }
        } else {
            $users = User::orderBy('nom', 'ASC')->paginate(3);
            return view('admin.users.index', [
                'users' => $users,
                'roles' => $roles,
                'regions' => $regions,
                'cmts' => $cmts,
            ]);
        }
        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'regions' => $regions,
            'cmts' => $cmts,
        ]);
    }

    /**
     * Search zones.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchZone(Request $request)
    {
        $region = Region::where('code_region', auth()->user()->region_cmt)->first();
        $regions = Region::orderBy('id','ASC')->get();
        $zones_all = Zone::orderBy('id','ASC')->paginate(5);
        $employes = EmployeOrganique::where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->get();

        if (isset($_GET['code_zone']) || isset($_GET['region_zone']) || isset($_GET['libelle_zone']) || isset($_GET['capacite_zone']) || isset($_GET['nb_places']) || isset($_GET['responsable_zone'])) {
            $code_zone = '';
            $libelle_zone = '';
            $region_zone = '';
            $capacite_zone = '';
            $nb_places = '';
            $responsable_zone = '';
            if (isset($_GET['code_zone'])) {
                $code_zone = $_GET['code_zone'];
            }
            if (isset($_GET['region_zone'])) {
                $region_zone = $_GET['region_zone'];
            }
            if (isset($_GET['libelle_zone'])) {
                $libelle_zone = $_GET['libelle_zone'];
            }
            if (isset($_GET['capacite_zone'])) {
                $capacite_zone = $_GET['capacite_zone'];
            }
            if (isset($_GET['nb_places'])) {
                $nb_places = $_GET['nb_places'];
            }
            if (isset($_GET['responsable_zone'])) {
                $responsable_zone = $_GET['responsable_zone'];
            }

            $zones = Zone::where('region_id', $region->id)
                ->where('code_zone', 'LIKE', '%' . $code_zone . '%')
                ->where('libelle_zone', 'LIKE', '%' . $libelle_zone . '%')
                ->where('capacité_zone', 'LIKE', '%' . $capacite_zone . '%')
                ->where('nb_places', 'LIKE', '%' . $nb_places . '%')
                ->where('responsable_zone', 'LIKE', '%' . $responsable_zone . '%')
                ->paginate(5);

            $zones->appends($request->all());

            $zones_all = Zone::where('code_zone', 'LIKE', '%' . $code_zone . '%')
                ->where('region_id', 'LIKE', '%' . $region_zone . '%')
                ->where('libelle_zone', 'LIKE', '%' . $libelle_zone . '%')
                ->where('capacité_zone', 'LIKE', '%' . $capacite_zone . '%')
                ->where('nb_places', 'LIKE', '%' . $nb_places . '%')
                ->where('responsable_zone', 'LIKE', '%' . $responsable_zone . '%')
                ->paginate(5);

            $zones_all->appends($request->all());
        } else {
            $zones = Zone::where('region_id', $region->id)
                ->orderBy('id', 'ASC')
                ->paginate(2);
            return view('DR.zones.index', [
                'regions' => $regions,
                'zones' => $zones,
                'zones_all' => $zones_all,
                'employes' => $employes,
            ]);
        }
        return view('DR.zones.index', [
            'regions' => $regions,
            'zones' => $zones,
            'zones_all' => $zones_all,
            'employes' => $employes,
        ]);
    }

    /**
     * Search consultations.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchConsultation(Request $request)
    {
        if (auth()->user()->region_cmt == 'DP/OS') {
            $region = Region::find(1);
        } else {
            $region = Region::where('code_region', auth()->user()->region_cmt)->first();
        }
        $date = Carbon::now()->format('Y-m-d');
        $today = 0;
        $consultations = Consultation::where('date_consultation', $date)
            ->where('region_id', $region->id)
            ->orderBy('date_consultation', 'ASC')
            ->paginate(1);

        if ($request->filled('date_consultation')) {
            if (isset($_GET['matricule']) || isset($_GET['nom']) || isset($_GET['structure']) || isset($_GET['modalite_priseEnCharge']) || isset($_GET['symptomes']) || isset($_GET['depistage'])) {
                $date = $request->date_consultation;
                $today = Carbon::parse($request->date_consultation)->format('d-m-Y');
                $matricule = '';
                $nom = '';
                $structure = '';
                $modalite_priseEnCharge = '';
                $symptomes = '';
                $depistage = '';
                if (isset($_GET['matricule'])) {
                    $matricule = $_GET['matricule'];
                }
                if (isset($_GET['nom'])) {
                    $nom = $_GET['nom'];
                }
                if (isset($_GET['structure'])) {
                    $structure = $_GET['structure'];
                }
                if (isset($_GET['modalite_priseEnCharge'])) {
                    $modalite_priseEnCharge = $_GET['modalite_priseEnCharge'];
                }
                if (isset($_GET['symptomes'])) {
                    $symptomes = $_GET['symptomes'];
                }
                if (isset($_GET['depistage'])) {
                    $depistage = $_GET['depistage'];
                }

                $employe_ids = EmployeOrganique::select('id')
                    ->where('region_id', $region->id)
                    ->where('matricule', 'LIKE', '%' . $matricule . '%')
                    ->where('nom', 'LIKE', '%' . $nom . '%')
                    ->where('structure', 'LIKE', '%' . $structure . '%')
                    ->get();
                $sous_ids = SousTraitant::select('id')
                    ->where('region_id', $region->id)
                    ->where('matricule', 'LIKE', '%' . $matricule . '%')
                    ->where('nom', 'LIKE', '%' . $nom . '%')
                    ->where('type', 'LIKE', '%' . $structure . '%')
                    ->get();

                $depistage_ids = Depistage::select('id')
                    ->where('resultat_test', 'LIKE', $depistage . '%')
                    ->get();

                $consultations = Consultation::where('date_consultation', $date)
                    ->whereIn('organique_id', $employe_ids)
                    ->where('modalités_priseEnCharge', 'LIKE', '%' . $modalite_priseEnCharge)
                    ->where('symptomes', 'LIKE', '%' . $symptomes . '%')
                    ->whereIn('depistage_id', $depistage_ids)
                    ->orWhereIn('sousTraitant_id', $sous_ids)
                    ->where('modalités_priseEnCharge', 'LIKE', '%' . $modalite_priseEnCharge)
                    ->where('symptomes', 'LIKE', '%' . $symptomes . '%')
                    ->whereIn('depistage_id', $depistage_ids)
                    ->where('date_consultation', $date)
                    ->orderBy('date_consultation', 'ASC')
                    ->paginate(5);

                $consultations->appends($request->all());
            } else {
                if (
                    auth()
                        ->user()
                        ->roles->first()->role == 'Responsable de Reporting'
                ) {
                    return view('dr.covid.index', [
                        'consultations' => $consultations,
                        'today' => $today,
                    ]);
                } else {
                    if (
                        auth()
                            ->user()
                            ->roles->first()->role == 'Secrétaire médicale'
                    ) {
                        return view('cmt.covid.index', [
                            'consultations' => $consultations,
                            'today' => $today,
                        ]);
                    }
                }
            }

            if (
                auth()
                    ->user()
                    ->roles->first()->role == 'Responsable de Reporting'
            ) {
                return view('dr.covid.index', [
                    'consultations' => $consultations,
                    'today' => $today,
                ]);
            } else {
                if (
                    auth()
                        ->user()
                        ->roles->first()->role == 'Secrétaire médicale'
                ) {
                    return view('cmt.covid.index', [
                        'consultations' => $consultations,
                        'today' => $today,
                    ]);
                }
            }
        } else {
            if (isset($_GET['matricule']) || isset($_GET['nom']) || isset($_GET['structure']) || isset($_GET['modalite_priseEnCharge']) || isset($_GET['symptomes']) || isset($_GET['depistage'])) {
                $matricule = '';
                $nom = '';
                $structure = '';
                $modalite_priseEnCharge = '';
                $symptomes = '';
                $depistage = '';
                if (isset($_GET['matricule'])) {
                    $matricule = $_GET['matricule'];
                }
                if (isset($_GET['nom'])) {
                    $nom = $_GET['nom'];
                }
                if (isset($_GET['structure'])) {
                    $structure = $_GET['structure'];
                }
                if (isset($_GET['modalite_priseEnCharge'])) {
                    $modalite_priseEnCharge = $_GET['modalite_priseEnCharge'];
                }
                if (isset($_GET['symptomes'])) {
                    $symptomes = $_GET['symptomes'];
                }
                if (isset($_GET['depistage'])) {
                    $depistage = $_GET['depistage'];
                }

                $employe_ids = EmployeOrganique::select('id')
                    ->where('region_id', $region->id)
                    ->where('matricule', 'LIKE', '%' . $matricule . '%')
                    ->where('nom', 'LIKE', '%' . $nom . '%')
                    ->where('structure', 'LIKE', '%' . $structure . '%')
                    ->get();
                $sous_ids = SousTraitant::select('id')
                    ->where('region_id', $region->id)
                    ->where('matricule', 'LIKE', '%' . $matricule . '%')
                    ->where('nom', 'LIKE', '%' . $nom . '%')
                    ->where('type', 'LIKE', '%' . $structure . '%')
                    ->get();

                $depistage_ids = Depistage::select('id')
                    ->where('resultat_test', 'LIKE', $depistage . '%')
                    ->get();

                $consultations = Consultation::whereIn('organique_id', $employe_ids)
                    ->where('modalités_priseEnCharge', 'LIKE', '%' . $modalite_priseEnCharge)
                    ->where('symptomes', 'LIKE', '%' . $symptomes . '%')
                    ->whereIn('depistage_id', $depistage_ids)
                    ->orWhereIn('sousTraitant_id', $sous_ids)
                    ->where('modalités_priseEnCharge', 'LIKE', '%' . $modalite_priseEnCharge)
                    ->where('symptomes', 'LIKE', '%' . $symptomes . '%')
                    ->whereIn('depistage_id', $depistage_ids)
                    ->orderBy('date_consultation', 'ASC')
                    ->paginate(5);

                $consultations->appends($request->all());
            } else {
                if (
                    auth()
                        ->user()
                        ->roles->first()->role == 'Responsable de Reporting'
                ) {
                    return view('dr.covid.index', [
                        'consultations' => $consultations,
                        'today' => $today,
                    ]);
                } else {
                    if (
                        auth()
                            ->user()
                            ->roles->first()->role == 'Secrétaire médicale'
                    ) {
                        return view('cmt.covid.index', [
                            'consultations' => $consultations,
                            'today' => $today,
                        ]);
                    }
                }
            }

            if (
                auth()
                    ->user()
                    ->roles->first()->role == 'Responsable de Reporting'
            ) {
                return view('dr.covid.index', [
                    'consultations' => $consultations,
                    'today' => $today,
                ]);
            } else {
                if (
                    auth()
                        ->user()
                        ->roles->first()->role == 'Secrétaire médicale'
                ) {
                    return view('cmt.covid.index', [
                        'consultations' => $consultations,
                        'today' => $today,
                    ]);
                }
            }
        }
    }

    /**
     * Search vaccinations.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchVaccination(Request $request)
    {
        $region = Region::where('code_region', auth()->user()->region_cmt)->first();
        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::now()->format('d-m-Y');
        $vaccinations = Vaccination::where('date_vaccination', $date)
            ->where('region_id', $region->id)
            ->orderBy('id', 'ASC')
            ->paginate(5);

        if (isset($_GET['matricule']) || isset($_GET['nom']) || isset($_GET['structure']) || isset($_GET['dose_vaccination']) || isset($_GET['type_vaccin']) || isset($_GET['couverture'])) {
            $matricule = '';
            $nom = '';
            $structure = '';
            $dose_vaccination = '';
            $couverture = '';
            $type_vaccin = '';
            $today = 0;
            if (isset($_GET['matricule'])) {
                $matricule = $_GET['matricule'];
            }
            if (isset($_GET['nom'])) {
                $nom = $_GET['nom'];
            }
            if (isset($_GET['structure'])) {
                $structure = $_GET['structure'];
            }
            if (isset($_GET['dose_vaccination'])) {
                $dose_vaccination = $_GET['dose_vaccination'];
            }
            if (isset($_GET['type_vaccin'])) {
                $type_vaccin = $_GET['type_vaccin'];
            }
            if (isset($_GET['couverture'])) {
                $couverture = $_GET['couverture'];
            }

            if ($couverture == 'organique') {
                $employe_ids = EmployeOrganique::select('id')
                    ->where('region_id', $region->id)
                    ->where('matricule', 'LIKE', '%' . $matricule . '%')
                    ->where('nom', 'LIKE', '%' . $nom . '%')
                    ->where('structure', 'LIKE', '%' . $structure . '%')
                    ->get();

                $vaccinations = Vaccination::whereIn('organique_id', $employe_ids)
                    ->where('dose_vaccination', 'LIKE', '%' . $dose_vaccination)
                    ->where('type_vaccin', 'LIKE', '%' . $type_vaccin)
                    ->paginate(5);
            } else {
                if ($couverture == 'sousTraitant') {
                    $sous_ids = SousTraitant::select('id')
                        ->where('region_id', $region->id)
                        ->where('matricule', 'LIKE', '%' . $matricule . '%')
                        ->where('nom', 'LIKE', '%' . $nom . '%')
                        ->where('type', 'LIKE', '%' . $structure . '%')
                        ->get();

                    $vaccinations = Vaccination::whereIn('sousTraitant_id', $sous_ids)
                        ->where('dose_vaccination', 'LIKE', '%' . $dose_vaccination)
                        ->where('type_vaccin', 'LIKE', '%' . $type_vaccin)
                        ->paginate(5);
                } else {
                    $employe_ids = EmployeOrganique::select('id')
                        ->where('region_id', $region->id)
                        ->where('matricule', 'LIKE', '%' . $matricule . '%')
                        ->where('nom', 'LIKE', '%' . $nom . '%')
                        ->where('structure', 'LIKE', '%' . $structure . '%')
                        ->get();

                    $sous_ids = SousTraitant::select('id')
                        ->where('region_id', $region->id)
                        ->where('matricule', 'LIKE', '%' . $matricule . '%')
                        ->where('nom', 'LIKE', '%' . $nom . '%')
                        ->where('type', 'LIKE', '%' . $structure . '%')
                        ->get();

                    $vaccinations = Vaccination::whereIn('organique_id', $employe_ids)
                        ->where('dose_vaccination', 'LIKE', '%' . $dose_vaccination)
                        ->where('type_vaccin', 'LIKE', '%' . $type_vaccin)
                        ->orWhereIn('sousTraitant_id', $sous_ids)
                        ->where('dose_vaccination', 'LIKE', '%' . $dose_vaccination)
                        ->where('type_vaccin', 'LIKE', '%' . $type_vaccin)
                        ->paginate(5);
                }
            }

            $vaccinations->appends($request->all());
        } else {
            if (
                auth()
                    ->user()
                    ->roles->first()->role == 'Responsable de Reporting'
            ) {
                return view('dr.vaccination.index', [
                    'vaccinations' => $vaccinations,
                    'today' => $today,
                    'date' => $date,
                ]);
            } else {
                if (
                    auth()
                        ->user()
                        ->roles->first()->role == 'Secrétaire médicale'
                ) {
                    return view('cmt.vaccination.index', [
                        'vaccinations' => $vaccinations,
                        'today' => $today,
                        'date' => $date,
                    ]);
                }
            }
        }

        if (
            auth()
                ->user()
                ->roles->first()->role == 'Responsable de Reporting'
        ) {
            return view('dr.vaccination.index', [
                'vaccinations' => $vaccinations,
                'today' => $today,
                'date' => $date,
            ]);
        } else {
            if (
                auth()
                    ->user()
                    ->roles->first()->role == 'Secrétaire médicale'
            ) {
                return view('cmt.vaccination.index', [
                    'vaccinations' => $vaccinations,
                    'today' => $today,
                    'date' => $date,
                ]);
            }
        }
    }
}
