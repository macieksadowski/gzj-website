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

        Route::get('{any}', function () {
            return view('angular');
        })->where('any', '.*');

    });

});

require __DIR__.'/auth.php';
