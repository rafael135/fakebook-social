<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getPostById(Request $request) :JsonResponse {
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
        $userToken = $request->input("user_token", false);

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
                "status" => 404
            ], 404);
        }

        $userId = $userId[0]->id;

        $loggedUser = User::find($userId);

        

    }
}
