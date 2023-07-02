<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function showMessages(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        
    }
}
