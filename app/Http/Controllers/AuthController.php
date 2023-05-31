<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage(Request $request) : View {
        return view("auth.login");
    }

    public function registerPage(Request $request) : View {
        return view("auth.register");
    }

    public static function checkLogin() : User | false {
        if(Auth::check() == true) {
            $user = User::find(Auth::getUser()->getAuthIdentifier());
            return $user;
        }

        return false;
    }

    public function registerAction(Request $request) {

    }

    public function loginAction(Request $request) {

    }
}
