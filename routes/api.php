<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\QuizController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller( AuthController::class )->group( function() {
    Route::post( '/login', 'login' );
    Route::post( '/register', 'register' );
} );

Route::middleware(['auth:sanctum'])->group( function() {
    Route::post( '/logout', [ AuthController::class, 'logout' ] );
} );

Route::controller( LocationController::class )->group( function() {
    Route::get( 'locations', 'getAll' );
    Route::get( 'edit_location', 'edit_location' );
    Route::post( 'update_location/{id}', 'update_location' );
} );

Route::controller( QuizController::class )
    ->middleware('auth:sanctum')
    ->prefix( "v1" )
    ->group( function() {
        Route::get( '/quizzes', 'index' );
        Route::post( '/quizzes', 'store' );
        Route::put( '/quizzes/{id}', 'update' );
        Route::delete( '/quizzes/{id}', 'delete' );
} );