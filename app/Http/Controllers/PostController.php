<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getPostById(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);

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

        return response()->json([
            "response" => $post,
            "status" => 200
        ], 200);
    }

    public function getComments(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);

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

        $comments = $post->comments;

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
                "response" => "Parameter or body field not found",
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
}
