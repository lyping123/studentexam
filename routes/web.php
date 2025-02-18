<?php

use App\Http\Controllers\examController;
use Illuminate\Support\Facades\Route;

Route::get('/',[examController::class,'index'])->name("exam.index");

Route::post("/uploadJson",[examController::class,'uploadJson'])->name("exam.uploadJson");
Route::delete("/delete",[examController::class,'delete'])->name("exam.delete");
