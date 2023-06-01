<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function registerAction(CreateUserRequest $request) {
        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }

        $data = $request->only([
            "name",
            "email",
            "password"
        ]);

        $passwordConfirm = $request->input("passwordConfirm", false);

        if ($data["password"] != $passwordConfirm) {
            return redirect()->route("auth.register");
        }

        $remember = $request->input("remember", false);

        if($remember == "true") {
            $remember = true;
        } else {
            $remember = false;
        }

        if($data != null) {
            $hash = Hash::make($data["password"]);
            $data["password"] = $hash;

            // Caso for cadastrado com sucesso, retorna o Model User
            $result = User::create($data);

            if($result != null) {
                // Uso o Model do usuario retornado em "$result" para efetuar o Login
                Auth::login($result, $remember);
                $request->session()->regenerate();

                return redirect()->route("home");
            }
        }

        return redirect()->route("auth.register");
    }

    public function loginAction(LoginRequest $request) {
        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }

        $data = $request->only([
            "email",
            "password"
        ]);

        $remember = $request->input("remember", false);

        if($remember == "true") {
            $remember = true;
        } else {
            $remember = false;
        }

        if($data != null) {
            if(Auth::attempt($data, $remember)) {
                $request->session()->regenerate();
    
                return redirect()->route("home");
            }
        }

        return redirect()->route("auth.login");
    }

    public function logout(Request $request) {
        if(self::checkLogin() == false) {
            return redirect()->route("auth.login");
        }

        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate(true);

        return redirect()->route("auth.login");
    }
}