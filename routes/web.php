<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\DR\ConsultationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['verify' => true]);

//dashboard
Route::get('/dashboard/{type}', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
//admin's users crud
Route::middleware(['auth','role:admin'])->group(function(){
    Route::namespace('App\Http\Controllers\Admin')->prefix('admin')->name('admin.')->group(function(){
        Route::resource('users', 'UsersController');
    });
});

//search users
Route::get('/users/search', [SearchController::class, 'searchUser'])->name('users.search');

//admin's roles crud
Route::middleware(['auth','role:admin'])->group(function(){
    Route::namespace('App\Http\Controllers\Admin')->prefix('admin')->name('admin.')->group(function(){
        Route::resource('roles', 'RolesController');
    });
});

//user's profile
Route::middleware('auth')->group(function(){
    Route::namespace('App\Http\Controllers\User')->prefix('user')->name('user.')->group(function(){
        Route::resource('profile', 'UserController');
    });
});

//admin : zones crud
Route::middleware(['auth','role:Responsable de Reporting,admin'])->group(function(){
    Route::namespace('App\Http\Controllers\DR')->prefix('DR')->name('DR.')->group(function(){
        Route::resource('zones', 'ZonesController');
    });
});

//search zones
// Route::get('/regions/search', [SearchController::class, 'searchRegion'])->name('regions.search');

//admin : regions crud
Route::middleware(['auth','role:Responsable de Reporting,admin,Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\DR')->prefix('DR')->name('DR.')->group(function(){
        Route::resource('regions', 'RegionsController');
    });
});

//admin : cmts crud
Route::middleware(['auth','role:Secrétaire médicale,admin,Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.')->group(function(){
        Route::resource('cmts', 'CmtsController');
    });
});

//search zones
Route::get('/zones/search', [SearchController::class, 'searchZone'])->name('zones.search');


//responsable de reporting : covid crud
Route::middleware(['auth','role:Responsable de Reporting'])->group(function(){
    Route::namespace('App\Http\Controllers\DR')->prefix('DR')->name('DR.')->group(function(){
        Route::resource('consultations', 'consultationsController');
    });
});

Route::get('/findEmploye',[ConsultationsController::class, 'findEmploye'])->name('findEmploye');

//search consultations
Route::get('/consultations/search', [SearchController::class, 'searchConsultation'])->name('consultations.search');

//responsable de reporting : vaccination
Route::middleware(['auth','role:Responsable de Reporting'])->group(function(){
    Route::namespace('App\Http\Controllers\DR')->prefix('DR')->name('DR.')->group(function(){
        Route::resource('vaccinations', 'VaccinationController');
    });
});

//responsable de reporting : releve
Route::middleware(['auth','role:Responsable de Reporting,Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\DR')->prefix('DR')->name('DR.')->group(function(){
        Route::resource('releves', 'ReleveController');
    });
});

//search vaccinations
Route::get('/vaccinations/search', [SearchController::class, 'searchVaccination'])->name('vaccinations.search');

//secrétaire médicale : covid crud
Route::middleware(['auth','role:Secrétaire médicale'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.')->group(function(){
        Route::resource('consultations', 'ConsultationsController');
    });
});

//secrétaire médicale : vaccination
Route::middleware(['auth','role:Secrétaire médicale'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.')->group(function(){
        Route::resource('vaccinations', 'VaccinationController');
    });
});

//secrétaire médicale : activité CMT
Route::middleware(['auth','role:Secrétaire médicale'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.depistage')->group(function(){
        Route::get('depistage', 'DepistageController@showDepistageCMT');
    });
});

//coordinateur : covid 3 CMT
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.covid')->group(function(){
        Route::get('situation_covid', 'CovidController@showSituationCovid');
    });
});

//coordinateur : covid 3 CMT PDF
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.covid.reporting')->group(function(){
        Route::get('generate_reporting_covid', 'CovidController@generateReporting');
    });
});

