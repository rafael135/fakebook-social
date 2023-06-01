<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
