<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsSourceController;
use App\Http\Controllers\ArticleController;


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


Route::prefix('auth')->group(function () {
    
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
  

});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::get('/fetchNews', [NewsSourceController::class, 'fetchNews']);

// Route to fetch articles with filters and pagination
Route::get('articles', [ArticleController::class, 'index']);

// Route to fetch a single article by ID
Route::get('articles/{id}', [ArticleController::class, 'showById']);

// Route to fetch a single article by slug
Route::get('articles/slug/{slug}', [ArticleController::class, 'showBySlug']);
