<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
}
