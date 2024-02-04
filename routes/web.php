<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\GeneratorController;

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



Route::get('/', [PublicController::class,'index']);
// Route::get('/o-zespole', [PublicController::class,'about']);
// Route::get('/koncerty', [PublicController::class,'events']);
// Route::get('/nagrania', [PublicController::class,'records']);

Route::get('/presspack', function () {
    return redirect()->away('https://drive.google.com/drive/folders/1Y5HuLKQnPW9BcxFSZzCFTOPeKsxM6ZfD?usp=sharing');
});

Route::get('/drugi_akapit', function () {
    return redirect()->away('https://songwhip.com/glownyzaworjazzu/drugi-akapit');
});

Route::get('/noca_w_pewnym_miescie', function () {
    return redirect()->away('https://songwhip.com/glownyzaworjazzu/noca-w-pewnym-miescie');
});

Route::get('/poczatek', function () {
    return redirect()->away('https://songwhip.com/glownyzaworjazzu/poczatek');
});


Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => '/dashboard'], function () {

        Route::get('/', [DashboardController::class,'dashboard'])->name('dashboard');
        Route::get('/zaiks', [DashboardController::class, 'zaiks'])->name('zaiks');
        Route::get('/contract-generator', [DashboardController::class, 'contractGenerator'])->name('contract-generator');
        Route::get('/events', [DashboardController::class, 'events'])->name('eventy');
        Route::get('/events/{id}', [DashboardController::class, 'event'])->name('events.show');
        Route::get('/contracts', [DashboardController::class, 'contracts'])->name('contracts');
        Route::get('/contracts/{id}', [DashboardController::class, 'contract'])->name('contracts.show');

        Route::post('zaiks/generate', [GeneratorController::class, 'zaiks'] )->name('generateZAiKS');
        Route::post('/events/{id}', [FormController::class, 'addMemberToContract'] );
        Route::post('/contracts', [FormController::class, 'newContract'] );
        Route::post('/contract-generator', [FormController::class, 'updateMember'] );
        Route::post('/contract-generator/generate', [GeneratorController::class, 'contract'] )->name('generateContract');


    });

});

require __DIR__.'/auth.php';
