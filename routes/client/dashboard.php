<?php
use \Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;

Route::post('/dashboard/summary',[DashboardController::class,'summary']);
Route::post('/dashboard/summary/filter',[DashboardController::class,'filterSummary']);
