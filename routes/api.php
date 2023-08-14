<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupPostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PagePostController;
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

// Perfil de usuário e interações
Route::prefix("/user")->group(function () {
    Route::get("/{id}/posts", [UserController::class, "getUserPosts"])->withoutMiddleware([Authenticate::class]);
    Route::get("/{id}", [UserController::class, "getUserById"])->withoutMiddleware([Authenticate::class]);

    Route::post("/{id}/follow", [UserController::class, "followUser"])->withoutMiddleware([Authenticate::class])->name("api.user.follow");
});
//////////////////////////////


Route::post("/chat/messages", [MessageController::class, "getChatMessages"])->withoutMiddleware([Authenticate::class])->block(2, 2)->name("api.chat.getMessages");
Route::post("/message/new", [MessageController::class, "newMessageTo"])->withoutMiddleware([Authenticate::class])->name("api.message.new");

// Posts de Usuario
Route::prefix("/post")->group(function () {
    Route::get("/{id}/comments", [PostController::class, "getComments"])->withoutMiddleware([Authenticate::class])->name("api.post.comments");
    Route::patch("/{id}", [PostController::class, "getPostById"])->withoutMiddleware([Authenticate::class])->name("api.post.get");

    Route::post("/", [PostController::class, "newPost"])->withoutMiddleware([Authenticate::class])->name("api.post.new");
    Route::post("/{id}/comments", [CommentController::class, "newComment"])->withoutMiddleware([Authenticate::class])->name("api.post.comments.new");
    Route::delete("/{id}", [PostController::class, "deletePost"])->withoutMiddleware(Authenticate::class)->name("api.post.delete");

    Route::post("/{id}/like", [PostController::class, "likePostById"])->withoutMiddleware([Authenticate::class])->name("api.post.like");
});
//////////////////////////////

// Comentarios em Posts
Route::prefix("/comments")->group(function () {
    Route::post("/{id}", [CommentController::class, "likeComment"])->withoutMiddleware([Authenticate::class])->name("api.comments.like");

    Route::post("/{id}/reply", [CommentController::class, "replyComment"])->withoutMiddleware([Authenticate::class])->name("api.comments.reply");
});

// Posts de grupos
Route::prefix("/group")->group(function () {
    Route::get("/{uniqueUrl}/posts", [GroupPostController::class, "posts"])->withoutMiddleware([Authenticate::class])->name("api.group.posts");
    Route::get("/{uniqueUrl}/post/{id}", [GroupPostController::class, "post"])->withoutMiddleware([Authenticate::class])->name("api.group.post");
});
//////////////////////////////

// Posts de páginas
Route::prefix("/page")->group(function () {
    Route::get("/{uniqueUrl}/posts", [PagePostController::class, "posts"])->withoutMiddleware([Authenticate::class])->name("api.page.posts");
    Route::get("/{uniqueUrl}/post/{id}", [PagePostController::class, "post"])->withoutMiddleware([Authenticate::class])->name("api.page.post");
});
/////////////////////////////


Route::post("/user/register", [AuthController::class, "createUser"])->withoutMiddleware([Authenticate::class]);