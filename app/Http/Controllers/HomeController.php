<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private function getPostsOfUsers($usersIds) {
        $posts = collect();

        foreach($usersIds as $userId) {
            $usr = User::find($userId);

            $userPosts = $usr->posts;
            
            foreach($userPosts as $post) {
                $posts->push($post);
            }
        }

        return $posts;
    }

    public function index(Request $request) {
        $loggedUser = AuthController::checkLogin();
        //if($loggedUser == false) {
        //    return redirect()->route("auth.login");
        //}
        
        $loggedUser = User::find(1);

        $friends = FriendRelationsController::getRelationsOf($loggedUser);

        $feedPosts = $this->getPostsOfUsers($friends);

        $feedPosts = $feedPosts->sortBy(function($product, int $key) {
            
            return $product->updated_at->toArray()["timestamp"];
        });

        //dd($feedPosts);

        return view("home", [
            "loggedUser" => $loggedUser,
            "feedPosts" => $feedPosts
        ]);


        


    }
}
