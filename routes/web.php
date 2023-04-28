<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnrollmentController;

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


Route::get('/', [EnrollmentController::class, 'index']);
Route::get('/enrollment/search', [EnrollmentController::class, 'search']);
Route::resource('enrollment', EnrollmentController::class);