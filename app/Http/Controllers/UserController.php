<?php

namespace App\Http\Controllers;

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

    // Métodos para rotas de API:
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

        $user = User::find($userId);

        return view("User.profile", [
            "loggedUser" => $loggedUser,
            "profileInfo" => $user
        ]);
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
