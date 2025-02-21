<?php

use App\Http\Controllers\ajaxController;
use App\Http\Controllers\examController;
use App\Http\Controllers\logController;
use App\Http\Controllers\userContorller;
use App\Http\Middleware\checkauth;
use App\Models\exam_question;
use Illuminate\Support\Facades\Route;



Route::get("/login",[userContorller::class,'login'])->name("user.login");
Route::post("/login",[userContorller::class,'authentication'])->name("user.login");
Route::get("/register",[userContorller::class,'register'])->name("user.register");
Route::post("/register",[userContorller::class,'register_user'])->name("user.register");
Route::get("/logout",[userContorller::class,'logout'])->name("user.logout");

Route::middleware(checkauth::class)->group(function(){
    Route::get('/',[examController::class,'index'])->name("exam.index");
    Route::get("search",[examController::class,'search'])->name("exam.search");
    Route::post("/uploadJson",[examController::class,'uploadJson'])->name("exam.uploadJson");
    Route::get("/subject_title",[ajaxController::class,'getSubject'])->name("subject_title.search");
    Route::delete("/delete",[examController::class,'delete'])->name("exam.delete");
    
    
    Route::get("/setquestion",[examController::class,"setquestionPage"])->name("exam.stuquestiton");
    Route::get("/viewsetquestion",[examController::class,"viewsetquestionPage"])->name("exam.viewsetquestion");
    Route::post("/setquestion",[examController::class,"setquestion"])->name("exam.setquestion");
    Route::put("/updatesetquestion",[examController::class,"updatesetquestion"])->name("exam.update.setquestion");
    Route::delete("/delete/exam/",[examController::class,'deletesetupAll'])->name("exam.setup.deleteAll");
    Route::delete("/delete/{exam_question}/exam/",[examController::class,'deletesetup'])->name("exam.setup.delete");

    Route::get("/showlog",[logController::class,'showlog'])->name("user.showlog");
    Route::get("/undo/{log_id}",[logController::class,'undo'])->name("undo.action");

});