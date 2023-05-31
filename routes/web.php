<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", [HomeController::class, "index"])->name("home");

Route::get("/register", [AuthController::class, "registerPage"])->name("auth.register");
Route::get("/login", [AuthController::class, "loginPage"])->name("auth.login");

Route::post("register", [AuthController::class, "registerAction"])->name("auth.registerAction");
Route::post("/login", [AuthController::class, "loginAction"])->name("auth.loginAction");