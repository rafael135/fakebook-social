<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/user/{id}/posts", [UserController::class, "getUserPosts"])->withoutMiddleware([Authenticate::class]);
Route::get("/user/{id}", [UserController::class, "getUserById"])->withoutMiddleware([Authenticate::class]);

Route::post("/post/new", [PostController::class, "newPost"])->withoutMiddleware(Authenticate::class)->name("api.post.new");
Route::get("/post/{id}/comments", [PostController::class, "getComments"])->withoutMiddleware([Authenticate::class]);
Route::get("/post/{id}", [PostController::class, "getPostById"])->withoutMiddleware([Authenticate::class]);

Route::post("/post/{id}/like", [PostController::class, "likePostById"])->withoutMiddleware([Authenticate::class])->name("api.post.like");


Route::post("/user/register", [AuthController::class, "createUser"])->withoutMiddleware([Authenticate::class]);