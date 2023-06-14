<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private function getPostsOfUsers($usersIds, $loggedUser) {
        $posts = collect();

        foreach($usersIds as $userId) {
            $usr = User::find($userId);

            $userPosts = $usr->posts;
            
            foreach($userPosts as $post) {
                $postLike = DB::table("posts_likes")->select()->where("post_id", "=", $post->id)->where("user_id", "=", $loggedUser->id)->get()->count();
                $post["liked"] = ($postLike > 0) ? true : false;
                $posts->push($post);
            }
        }

        return $posts;
    }

    public function index(Request $request) {
        $loggedUser = AuthController::checkLogin();
        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $friends = FriendRelationsController::getFollowing($loggedUser);



        $feedPosts = $this->getPostsOfUsers($friends, $loggedUser);

        $userPosts = $loggedUser->posts;

        foreach($userPosts as $userPost) {
            $postLike = DB::table("posts_likes")->select()->where("post_id", "=", $userPost->id)->where("user_id", "=", $loggedUser->id)->get()->count();
            $userPost["liked"] = ($postLike > 0) ? true : false;
            $feedPosts->add($userPost);
        }

        $feedPosts = $feedPosts->sortBy(function($post, int $key) {
            
            return -$post->updated_at->toArray()["timestamp"];
        });

        //dd($feedPosts);

        return view("home", [
            "loggedUser" => $loggedUser,
            "feedPosts" => $feedPosts
        ]);


        


    }
}
