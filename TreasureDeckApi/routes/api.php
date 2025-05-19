<?php


use App\Http\Controllers\v2\CardController;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\v2\CardVersionController;
use App\Http\Controllers\v2\DeckCardController;
use App\Http\Controllers\v2\DeckController;
use App\Http\Controllers\v2\DeckStatController;
use App\Http\Controllers\v2\ExpansionController;
use App\Http\Controllers\v2\RoleController;
use App\Http\Controllers\v2\UserCardController;
use App\Http\Controllers\v2\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v2')->middleware('auth:sanctum')->group(function () {

Route::apiResource('cards', CardController::class);
Route::apiResource('cardsVersion', CardVersionController::class);
Route::apiResource('deckCards', DeckCardController::class);
Route::apiResource('decks', DeckController::class);
Route::apiResource('deckStats', DeckStatController::class);
Route::apiResource('expansions', ExpansionController::class);
Route::apiResource('role', RoleController::class);
Route::apiResource('userCards', UserCardController::class);
Route::apiResource('users', UserController::class);

});


Route::post('register', [AuthApiController::class, 'register']);
Route::post('login', [AuthApiController::class, 'login']);


Route::get('email/verify/{id}/{hash}', function ($id, $hash) {
    return 'Verificación';
})->name('verification.verify');