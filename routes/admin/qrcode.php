<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QRCodeController;

Route::get("/qrcode",[QRCodeController::class,'showQRCode'])->name('qrcode');
Route::post("/qrcode/create",[QRCodeController::class,'save'])->name('createQRCode');
Route::post("/qrcode/save/image",[QRCodeController::class,'saveQRCodeImage'])->name('saveQRCodeImage');
//Route::post("/user/update",[QRCodeController::class,'update'])->name('saveUser');
Route::post("/qrcode/delete",[QRCodeController::class,'delete'])->name('deleteQRCode');
