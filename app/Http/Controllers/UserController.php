<?php

namespace App\Http\Controllers;

use App\Models\FriendRelation;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Route;
use Hamcrest\Type\IsString;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

# Métodos para rotas de API:
    public function getUserById(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);

        if($id == null) {
            return response()->json([
                "response" => "Parameter 'id' not found",
                "status" => 400
            ], 400);
        }

        $user = User::find($id);

        if($user == null) {
            return response()->json([
                "response" => "User with id: $id not found",
                "status" => 404
            ], 404);
        }

        return response()->json([
            "response" => $user->toJson(),
            "status" => 200
        ], 200);
    }



    public function getUserPosts(Request $request) : JsonResponse {
        $id = $request->route()->parameter("id", null);

        if($id == null) {
            return response()->json([
                "response" => "Parameter 'id' not found",
                "status" => 400
            ], 400);
        }

        $user = User::find($id);

        if($user == null) {
            return response()->json([
                "response" => "User with id: $id not found",
                "status" => 404
            ], 404);
        }

        $posts = $user->posts;

        return response()->json([
            "response" => $posts->toJson(),
            "status" => 200
        ], 200);
    }

    /**
     * Função para checar se um usuário segue o outro, caso sim, exclui o Model da relação e para seguir, caso não, cria o Model da relação e segue
     * 
     * 
     */
    public function followUser(Request $request) : JsonResponse {
        $userToId = $request->route()->parameter("id", false);
        $userFromToken = $request->input("userToken", false);

        if($userToId == false) { // Checagem de segurança para caso o id não seja válido
            return response()->json([ "response" => false, "status" => 403 ], 403);
        }

        if($userFromToken == false) { // Checagem de segurança para caso o token do usuário não for informado
            return response()->json([ "response" => false, "status" => 403 ], 403);
        }

        

        $userFromRawId = DB::table("users")->select(["id"])->where("remember_token", "=", $userFromToken)->get(); // Tenta encontrar um usuário com o token informado
        $userFrom = false;

        if($userFromRawId[0] != null) { // Caso tenha encontrado um usuário com o token, pega seu id
            $userFrom = User::find($userFromRawId[0]->id);
        }

        if($userFrom == false) { // Checa se o usuário foi encontrado
            return response()->json([ "response" => false, "status" => 403 ], 403);
        }

        $userTo = User::find($userToId);

        if($userTo == null) { // Checa se o usuário para ser seguido é válido
            return response()->json([ "response" => false, "status" => 403 ], 403);
        }

        $relation = FriendRelationsController::checkRelationByUserIds($userFrom->id, $userTo->id);

        // Se a relação não existir, crio uma e a retorno
        if($relation == false) {
            $relation = FriendRelation::create([
                "user_from" => $userFrom->id,
                "user_to" => $userTo->id
            ]);

            $userFrom->following_count = $userFrom->following_count + 1;
            $userFrom->save();

            $userTo->followers_count = $userTo->followers_count + 1;
            $userTo->save();

            return response()->json(["response" => $relation, "status" => 201], 201);
        }

        // Se a relação existir, excluo ela e retorno falso
        $relation->delete();

        $userFrom->following_count = $userFrom->following_count - 1;
        $userFrom->save();

        $userTo->followers_count = $userTo->followers_count - 1;
        $userTo->save();

        return response()->json(["response" => false, "status" => 200], 200);
    }
#

    // ------------------------------------------------------------

    public function showUser(Request $request) {
        $loggedUser = AuthController::checkLogin();

        $uniqueUrl = $request->route()->parameter("uniqueUrl", null);

        if($uniqueUrl == null) {
            return redirect()->route("home");
        }

        $rawData = DB::table("users")->select(["id"])->where("uniqueUrl", "=", $uniqueUrl)->get();

        if($rawData->count() == 0) {
            return redirect()->route("home");
        }

        $userId = $rawData->first()->id;

        $profileUser = User::find($userId);

        $friendRelations = FriendRelationsController::getFollowing($loggedUser);

        $isFriend = false;

        foreach($friendRelations as $friend) {
            if($friend == $profileUser->id) {
                $isFriend = true;
                break;
            }
        }

        $isMine = ($profileUser->id == $loggedUser->id) ? true : false;

        $profileUser["is_friend"] = $isFriend;
        $profileUser["is_mine"] = $isMine;

        

        return view("User.profile", [
            "loggedUser" => $loggedUser,
            "profileUser" => $profileUser
        ]);
    }

    public function showUserFollowers(Request $request) {
        $loggedUser = AuthController::checkLogin();

        $uniqueUrl = $request->route()->parameter("uniqueUrl", false);

        dd($uniqueUrl);
    }

    public function showUserFollowing(Request $request) {
        $loggedUser = AuthController::checkLogin();

        $uniqueUrl = $request->route()->parameter("uniqueUrl", false);

        dd($uniqueUrl);
    }

    public function userConfig(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $opt = $request->input("option", false);

        return view("User.Config.profile", [
            "loggedUser" => $loggedUser,
            "optionPage" => $opt
        ]);
    }
}
