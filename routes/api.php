<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FinancesController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\GeneratorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events', [EventController::class, 'getAllEvents']);
    Route::get('/events/{id}', [EventController::class, 'getEvent']);
    Route::get('/event-types', [EventController::class, 'getEventTypes']);
    Route::get('/events-search', [EventController::class, 'searchEvents']);

    Route::post('/events/new', [EventController::class, 'createEvent']);
    Route::post('/events/{id}/edit', [EventController::class, 'editEvent']);
    Route::delete('/events/{id}', [EventController::class, 'deleteEvent']);

    Route::get('/contracts', [EventController::class, 'getAllContracts']);
    Route::get('/contracts/summaryPerYear', [EventController::class, 'getContractsSummaryPerYear']);
    
    Route::get('/members', [MemberController::class, 'getAllMembers']);
    Route::get('/members/names', [MemberController::class, 'getAllMembersNames']);

    Route::get('/transactions', [FinancesController::class, 'getAllTransactions']);
    Route::get('/transactions/{id}', [FinancesController::class, 'getTransaction']);
    Route::delete('/transactions/{id}', [FinancesController::class, 'deleteTransactionApi']);
    Route::post('/transactions/{id}/edit', [FinancesController::class, 'editTransactionApi']);
    Route::post('/transactions/new', [FinancesController::class, 'createTransaction']);
    Route::get('/transactions-saldo', [FinancesController::class, 'getTotalSaldoJson']);

    Route::get('/transaction-categories', [FinancesController::class, 'getAllCategories']);

    Route::get('/songs', [SongsController::class, 'getAllSongs']);
    Route::get('/songs/{id}', [SongsController::class, 'getSong']);
    Route::post('/songs/new', [SongsController::class, 'createSong']);
    Route::post('/songs/{id}/edit', [SongsController::class, 'editSong']);
    Route::delete('/songs/{id}', [SongsController::class, 'deleteSong']);

    Route::post('/zaiks/generate', [GeneratorController::class, 'zaiks'] )->name('generateZAiKS');
});
