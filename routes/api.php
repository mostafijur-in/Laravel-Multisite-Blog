<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\SubscriberController;
use App\Http\Controllers\API\WebsiteController;

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

// Login
Route::post('/create-token', [AuthController::class, 'create_token']);
Route::post('/register', [AuthController::class, 'register']);

// User subscription
Route::post('/subscriber', [SubscriberController::class, 'subscribe']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    // Website routes
    Route::post('/websites', [WebsiteController::class, 'index']);
    Route::post('/websites/create', [WebsiteController::class, 'store']);

    // Post routes
    Route::post('/posts', [PostController::class, 'index']);
    Route::post('/posts/create', [PostController::class, 'store']);
    Route::post('/posts/update-status', [PostController::class, 'update_status']);

});
