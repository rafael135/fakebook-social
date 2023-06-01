<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserById(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);

        if($id == null) {
            return response()->json([
                "response" => "Parameter 'id' not found",
                "status" => 400
            ], 400);
        }

        $user = User::find($id);

        if($user == null) {
            return response()->json([
                "response" => "User with id: $id not found",
                "status" => 404
            ], 404);
        }

        return response()->json([
            "response" => $user,
            "status" => 200
        ], 200);
    }



    public function getUserPosts(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);

        if($id == null) {
            return response()->json([
                "response" => "Parameter 'id' not found",
                "status" => 400
            ], 400);
        }

        $user = User::find($id);

        if($user == null) {
            return response()->json([
                "response" => "User with id: $id not found",
                "status" => 404
            ], 404);
        }

        $posts = $user->posts;

        return response()->json([
            "response" => $posts,
            "status" => 200
        ], 200);
    }
}
