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
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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


Route::prefix('v2')->middleware('auth:api')->group(function () {

Route::apiResource('cards', CardController::class);
Route::apiResource('cardsVersion', CardVersionController::class);
Route::apiResource('deckCards', DeckCardController::class);
Route::apiResource('decks', DeckController::class);
Route::apiResource('expansions', ExpansionController::class);
Route::apiResource('role', RoleController::class);
Route::apiResource('userCards', UserCardController::class);
Route::apiResource('users', UserController::class);
Route::delete('/deckCards/byDeck/{deck_id}', [DeckCardController::class, 'destroyByDeck']);

});


Route::post('register', [AuthApiController::class, 'register']);
Route::post('login', [AuthApiController::class, 'login']);




Route::get('/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->email))) {
        abort(403, 'Hash no válido');
    }

    if (! URL::hasValidSignature(request())) {
        abort(403, 'Enlace expirado o inválido');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return response('Correo verificado correctamente');
})->name('verification.verify');