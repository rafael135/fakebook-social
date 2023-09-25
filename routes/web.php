<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendRelationsController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
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
Route::post("/register", [AuthController::class, "registerAction"])->name("auth.registerAction");

Route::get("/login", [AuthController::class, "loginPage"])->name("auth.login");
Route::post("/login", [AuthController::class, "loginAction"])->name("auth.loginAction");



Route::prefix("/user")->group(function () {
    Route::post("/change/avatar", [UserController::class, "changeAvatar"])->middleware([Authenticate::class])->name("user.change.avatar");
    Route::post("/change/cover", [UserController::class, "changeCover"])->middleware([Authenticate::class])->name("user.change.cover");

    Route::get("/config", [UserController::class, "userConfig"])->middleware([Authenticate::class])->name("user.config");
    Route::get("/friends", [FriendRelationsController::class, "showFriends"])->middleware([Authenticate::class])->name("user.friends");
    Route::get("/messages", [MessageController::class, "showMessages"])->middleware([Authenticate::class])->name("user.messages");

    Route::get("/{uniqueUrl}", [UserController::class, "showUser"])->name("user.profile");
    Route::get("/{uniqueUrl}/friends", [FriendRelationsController::class, "showUserListFriends"])->name("user.listFriends");
    Route::get("/{uniqueUrl}/followers", [UserController::class, "showUserFollowers"])->name("user.followers");
    Route::get("/{uniqueUrl}/following", [UserController::class, "showUserFollowing"])->name("user.following");
});



Route::prefix("/page")->group(function () {

    Route::get("/create", [PageController::class, "create"])->middleware([Authenticate::class])->name("page.create");
    Route::post("/create", [PageController::class, "createAction"])->middleware([Authenticate::class])->name("page.createAction");


    Route::get("/{uniqueUrl}", [PageController::class, "show"])->name("page.show");
    Route::get("/{uniqueUrl}/followers", [PageController::class, "followers"])->name("page.followers");
    Route::get("/{uniqueUrl}/info", [PageController::class, "info"])->name("page.info");

    Route::get("/{uniqueUrl}/config", [PageController::class, "config"])->middleware([Authenticate::class])->name("page.config");


    Route::post("/{uniqueUrl}/change/image", [PageController::class, "changeImage"])->middleware([Authenticate::class])->name("page.changeImage");
    Route::post("/{uniqueUrl}/change/cover", [PageController::class, "changeCover"])->middleware([Authenticate::class])->name("page.changeCover");
});

Route::prefix("/group")->group(function () {
    Route::get("/create", [GroupController::class, "create"])->middleware([Authenticate::class])->name("group.create");
    Route::post("/create", [GroupController::class, "createAction"])->middleware([Authenticate::class])->name("group.createAction");

    Route::get("/{uniqueUrl}", [GroupController::class, "show"])->name("group.show");
});


Route::get("/logout", [AuthController::class, "logout"])->middleware([Authenticate::class])->name("user.logout");