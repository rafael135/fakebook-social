<?php

namespace App\Http\Controllers;

use App\Models\FriendRelation;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FriendRelationsController extends Controller
{   
    /**
     * Pega todos os id's dos usuários que são seguidos pelo usuário específicado
     */
    public static function getFollowing(User $targetUser) : Collection {
        $friendRelations = $targetUser->following()->get();

        $friendRelations = $friendRelations;

        $following = collect(); 

        foreach($friendRelations as $friend) {
            $following->push($friend->user_to);
        }

        return $following;
    }

    /**
     * Pega todos os id's dos usuários que seguem o usuário específicado
     */
    public static function getFollowers(User $targetUser) : Collection {
        $friendRelations = $targetUser->followers()->get();

        $friendRelations = $friendRelations;

        $followers = collect();

        foreach($friendRelations as $friend) {
            $followers->push($friend->user_from);
        }

        return $followers;
    }

    /**
     * Função para checar se relação entre dois usuários especificos existe
     * 
     * Se existir, retorna o Model da relação, se não, retorna falso
     * 
     */
    public static function checkRelationByUserIds($userFrom, $userTo) : FriendRelation | bool {
        $rawRelation = DB::table("friends_relations")->select(["id"])->where("user_from", "=", $userFrom)->where("user_to", "=", $userTo)->get();

        if($rawRelation->count() > 0) {
            $id = $rawRelation[0]->id;

            $relation = FriendRelation::find($id);

            return $relation;
        }

        return false;
    }



    public function showFriends(Request $request) : View | RedirectResponse {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $followers = self::getFollowers($loggedUser);
        $following = self::getFollowing($loggedUser);
        //dd($followers, $following);

        $friends = collect();

        foreach($followers as $follower) {
            foreach($following as $follow) {
                if($follower == $follow) {
                    $validFriend = User::find($follower);

                    $friends->add($validFriend);
                }
            }
        }

        if($friends->count() > 0) {
            $friends = User::getUsersImgs($friends);
        }

        //dd($friends);

        


        return view("friends", ["loggedUser" => $loggedUser, "pageName" => "friends", "friends" => $friends]);
    }


    public function showUserListFriends(Request $request): View | RedirectResponse {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $uniqueUrl = $request->route()->parameter("uniqueUrl", false);

        $dbRes = DB::table("users")->select(["id"])->where("uniqueUrl", "=", $uniqueUrl)->get();

        if($dbRes->count() == 0) {
            return redirect()->route("home")->with("error", "Perfil de usuário não encontrado!");
        }

        $profile = User::find($dbRes->first()->id);

        $followers = $profile->followers()->get();
        $followers = User::getUsersModelsFromFriendRelations($followers);


        $loggedFriendRelations = FriendRelationsController::getFollowing($loggedUser);

        $verifiedFollowers = collect();

        foreach($followers as $follower) {
            $follower["is_friend"] = false;
            $follower["is_mine"] = false;
            foreach($loggedFriendRelations as $LoggedUsrfriendId) {
                $friendProfile = User::find($LoggedUsrfriendId);
                if($follower->id == $friendProfile->id) {
                    $follower["is_friend"] = true;
                }
            }

            if($follower->id == $loggedUser->id) {
                $follower["is_mine"] = true;
            }

            $verifiedFollowers->push($follower);
            
        }

        $following = $profile->following()->get();
        $following = User::getUsersModelsFromFriendRelations($following);

        $profileFriendsRelations = FriendRelationsController::getFollowing($profile);

        //dd($profileFriendsRelations);

        $verifiedFollowing = collect();

        foreach($profileFriendsRelations as $friendId) {
            $friendProfile = User::find($friendId);

            $friendProfile["is_friend"] = false;
            $friendProfile["is_mine"] = false;

            foreach($loggedFriendRelations as $loggedFriendId) {
                $loggedFriendProfile = User::find($loggedFriendId);

                if($friendProfile->id == $loggedFriendProfile->id) {
                    $friendProfile["is_friend"] = true;
                }
            }

            if($friendProfile->id == $loggedUser->id) {
                $friendProfile["is_mine"] = true;
            }


            $verifiedFollowing->push($friendProfile);
        }

        /*
        foreach($profileFriendsRelations as $friendId) {
            $friendProfile = User::find($friendId);
            foreach($following as $follow) {
                $follow["is_mine"] = false;
                if($follow->id == $friendProfile->id) {
                    $follow["is_friend"] = true;
                } else {
                    $follow["is_friend"] = false;
                }

                $verifiedFollowing->push($follow);
            }
        }
        */



        return view("User.userListFriends", [
            "pageName" => "profile",
            "loggedUser" => $loggedUser,
            "profile" => $profile,
            "followers" => $verifiedFollowers,
            "followings" => $verifiedFollowing
        ]);
    }
}
