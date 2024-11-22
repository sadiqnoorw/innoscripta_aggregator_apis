<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsSourceController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserPreferencesController;


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

Route::prefix('auth')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('preferences', [UserPreferencesController::class, 'setPreferences']);
    Route::get('preferences', [UserPreferencesController::class, 'getPreferences']);
    Route::get('personalized-news', [UserPreferencesController::class, 'getPersonalizedNewsFeed']);
});

Route::middleware('throttle:fetch-actions')->group(function () {
    Route::get('/fetchNews', [NewsSourceController::class, 'fetchNews']);
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{id}', [ArticleController::class, 'showById']);
    Route::get('articles/slug/{slug}', [ArticleController::class, 'showBySlug']);
});


Route::middleware('throttle:auth-actions')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});
