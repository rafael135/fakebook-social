<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function create(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }



        return view("Pages.createNewPage", [
            "pageName" => "newPage",
            "loggedUser" => $loggedUser
        ]);
    }

    public function createAction (Request $request) {
        
    }



    public function show(Request $request) {
        $loggedUser = AuthController::checkLogin();
        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $uniqueUrl = $request->route()->parameter("uniqueUrl", false);
        if($uniqueUrl == false) {

        }

        $sqlRes = DB::table("pages")->select(["id"])->where("uniqueUrl", "=", $uniqueUrl)->get();
        if($sqlRes->count() == 0) {

        }

        $page = Page::find($sqlRes->first()->id);


    }
}
