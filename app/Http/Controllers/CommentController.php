<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
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

    public static function verifyUserLike($targetId, $commentId): bool {
        $raw = DB::table("comments_likes")->select(["id"])->where("comment_id", "=", $commentId)->where("user_id", "=", $targetId)->get();

        if($raw->count() == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function likeComment(Request $request) {
        $commentId = $request->route()->parameter("id", null);
        $usrToken = $request->input("token", null);

        if($commentId == null || $usrToken == null) {
            return response()->json([
                "response" => [

                ],
                "status" => 400
            ], 400);
        }



        $rawUsr = DB::table("users")->select(["id"])->where("remember_token", "=", $usrToken)->get();

        if($rawUsr->count() == 0) {
            return response()->json([
                "response" => [

                ],
                "status" => 401
            ], 401);
        }

        $user = User::find($rawUsr->first()->id);
        $commentToLike = Comment::find($commentId);

        $rawLike = DB::table("comments_likes")->select(["id"])->where("comment_id", "=", $commentToLike->id)->where("user_id", "=", $user->id)->get();

        if($commentToLike->like_count == 0 || $rawLike->count() == 0) {
            $commentLike = CommentLike::create([
                "comment_id" => $commentToLike->id,
                "user_id" => $user->id
            ]);
            
            if($commentLike != null) {
                return response()->json([
                    "response" => [
                        "liked" => true
                    ],
                    "status" => 201
                ], 201);
            } else {
                return response()->json([
                    "response" => [
                        "liked" => null
                    ],
                    "status" => 500
                ], 500);
            }
        }

        $commentLike = CommentLike::find($rawLike->first()->id);
        $res = $commentLike->delete();

        if($res == true) {
            return response()->json([
                "response" => [
                    "liked" => false
                ],
                "status" => 200
            ], 200);
        } else {
            return response()->json([
                "response" => [
                    "liked" => null
                ],
                "status" => 500
            ], 500);
        }
    }

    public function replyComment(Request $request) {

    }
}
