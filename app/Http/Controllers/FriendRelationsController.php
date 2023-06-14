<?php

namespace App\Http\Controllers;

use App\Models\FriendRelation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendRelationsController extends Controller
{
    public static function getFollowing(User $user) {
        $friendRelations = $user->following()->get();

        $friendRelations = $friendRelations->toArray();

        $following = []; 

        foreach($friendRelations as $friend) {
            $following[] = $friend["user_to"];
        }

        return $following;
    }

    public static function getFollowers(User $user) {
        $friendRelations = $user->followers()->get();

        $friendRelations = $friendRelations->toArray();

        $followers = [];

        foreach($friendRelations as $friend) {
            $followers[] = $friend["user_from"];
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
}
