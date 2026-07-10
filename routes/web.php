<?php

use App\Http\Controllers\AgrochemicalrecordsController;
use App\Http\Controllers\ApprovalcommitteController;
use App\Http\Controllers\dashboardController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FarmentranceController;
use App\Http\Controllers\FarmunitsController;
use App\Http\Controllers\FarmunityieldController;
use App\Http\Controllers\InternalinspectionController;
use App\Http\Controllers\MapImage;
use App\Http\Controllers\MapImageController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\OthercropsrecordsController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReportsectionController;
use App\Http\Controllers\ReportquestionsController;
use App\Http\Controllers\userController;
use App\Http\Controllers\SeasonController;
use Illuminate\Http\Request;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('testview', 'testview')->name('testview');

/** 
Route::view('dashboard', 'dashboard2')
    ->middleware(['auth', 'verified'])
    ->name('dashboard2');
*/
Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/offline-fe', function () {
    return view('offline.farm_entrance_offline'); // return view with x-layouts.offline
});

Route::get('/offline-inspection', function () {
    return view('offline.offline_inspection'); // return view with x-layouts.offline
});

Route::middleware('auth')->group(function () {
    Route::get('offline/farmentrance',[FarmentranceController::class, 'offlinefestart'])->name('offlinefestart'); 

});

Route::post('/api/sync-onboarding', function (Request $request) {
  // Save onboarding form data to DB
  return response()->json(['status' => 'synced']);
});

Route::middleware('auth')->group(function () {
    Route::get('user_admin',[userController::class, 'index'])->name('user_admin'); 
    Route::get('new_user',[userController::class, 'newuser'])->name('newuser');
    Route::post('user_update',[userController::class, 'user_update'])->name('user_update'); 
    Route::post('user_pwd',[userController::class, 'user_pwd'])->name('user_pwd'); 
    Route::get('user_pwd',[userController::class, 'user_pwd'])->name('user_pwd'); 

});

Route::post('/save-map-image', [MapImageController::class, 'store'])->name('saveimage');
Route::get('/testsaveimage', [MapImageController::class, 'test'])->name('testimage');
Route::get('/mapping/updatesavemap', [MapImageController::class,'updatesavedmap'])->name('updatefarmmap');
Route::post('/upload-map', [MapImageController::class, 'upload']);



    Route::middleware(['auth','verified'])->group(function () {
        
        Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
        Route::view('/unauthorized', 'unauthorized')->name('unauthorized');

    });

    /** 
Route::view('farm', 'farm')
    ->middleware(['auth', 'verified'])
    ->name('farm');


Route::view('newfarm', 'newfarm')
    ->middleware(['auth', 'verified'])
    ->name('newfarm');
**/
Route::middleware('auth')->group(function () {
        Route::get('/farm', [FarmController::class, 'index'])->name('index');
        Route::get('/newfarm', [FarmController::class, 'create'])->name('create');
        Route::post('/newfarm/Createfarm', [FarmController::class, 'store']);
        Route::post('/farm/schedule', [FarmController::class, 'newinspectiondate']);
        Route::post('/farm/assignstaff', [FarmController::class, 'assignstaff']);
        Route::get('/farm/view', [FarmController::class, 'displayfarm'])->name('displayfarm');
        Route::post('/farm/updatefarm', [FarmController::class, 'updatefarm']);
        Route::post('/farm/import', [FarmController::class, 'importfarms'])->name('importfarm');
        Route::get('/farm/importlist', [FarmController::class, 'import_list'])->name('import_list');
        Route::get('/farm/onboardinglist', [FarmController::class, 'onboarding'])->name('onboarding');
        Route::get('/farm/annualreport', [FarmController::class, 'annualreport'])->name('annualreport');
        Route::get('/farm/viewcontract',[FarmController::class, 'viewcontract'])->name('viewcontract');
        Route::post('/farm/updatepicture',[FarmController::class, 'updatepicture'])->name('updatepicture');
        Route::get('/farm/disabled', [FarmController::class, 'disabled'])->name('disabled.farms');
        Route::post('/farm/reenable', [FarmController::class, 'reenable'])->name('farm.reenable');
        
    });
    Route::middleware('auth')->group(function () {
        Route::get('/feprofile', [FarmentranceController::class, 'feprofile'])->name('feprofile');
        Route::post('/feprofile/update', [FarmentranceController::class, 'feprofile_update'])->name('feprofile_update');
        Route::get('/beginfe', [FarmentranceController::class, 'begin'])->name('begin');
        Route::post('/addagchems',[AgrochemicalrecordsController::class, 'add'])->name('addchems');
        Route::get('/disablechems',[AgrochemicalrecordsController::class, 'disable'])->name('disablechems');
        Route::post('/addoplots',[OthercropsrecordsController::class, 'add'])->name('addoplots');
        Route::get('/disableoplots',[OthercropsrecordsController::class, 'disable'])->name('disableoplots');
        Route::post('/fequestionnaire', [FarmentranceController::class, 'getfeq'])->name('getfeq');
        Route::get('/disableplot',[FarmentranceController::class, 'disablefu'])->name('disablefarm');
        Route::post('/addvolsold', [FarmentranceController::class, 'addvolsold'])->name('addvolsold');
        Route::get('/disablevolsold',[FarmentranceController::class, 'disablevolsold'])->name('disablevolsold');
        Route::get('/cropdelivered',[FarmentranceController::class, 'cropdelivered'])->name('cropdelivered');
        Route::get('/cropproduced',[FarmentranceController::class, 'cropproduced'])->name('cropproduced');
        });


