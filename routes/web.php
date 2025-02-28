<?php

use App\Http\Controllers\ajaxController;
use App\Http\Controllers\demoExamController;
use App\Http\Controllers\examController;
use App\Http\Controllers\logController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\userContorller;
use App\Http\Middleware\checkauth;

use Illuminate\Support\Facades\Route;


Route::get("/login",[userContorller::class,'login'])->name("user.login");
Route::post("/login",[userContorller::class,'authentication'])->name("user.login");
Route::get("/studentlogin",[userContorller::class,'studentLoginPage'])->name("student.login");
Route::post("/studentlogin",[userContorller::class,'studentLogin'])->name("student.login");
Route::get("/register",[userContorller::class,'register'])->name("admin.register");
Route::post("/register",[userContorller::class,'register_user'])->name("admin.register");
Route::get("/studentregister",[userContorller::class,'studentRegisterPage'])->name("student.register");
Route::post("/studentregister",[userContorller::class,'studentRegister'])->name("student.register");
Route::get("/logout",[userContorller::class,'logout'])->name("user.logout");



Route::middleware(checkauth::class)->group(function(){
    Route::get('/',[examController::class,'index'])->name("exam.index");
    Route::get("search",[examController::class,'search'])->name("exam.search");
    Route::post("/uploadJson",[examController::class,'uploadJson'])->name("exam.uploadJson");
    Route::get("/subject_title",[ajaxController::class,'getSubject'])->name("subject_title.search");
    Route::delete("/delete",[examController::class,'delete'])->name("exam.delete");
    
    
    Route::get("/setquestion",[examController::class,"setquestionPage"])->name("exam.stuquestiton");
    Route::get("/viewsetquestion",[examController::class,"viewsetquestionPage"])->name("exam.viewsetquestion");
    Route::get("/setquestion/{question_paper}",[examController::class,"updatequestionPage"])->name("exam.editquestion");
    Route::post("/setquestion",[examController::class,"setquestion"])->name("exam.setquestion");
    Route::get("/question_paper/setting/{id}",[ajaxController::class,"getQuestionPaperSetting"])->name("exam.setting");
    Route::post("/question_paper/setting/{id}",[examController::class,"saveQuestionPaperSetting"])->name("exam.setting.save");
    Route::put("/updatesetquestion",[examController::class,"updatesetquestion"])->name("exam.update.setquestion");
    Route::put("/updateeditquestion/{question_paper}",[examController::class,"updatequestion"])->name("exam.update.editquestion");
    Route::delete("/delete/exam/",[examController::class,'deletesetupAll'])->name("exam.setup.deleteAll");
    Route::delete("/delete/{exam_question}/exam/",[examController::class,'deletesetup'])->name("exam.setup.delete");
    Route::delete("deletesetexam/{question_paper}/exam",[examController::class,'deletesetexam'])->name("exam.set.delete");
    Route::delete("/delete/updateexam/{question_paper}",[examController::class,"deleteupdateAll"])->name("exam.update.deleteAll");
    Route::delete("/delete/exam/{exam_question}/{question_paper}",[examController::class,'deleteupdate'])->name("exam.update.delete");

    

    Route::get("/demoquestion/{question_paper}",[demoExamController::class,"index"])->name("demoexam.index");
    Route::post('/exam/submit', [demoExamController::class, 'submitExam'])->name('demoexam.submit');
    Route::get('/examreview/{ExamAttempt}',[demoExamController::class,'examReview'])->name("demoexam.review");
    Route::get("/examreviewlist",[demoExamController::class,'examReviewlist'])->name("demoexam.review.list");

    Route::get("/showlog",[logController::class,'showlog'])->name("user.showlog");
    Route::get("/undo/{log_id}",[logController::class,'undo'])->name("undo.action");

});
