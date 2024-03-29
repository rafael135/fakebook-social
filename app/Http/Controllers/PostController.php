<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public static function getIfPostWasLiked(User $targetUser, Post $targetPost) : bool {
        $existingRelation = DB::table("posts_likes")->select(["id"])->where("post_id", "=", $targetPost->id)->where("user_id", "=", $targetUser->id)->get();

        if($existingRelation->count() == 0) {
            return false;
        } else {
            return true;
        }
    }


    public function getPostById(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);
        $userToken = $request->input("userToken", null);

        if($id == null || $userToken == null) {
            return response()->json([
                "response" => "Parameter 'id' not found",
                "status" => 400
            ], 400);
        }

        $post = Post::find($id);

        $rawUser = DB::table("users")->select(["id"])->where("remember_token", "=", $userToken)->get();

        if($post == null || $rawUser->count() == 0) {
            return response()->json([
                "response" => "Post with id: $id not found",
                "status" => 404
            ], 404);
        }

        $loggedUser = User::find($rawUser->first()->id);

        $author = $post->user;
        $author = UserController::checkUser($author);

        $checkLiked = self::getIfPostWasLiked($loggedUser, $post);

        if($checkLiked == true) {
            $post["is_liked"] = true;
        } else {
            $post["is_liked"] = false;
        }

        return response()->json([
            "response" => [
                "post" => $post,
                "user" => $author
            ],
            "status" => 200
        ], 200);
    }

    public function getComments(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);
        $token = $request->header("usrToken", null);

        if($id == null) {
            return response()->json([
                "response" => "Parameter 'id' not found",
                "status" => 400
            ], 400);
        }

        $post = Post::find($id);

        if($post == null) {
            return response()->json([
                "response" => "Post with id: $id not found",
                "status" => 404
            ], 404);
        }

        $raw = DB::table("users")->select(["id"])->where("remember_token", "=", $token)->get();

        $loggedUser = null;

        if($raw->count() > 0) {
            $loggedUser = User::find($raw->first()->id);
        }

        $comments = $post->comments;
        $comments = CommentController::getCommentsAuthor($comments);
        $comments = CommentController::getLikedComments($comments, $loggedUser);

        return response()->json([
            "response" => $comments,
            "status" => 200
        ], 200);
    }



    public function likePostById(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", false);
        $userToken = $request->input("userToken", false);

        if($id == false || $userToken == false) {
            return response()->json([
                "response" => "Parâmetro ou corpo da requisição não encontrado!",
                "status" => 400
            ], 400);
        }

        $userId = DB::table("users")->select(["id"])->where("remember_token", "=", $userToken)->get()->toArray();

        if(count($userId) == 0) {
            return response()->json([
                "response" => false,
                "status" => 401
            ], 401);
        }

        $userId = $userId[0]->id;

        $loggedUser = User::find($userId);

        $likePost = DB::table("posts_likes")->select()->where("post_id", "=", $id)->where("user_id", "=", $loggedUser->id)->get();

        $liked = null;

        if(count($likePost) > 0) {
            $post = PostLike::find($likePost[0]->id);
            $post->delete();
            $liked = false;
        } else {
            $post = PostLike::create([
                "post_id" => $id,
                "user_id" => $userId
            ]);
            $liked = true;
        }

        if($liked == true) {
            return response()->json([
                "response" => $post,
                "status" => 201
            ], 201);
        } else {
            return response()->json([
                "response" => false,
                "status" => 200
            ], 200);
        }

    }


    function newPost(Request $request) : JsonResponse {
        $userToken = $request->input("userToken", false);
        $body = $request->input("body", false);


        if($userToken == false || $body == false) {
            return response()->json([
                "response" => "Dados incompletos ou errados!",
                "status" => 404
            ], 404);
        }

        $rawData = DB::table("users")->select(["id"])->where("remember_token", "=", $userToken)->get();

        if($rawData->first() == null) {
            return response()->json([
                "response" => "Não autorizado",
                "status" => 403
            ], 403);
        }

        $userId = $rawData->first()->id;

        //$author = User::find($userId);
        $data = [
            "user_id" => $userId,
            "type" => "post",
            "body" => $body
        ];

        $newPost = Post::create($data);

        //$newPost->save();
        $user = User::find($userId);

        return response()->json([
            "response" => [
                "post" => $newPost,
                "user" => $user
            ],
            "status" => 201
        ], 201);
    }

    public function deletePost(Request $request) : JsonResponse {
        $userToken = $request->input("userToken", false);
        $id = $request->route()->parameter("id", false);

        if($userToken == false || $id == false) {
            return response()->json([
                "response" => "Não autorizado",
                "status" => 403
            ], 403);
        }

        $rawUser = DB::table("users")->select(["id"])->where("remember_token", "=", $userToken)->get();

        if($rawUser->first() != null) {
            $post = Post::find($id);
            $user = User::find($rawUser->first()->id);

            if($post->user_id != $user->id) {
                return response()->json([
                    "response" => "Não autorizado",
                    "status" => 403
                ], 403);
            }


            $post->delete();

            return response()->json([
                "response" => true,
                "status" => 200
            ], 200);
        }

        return response()->json([
            "response" => "Não autorizado",
            "status" => 403
        ], 403);
    }




    /**
     * Marca se os posts informados sao do usuario
     */
    public static function markMinePosts(User $targetUser, Collection $posts) {
        $verifiedPosts = collect();

        //$values = [];

        foreach($posts as $post) {
            if($post->user_id == $targetUser->id) {
                $post["is_mine"] = true;
                //$values[] = [ "result" => true, "userId" => $post->user->name ];
            } else {
                $post["is_mine"] = false;
                //$values[] = [ "result" => false, "userId" => $post->user->name ];
            }

            $verifiedPosts->add($post);
        }

        //dd($values);
        return $verifiedPosts;
    }

    public static function getAuthorAvatar(Collection $targetPosts) {
        $userFiles = "users/";
        $posts = collect();

        foreach($targetPosts as $post) {
            if($post->user->avatar != null) {
                $post["avatar_url"] = Storage::url("public/" . $userFiles . $post->user_id . "/" . $post->user->avatar);
            } else {
                $post["avatar_url"] = null;
            }

            $posts->push($post);
        }

        return $posts;
    }
}
