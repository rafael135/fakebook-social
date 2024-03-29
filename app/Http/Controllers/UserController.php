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
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $usersFiles = "users/";

    public function __construct()
    {
        
    }

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
     * Função para checar se um usuário segue o outro, caso sim, exclui o Model da relação, caso não, cria o Model da relação e segue
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

        if($userFrom->id == $userTo->id) {
            return response()->json([
                "response" => false,
                "status" => 400
            ], 400);
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

        if($profileUser->avatar != null) {
            $profileUser["avatar_url"] = Storage::url($this->usersFiles . $profileUser->id . "/" . $profileUser->avatar);
        }

        if($profileUser->cover != null) {
            $profileUser["cover_url"] = Storage::url($this->usersFiles . $profileUser->id . "/" . $profileUser->cover);
        }

        $friendRelations = FriendRelationsController::getFollowing($loggedUser);

        $isFriend = false;

        foreach($friendRelations as $friend) {
            if($friend == $profileUser->id) {
                $isFriend = true;
                break;
            }
        }

        $isMine = ($profileUser->id == $loggedUser->id) ? true : false;

        

        $verifiedPosts = PostLikesController::markPostsLikedBy($loggedUser, $profileUser);

        if($isMine == true) {
            $verifiedPosts = PostController::markMinePosts($loggedUser, $verifiedPosts);
        }

        $verifiedPosts = PostController::getAuthorAvatar($verifiedPosts);

        $profileUser["is_friend"] = $isFriend;
        $profileUser["is_mine"] = $isMine;

        $profileUser["verified_posts"] = $verifiedPosts;

        return view("User.profile", [
            "pageName" => "profile",
            "loggedUser" => $loggedUser,
            "profileUser" => $profileUser
        ]);
    }

    public function showUserFollowers(Request $request) {
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



        $friendRelations = FriendRelationsController::getFollowing($loggedUser);

        $verifiedFollowers = collect();

        foreach($followers as $follower) {
            $follower["is_friend"] = false;
            $follower["is_mine"] = false;
            foreach($friendRelations as $LoggedUsrfriendId) {
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

        return view("User.userFollowers", [
            "pageName" => "profile",
            "loggedUser" => $loggedUser,
            "profile" => $profile,
            "followers" => $verifiedFollowers
        ]);
    }

    public function showUserFollowing(Request $request) {
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

        $following = $profile->following()->get();
        $following = User::getUsersModelsFromFriendRelations($following);


        $friendRelations = FriendRelationsController::getFollowing($loggedUser);

        $verifiedFollowing = collect();
        foreach($friendRelations as $friendId) {
            $friendProfile = User::find($friendId);
            foreach($following as $follow) {
                if($follow->id == $friendProfile->id) {
                    $follow["is_friend"] = true;
                } else {
                    $follow["is_friend"] = false;
                }

                $verifiedFollowing->add($follow);
            }
        }

        return view("User.userFollowers", [
            "pageName" => "profile",
            "loggedUser" => $loggedUser,
            "profile" => $profile,
            "following" => $verifiedFollowing
        ]);
    }

    public function userConfig(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $opt = $request->input("option", false);

        return view("User.Config.profile", [
            "pageName" => "config",
            "loggedUser" => $loggedUser,
            "optionPage" => $opt
        ]);
    }

    

    public function changeAvatar(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }
        $loggedUser = User::find($loggedUser->id);

        $avatarFile = $request->file("avatar", false);

        if($avatarFile == false) {
            return redirect()->route("user.profile", ["uniqueUrl" => $loggedUser->uniqueUrl])->with("error", "Erro ao tentar fazer upload da imagem");
        }

        $acceptedTypes = ["image/jpg", "image/jpeg", "image/png"];

        if(array_search($avatarFile->getMimeType(), $acceptedTypes) == false) {
            return redirect()->route("user.profile", ["uniqueUrl" => $loggedUser->uniqueUrl])->with("error", "Tipo de arquivo não permitido.");
        }

        $defaultPath = $this->usersFiles . $loggedUser->id;
        $avatarFileName = "avatar." . $avatarFile->clientExtension();

        if($loggedUser->avatar != null) {
            Storage::disk("public")->delete($defaultPath . "/" . $loggedUser->avatar);
        }

        Storage::disk("public")->makeDirectory($defaultPath);

        $path = Storage::putFileAs(
            "public/" . $defaultPath, $avatarFile, $avatarFileName
        );

        $loggedUser->avatar = $avatarFileName;
        $loggedUser->save();

        // Excluo o arquivo temporario
        unlink($avatarFile->getRealPath());
        
        //dd($avatarFile->store($this->usersFiles . $loggedUser->id . "/avatar."));

        //dd($avatarFile);

        return redirect()->route("user.profile", ["uniqueUrl" => $loggedUser->uniqueUrl])->with("success", "Imagem de perfil alterado com sucesso!");
    }

    public function changeCover(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }
        $loggedUser = User::find($loggedUser->id);

        $coverFile = $request->file("cover", false);

        if($coverFile == false) {
            return redirect()->route("user.profile", ["uniqueUrl" => $loggedUser->uniqueUrl])->with("error", "Erro ao tentar fazer upload da imagem");
        }

        $acceptedTypes = ["image/jpg", "image/jpeg", "image/png"];

        if(array_search($coverFile->getMimeType(), $acceptedTypes) == false) {
            return redirect()->route("user.profile", ["uniqueUrl" => $loggedUser->uniqueUrl])->with("error", "Tipo de arquivo não permitido.");
        }

        $defaultPath = $this->usersFiles . $loggedUser->id;
        $coverFileName = "cover." . $coverFile->clientExtension();


        if($loggedUser->cover != null) {
            Storage::disk("public")->delete($defaultPath . "/" . $loggedUser->cover);
        }

        Storage::disk("public")->makeDirectory($defaultPath);

        $path = Storage::putFileAs(
            "public/" . $defaultPath, $coverFile, $coverFileName
        );

        $loggedUser->cover = $coverFileName;
        $loggedUser->save();

        // Excluo o arquivo temporario
        unlink($coverFile->getRealPath());

        return redirect()->route("user.profile", ["uniqueUrl" => $loggedUser->uniqueUrl])->with("success", "Capa alterada com sucesso!");
    }


    /**
     * Obtem o avatar e capa do usuario, se houver
     */
    public static function checkUser(User $targetUser) : User {
        $userFiles = "users/";

        if($targetUser->avatar != null) {
            $targetUser["avatar_url"] = Storage::url($userFiles . $targetUser->id . "/" . $targetUser->avatar);
        }

        if($targetUser->cover != null) {
            $targetUser["cover_url"] = Storage::url($userFiles . $targetUser->id . "/" . $targetUser->cover);
        }

        return $targetUser;
    }
}
