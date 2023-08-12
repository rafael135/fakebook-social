<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $page = $request->input("page", false);

        $type = $request->input("type", false);
        $searchTerm = $request->input("searchTerm", false);



        switch($type) {
            case "group":
                $groupSearch = DB::table("groups")->select(["id"])->where("name", "like", "%$searchTerm%")->get();
                break;
            case "page":
                $pageSearch = DB::table("pages")->select(["id"])->where("name", "like", "%$searchTerm%")->get();

                break;
            case "profile":
                $profileSearch = DB::table("users")->select(["id"])->where("name", "like", "%$searchTerm%")->orWhere("email", "like", "$searchTerm%")->get();

                $profileIds = $profileSearch->collect();

                dd($profileIds);

                
                break;
            case "post":
                $userPostSearch = DB::table("posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                $pagePostSearch = DB::table("pages_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                $groupPostSearch = DB::table("groups_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                break;

            default:
                $profileSearch = DB::table("users")->select(["id"])->where("name", "like", "%$searchTerm%")->orWhere("email", "like", "$searchTerm%")->get();
                $pageSearch = DB::table("pages")->select(["id"])->where("name", "like", "%$searchTerm%")->get();
                $groupSearch = DB::table("groups")->select(["id"])->where("name", "like", "%$searchTerm%")->get();
                $pagePostSearch = DB::table("pages_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                $groupPostSearch = DB::table("groups_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                
        }

        dd($type, $searchTerm);




    }
}
