<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostLikesController extends Controller
{

    /**
     * Percorre todos os posts do usuario alvo e marca se eles foram curtidos pelo usuario logado ou nao
     */
    public static function markPostsLikedBy(User $user, User $targetUser) {
        $targetPosts = $targetUser->posts;

        $VerifiedPosts = collect();

        foreach($targetPosts as $post) {
            if(DB::table("posts_likes")->select(["id"])->where("post_id", "=", $post->id)->where("user_id", "=", $user->id)->count() > 0) {
                $post["liked"] = true;
            } else {
                $post["liked"] = false;
            }

            $VerifiedPosts->push($post);
        }

        return $VerifiedPosts;
    }
}