//coordinateur : covid 3 CMT PDF Quotidien
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.covid.reporting_quotidien')->group(function(){
        Route::get('generate_reporting_quotidien_covid', 'CovidController@generateReportingQuotidien');
    });
});

//coordinateur : vaccination 3 CMT
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.vaccination')->group(function(){
        Route::get('situation_vaccination', 'VaccinationsController@showSituationVaccination');
    });
});

//coordinateur : vaccination 3 CMT PDF
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.vaccination.reporting')->group(function(){
        Route::get('generate_reporting_vaccination', 'VaccinationsController@generateReporting');
    });
});

//coordinateur : vaccination 3 CMT PDF Hebdomadaire
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.vaccination.reporting_hebdomadaire')->group(function(){
        Route::get('generate_reporting_hebdomadaire_vaccination', 'VaccinationsController@generateReportingHebdomadaire');
    });
});

//coordinateur : activité 3 CMT
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.depistages')->group(function(){
        Route::get('situation_depistage', 'DepistagesController@showSituationDepistage');
    });
});

//coordinateur : activité 3 CMT PDF
Route::middleware(['auth','role:Coordinateur gestion social'])->group(function(){
    Route::namespace('App\Http\Controllers\CMT')->prefix('CMT')->name('CMT.depistages.reporting')->group(function(){
        Route::get('generate_reporting_depistage', 'DepistagesController@generateReporting');
    });
});

//inspecteur : covid 3 CMT
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\CMTs')->prefix('CMTs')->name('HSE.CMTs.covid')->group(function(){
        Route::get('situation_covid', 'CovidController@showSituationCovid');
    });
});

//inspecteur : covid 3 CMT PDF
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\CMTs')->prefix('CMTs')->name('HSE.CMTs.covid.reporting')->group(function(){
        Route::get('generate_reporting_covid', 'CovidController@generateReporting');
    });
});

//inspecteur : vaccination 3 CMT
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\CMTs')->prefix('CMTs')->name('HSE.CMTs.vaccination')->group(function(){
        Route::get('situation_vaccination', 'VaccinationController@showSituationVaccination');
    });
});

//inspecteur : vaccination 3 CMT PDF
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\CMTs')->prefix('CMTs')->name('HSE.CMTs.vaccination.reporting')->group(function(){
        Route::get('generate_reporting_vaccination', 'VaccinationController@generateReporting');
    });
});

//inspecteur : covid DRs
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\DRs')->prefix('DRs')->name('HSE.DRs.covid')->group(function(){
        Route::get('situation_covid', 'CovidController@showSituationCovid');
    });
});

//inspecteur : covid DRsPDF
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\DRs')->prefix('DRs')->name('HSE.DRs.covid.reporting')->group(function(){
        Route::get('generate_reporting_covid', 'CovidController@generateReporting');
    });
});

//inspecteur : vaccination DRs
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\DRs')->prefix('DRs')->name('HSE.DRs.vaccination')->group(function(){
        Route::get('situation_vaccination', 'VaccinationController@showSituationVaccination');
    });
});

//inspecteur : vaccination DRs PDF
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\DRs')->prefix('DRs')->name('HSE.DRs.vaccination.reporting')->group(function(){
        Route::get('generate_reporting_vaccination', 'VaccinationController@generateReporting');
    });
});

//inspecteur : zones situation
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\DRs')->prefix('DRs')->name('HSE.DRs.zones')->group(function(){
        Route::get('situation_zones', 'ZonesController@showSituationZones');
    });
});

//inspecteur : zones situation PDF
Route::middleware(['auth','role:Inspécteur de prévention'])->group(function(){
    Route::namespace('App\Http\Controllers\HSE\DRs')->prefix('DRs')->name('HSE.DRs.zones.reporting')->group(function(){
        Route::get('generate_reporting_zones', 'ZonesController@generateReporting');
    });
});


//président cellule de crise : reunion crud
Route::middleware(['auth','role:Président cellue de crise'])->group(function(){
    Route::namespace('App\Http\Controllers')->group(function(){
        Route::resource('reunions', 'ReunionsController');
    });
});
