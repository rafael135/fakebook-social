<?php

namespace App\Http\Controllers;

use App\Models\FriendRelation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class FriendRelationsController extends Controller
{
    public static function getRelationsOf(User $user) {
        $friendRelations = $user->friends()->get();

        $friendRelations = $friendRelations->toArray();

        $friends = []; 

        foreach($friendRelations as $friend) {
            $friends[] = $friend["user_to"];
        }

        return $friends;
    }
}
