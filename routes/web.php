<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendRelationsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
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

Route::post("/register", [AuthController::class, "registerAction"])->name("auth.registerAction");
Route::post("/login", [AuthController::class, "loginAction"])->name("auth.loginAction");


Route::get("/user/{uniqueUrl}", [UserController::class, "showUser"])->name("user.profile");
Route::get("/user/{uniqueUrl}/followers", [UserController::class, "showUserFollowers"])->name("user.followers");
Route::get("/user/{uniqueUrl}/following", [UserController::class, "showUserFollowing"])->name("user.following");

Route::get("/user/config", [UserController::class, "userConfig"])->name("user.config");

Route::get("/user/friends", [FriendRelationsController::class, "showFriends"])->name("user.friends");
Route::get("/user/messages", [MessageController::class, "showMessages"])->name("user.messages");

Route::get("/logout", [AuthController::class, "logout"])->name("user.logout");