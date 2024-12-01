<?php

use App\Http\Controllers\FinancesController;
use App\Http\Controllers\SongsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\GeneratorController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Storage;

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
        Route::post('zaiks/generate', [GeneratorController::class, 'zaiks'] )->name('generateZAiKS');
        
        Route::get('/contract-generator', [DashboardController::class, 'contractGenerator'])->name('contract-generator');
        Route::post('/contract-generator', [FormController::class, 'updateMember'] );
        Route::post('/contract-generator/generate', [GeneratorController::class, 'contract'] )->name('generateContract');

        Route::get('/events', [DashboardController::class, 'events'])->name('eventy');
        Route::get('/events/{id}', [DashboardController::class, 'event'])->name('events.show');
        Route::post('/events/{id}', [EventController::class, 'postEditEvent'] );

        Route::get('/contracts', [DashboardController::class, 'contracts'])->name('contracts');
        Route::get('/contracts/{id}', [DashboardController::class, 'contract'])->name('contracts.show');
        Route::post('/contracts', [FormController::class, 'newContract'] )->name('newContract');

        //songs
        Route::get('/songs', [SongsController::class, 'index'])->name('songs');

        Route::get('/finances', [FinancesController::class, 'index'])->name('finances');
        Route::post('/finances', [FinancesController::class, 'newTransaction'] )->name('newTransaction');
        Route::post('/finances/{id}', [FinancesController::class, 'editTransaction'] );
        Route::get('/finances/{id}', [FinancesController::class, 'displayTransaction'] )->name('editTransaction')->where('id', '[0-9]+');
        Route::get('/finances/delete/{id}', [FinancesController::class, 'deleteTransaction'])->name('deleteTransaction');

        Route::get('/finances/source', function () {
            $file = Storage::disk('public')->get('transactions.json');
            return response($file, 200)
                ->header('Content-Type', 'application/json')
                ->header('Access-Control-Allow-Origin', '*');
        });

        Route::get('finances/categories', [FinancesController::class, 'categories'] )->name('categories');
        Route::post('finances/categories', [FinancesController::class, 'newCategory'] )->name('newCategory');
        Route::post('finances/categories/{id}', [FinancesController::class, 'editCategory'] );
    


    });

});

require __DIR__.'/auth.php';
