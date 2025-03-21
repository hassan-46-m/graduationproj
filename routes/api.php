<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\MedicineController;

Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);


Route::get('/',[MedicineController::class,'index']);
Route::get('/{medicine_name}',[MedicineController::class,'show']);
Route::get('/search/{var}',[MedicineController::class,'search']);
//Route::get('/random',[MedicineController::class,'getRandomDrugs']);




