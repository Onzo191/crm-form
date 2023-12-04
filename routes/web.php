<?php

use App\Http\Controllers\SubmitFormController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [SubmitFormController::class, 'view']);
Route::post('/submit-form-webhook', [SubmitFormController::class, 'postWithWebhook']);
Route::post('/submit-form-api', [SubmitFormController::class, 'postWithApi']);
