<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public static function getCommentsAuthor(Collection $comments) : Collection {
        $verifiedComments = collect();

        foreach($comments as $comment) {
            $user = $comment->user;
            $user = UserController::checkUser($user);

            $comment["author"] = $user;

            $verifiedComments->add($comment);
        }

        return $verifiedComments;
    }

    public function newComment(Request $request) : JsonResponse {
        $postId = $request->route()->parameter("id", null);
        $userToken = $request->input("userToken", null);
        $commentBody = $request->input("body", null);

        if($postId == null || $userToken == null || $commentBody == null) {
            return response()->json([
                "response" => "Parameter or body argument not present",
                "status" => 400
            ], 400);
        }

        $rawUser = DB::table("users")->select(["id"])->where("remember_token", "=", $userToken)->get();

        if($rawUser->count() == 0) {
            return response()->json([
                "response" => "",
                "status" => 401
            ], 401);
        }

        $user = User::find($rawUser->first()->id);
        $user = UserController::checkUser($user);

        $postId = intval($postId);

        $commentBody = filter_var($commentBody, FILTER_SANITIZE_SPECIAL_CHARS);

        if($commentBody == false) {
            return response()->json([
                "response" => "comment not valid",
                "status" => 401
            ], 400);
        }

        $newComment = Comment::create([
            "post_id" => $postId,
            "user_id" => $user->id,
            "body" => $commentBody,
            "like_count" => 0
        ]);

        $newComment["author"] = $user;



        return response()->json([
            "response" => $newComment,
            "status" => 201
        ], 201);
    }
}