Route::middleware('auth')->group(function () {
    Route::get('/pdf/test', [PdfController::class, 'test'])->name('test');
    Route::get('/pdf/generate', [PdfController::class, 'generatePDF'])->name('printsheet');
    Route::get('/pdf/generatecontract', [PdfController::class, 'generateContractPDF'])->name('generatecontract');
});

    Route::middleware('auth')->group(function () {
        Route::get('/fu/edit',[FarmunitsController::class, 'edit'])->name('edit_farmunit'); 
        Route::get('/fu/list',[FarmunitsController::class, 'listfunits'])->name('listfunits'); 
        Route::get('/fu/new',[FarmunitsController::class, 'newfunit'])->name('newfunit');
        Route::post('/fu/save', [FarmunitsController::class, 'savefunit'])->name('fusave');
        Route::post('/fu/editfunit', [FarmunitsController::class, 'editfunit'])->name('editfu');
        
    });

        Route::middleware('auth')->group(function () {
        Route::get('/fy/list',[FarmunityieldController::class, 'list'])->name('list_yield'); 
        Route::post('/fy/addyield',[FarmunityieldController::class, 'addyield'])->name('addyield'); 
        Route::post('/fy/updyield',[FarmunityieldController::class, 'updyield'])->name('updyield'); 

        
    });
    

Route::middleware('auth')->group(function () {
    Route::get('report',[ReportsController::class, 'new'])->name('viewreports');
    Route::get('report/new',[ReportsController::class, 'new_report'])->name('new_report');
    Route::post('report/new',[ReportsController::class, 'new_report'])->name('new_report');
    Route::post('report/copy',[ReportsController::class, 'reportcopy'])->name('rcopy');
    Route::post('report/showsection',[ReportsectionController::class, 'showsection'])->name('showsectionreport');
    Route::post('report/getsection',[ReportsectionController::class, 'getsection'])->name('getsection');
    Route::post('report/newsection',[ReportsectionController::class, 'newsection'])->name('newsection');
    Route::post('report/showquestion',[ReportquestionsController::class, 'showquestion'])->name('showquestion');
    Route::post('report/newquestion',[ReportquestionsController::class, 'newquestion'])->name('newquestion');
    Route::post('report/editquestion',[ReportquestionsController::class, 'editquestion'])->name('editquestion');
    Route::post('report/reporttoggle',[ReportsController::class, 'rtoggle'])->name('rtoggle');

    
});


Route::middleware('auth')->group(function () {
    Route::get('inspection',[InternalinspectionController::class, 'index'])->name('inspection');
    Route::get('inspection/new',[InternalinspectionController::class, 'new'])->name('new');
    Route::post('inspection/start',[InternalinspectionController::class, 'start'])->name('start');
    Route::post('inspection/nextsection',[InternalinspectionController::class, 'nextsection'])->name('nextsection');
    Route::post('inspection/continue',[InternalinspectionController::class, 'continue'])->name('continue');
    Route::post('inspection/delete',[InternalinspectionController::class, 'destroy'])->name('destroy');
    Route::get('inspection_approval',[InternalinspectionController::class, 'iapproval'])->name('iapproval');
    Route::post('iapprove',[InternalinspectionController::class, 'iapprove'])->name('iapprove');
    Route::get('iapprove',[InternalinspectionController::class, 'iapprove'])->name('iapprove');
    Route::post('ireject',[InternalinspectionController::class, 'iapprove'])->name('ireject');
    Route::get('inspection/summary',[InternalinspectionController::class,'summarypage'])->name('summarypage');
    Route::get('inspection/summary/pdf',[InternalinspectionController::class,'summarypdf'])->name('summarypdf');
    Route::post('inspection/cancel',[InternalinspectionController::class, 'icancel'])->name('icancel');
    Route::post('inspection/changedate',[InternalinspectionController::class, 'changedate'])->name('changedate');

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


Route::view('/adminconfig/misccodes', 'adminconfig.misccodes')
    ->middleware([
        'auth',
        'verified',
        RoleMiddleware::class . ':ADMINISTRATOR'
    ])
    ->name('codeadmin');


    Route::middleware([
        'auth',
        'verified',
        RoleMiddleware::class . ':ADMINISTRATOR'
    ])->group(function () {

    Route::get('misccode/apprcomm_show',[ApprovalcommitteController::class, 'apprcomm_show'])->name('apprcomm_show');
    Route::post('misccode/apprcomm_add',[ApprovalcommitteController::class, 'apprcomm_add'])->name('apprcomm_add');
    Route::post('misccode/apprcomm_delete',[ApprovalcommitteController::class, 'apprcomm_delete'])->name('apprcomm_delete');

    Route::get('admin/season',             [SeasonController::class, 'index'])->name('season.index');
    Route::post('admin/season/open',       [SeasonController::class, 'open'])->name('season.open');
    Route::post('admin/season/close',      [SeasonController::class, 'close'])->name('season.close');
    Route::post('admin/season/massassign', [SeasonController::class, 'massassign'])->name('season.massassign');
});

require __DIR__.'/auth.php';
