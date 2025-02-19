<?php

use App\Http\Controllers\ajaxController;
use App\Http\Controllers\examController;
use App\Http\Controllers\userContorller;
use Illuminate\Support\Facades\Route;

Route::get('/',[examController::class,'index'])->name("exam.index");

Route::get("/login",[userContorller::class,'login'])->name("user.login");
Route::post("/login",[userContorller::class,'authentication'])->name("user.login");
Route::get("/register",[userContorller::class,'register'])->name("user.register");
Route::post("/register",[userContorller::class,'register_user'])->name("user.register");

Route::get("/logout",[userContorller::class,'logout'])->name("user.logout");
Route::get("search",[examController::class,'search'])->name("exam.search");
Route::post("/uploadJson",[examController::class,'uploadJson'])->name("exam.uploadJson");
Route::get("/subject_title",[ajaxController::class,'getSubject'])->name("subject_title.search");

Route::delete("/delete",[examController::class,'delete'])->name("exam.delete");
