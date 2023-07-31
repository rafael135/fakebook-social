<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendRelationsController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyCsrfToken;
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

Route::get("/search", [SearchController::class, "index"])->name("search");

Route::get("/register", [AuthController::class, "registerPage"])->name("auth.register");
Route::get("/login", [AuthController::class, "loginPage"])->name("auth.login");

Route::post("/register", [AuthController::class, "registerAction"])->name("auth.registerAction");
Route::post("/login", [AuthController::class, "loginAction"])->name("auth.loginAction");


Route::prefix("/user")->group(function () {
    Route::post("/user/change/avatar", [UserController::class, "changeAvatar"])->name("user.change.avatar");
    Route::post("/user/change/cover", [UserController::class, "changeCover"])->name("user.change.cover");

    Route::get("/config", [UserController::class, "userConfig"])->name("user.config");
    Route::get("/friends", [FriendRelationsController::class, "showFriends"])->name("user.friends");
    Route::get("/messages", [MessageController::class, "showMessages"])->name("user.messages");

    Route::get("/{uniqueUrl}", [UserController::class, "showUser"])->name("user.profile");
    Route::get("/{uniqueUrl}/followers", [UserController::class, "showUserFollowers"])->name("user.followers");
    Route::get("/{uniqueUrl}/following", [UserController::class, "showUserFollowing"])->name("user.following");
});



Route::prefix("/page")->group(function () {
    Route::get("/create", [PageController::class, "create"])->name("page.create");
    Route::get("/{uniqueUrl}", [PageController::class, "show"])->name("page.show");
});

Route::prefix("/group")->group(function () {
    Route::get("/create", [GroupController::class, "create"])->name("group.create");
    Route::get("/{uniqueUrl}", [GroupController::class, "show"])->name("group.show");
});


Route::get("/logout", [AuthController::class, "logout"])->name("user.logout");