<?php

use App\Http\Controllers\ProvinceController;
use App\Models\Province;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [ProvinceController::class, 'visitorsView']);
Route::get('/', [ProvinceController::class, 'thematicView']);
