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

                break;
            case "page":

                break;
            case "profile":
                $rawSearch = DB::table("users")->select(["id"])->where("name", "like", "%$searchTerm%")->orWhere("email", "like", "$searchTerm%")->get();

                dd($rawSearch);
                break;
            case "post":

                break;

            default:
                
        }

        dd($type, $searchTerm);




    }
}
