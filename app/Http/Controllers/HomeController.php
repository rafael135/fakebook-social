<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        $loggedUser = AuthController::checkLogin();
        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        return view("home", [
            "loggedUser" => $loggedUser
        ]);
    }
}
