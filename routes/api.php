<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginValidationController;
use App\Http\Controllers\API\AuthorizationController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/v1/login/validation', [LoginValidationController::class, 'loginValidation'])->name('api.login.validation');
Route::post('/v1/authorize', [AuthorizationController::class, 'authorizePage'])->name('authorize.page');