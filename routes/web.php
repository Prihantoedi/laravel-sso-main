<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'index'])->name('welcome.page');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginAttempt'])->name('login.attempt');
Route::get('/redirect/{token}/{client}', [LoginController::class, 'loginRedirectToClient']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/authentication/{client}', [LoginController::class, 'authentication'])->name('sso.authentication');
