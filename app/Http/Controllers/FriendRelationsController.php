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
                    $friends->add($follower);
                }
            }
        }

        dd($friends);

        


        return view("friends", ["loggedUser" => $loggedUser, "friends" => $friends]);
    }
}
