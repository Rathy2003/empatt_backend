<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Client\RequestLeaveController;

    Route::post('request_leave',[RequestLeaveController::class,'store']);
