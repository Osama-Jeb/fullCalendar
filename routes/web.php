<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
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

Route::get('/', [HomeController::class, "index"])->name("home.index");

Route::get("/calendar", [CalendarController::class, "index"])->name("calendar.index");

Route::post("/calendar/store", [CalendarController::class, "store"])->name("calendar.store");

Route::put("/calendar/update/cancel/{id}", [CalendarController::class, "cancel"])->name("calendar.cancel");

Route::put("/calendar/update/{id}", [CalendarController::class, "update"])->name("calendar.update");