<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIs\FeedbackController;
use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\MedicineController;

Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/',[MedicineController::class,'index']);
    Route::get('/show/{medicine_name}',[MedicineController::class,'show']);
    Route::get('/search/{var}',[MedicineController::class,'search']);
    Route::get('/similaruse-medicines/{name}', [MedicineController::class, 'getSimilaruseMedicines']);
    Route::get('/similarcomposition-medicines/{name}', [MedicineController::class, 'getSimilarCompositionMedicines']);
    route::post('/createfeedback',[FeedbackController::class,'store']);
    Route::put('/updatefeedback/{id}', [FeedbackController::class, 'update']);
    Route::delete('/deletefeedback/{id}', [FeedbackController::class, 'destroy']);
});
Route::get('/',[MedicineController::class,'index']);


Route::get('/search/{var}',[MedicineController::class,'search']);

//Route::get('/random',[MedicineController::class,'getRandomDrugs']);




