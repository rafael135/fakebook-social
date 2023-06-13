<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function loginPage(Request $request) : View | RedirectResponse {
        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }
        return view("auth.login", ["loggedUser" => false]);
    }

    public function registerPage(Request $request) : View | RedirectResponse {
        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }
        return view("auth.register", ["loggedUser" => false]);
    }

    public static function checkLogin() : User | false {
        if(Auth::check() == true) {
            $user = User::find(Auth::getUser()->getAuthIdentifier());
            return $user;
        }

        return false;
    }

# Action Functions:
    public function registerAction(CreateUserRequest $request) : RedirectResponse {
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

            $completeName = $data["name"];

            $uniqueUrl = str_replace(' ', '-', $completeName) . "-" . random_int(9999, 99999999);

            while( DB::table("users")->select()->where("uniqueUrl", "=", $uniqueUrl)->count() > 0 ) {
                $uniqueUrl = str_replace(' ', '-', $completeName) . "-" . random_int(9999, 99999999);
            }

            $data["uniqueUrl"] = $uniqueUrl;

            // Caso for cadastrado com sucesso, retorna o Model User
            $result = User::create($data);

            if($result != null) {
                // Uso o Model do usuario retornado em "$result" para efetuar o Login
                $request->session()->regenerate();
                Auth::login($result, $remember);
                return redirect()->route("home");
            }
        }

        return redirect()->route("auth.register");
    }

    public function loginAction(Request $request) : RedirectResponse {
        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }

        $errors = [];

        $data["email"] = $request->input("email", false);
        $data["password"] = $request->input("password", false);

        if($data["email"] == "") {
            $errors[] = [
                "field" => "email",
                "msg" => "E-mail não informado"
            ];
        } 
        else if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) == false) {
            $errors[] = [
                "field" => "email",
                "msg" => "O e-mail não é válido"
            ];
        }

        $remember = $request->input("remember", false);

        if($remember == "on") {
            $remember = true;
        } else {
            $remember = false;
        }
        
        if($data != null && count($errors) == 0) {
            $res = Auth::attempt($data, $remember);
            if($res == true) {
                $rawUser = DB::table("users")->select(["id"])->where("email", "=", $data["email"])->get();
                $user = User::find($rawUser[0]->id);

                Auth::login($user, $remember);
                return redirect()->route("home");
            }
        }

        return redirect()->route("auth.login")->with("errors", $errors);
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
#




# API Functions:

    public function createUser(CreateUserRequest $request) {
        $data = $request->only([
            "name",
            "email",
            "password"
        ]);

        $passwordConfirm = $request->input("passwordConfirm", false);

        if ($data["password"] != $passwordConfirm) {
            return response()->json([
                "response" => "As senhas sao diferentes!",
                "status" => 400
            ], 400);
        }

        if($data != null) {
            $hash = Hash::make($data["password"]);
            $data["password"] = $hash;

            $completeName = $data["name"];

            $uniqueUrl = str_replace(' ', '-', $completeName) . "-" . random_int(9999, 99999999);

            while( DB::table("users")->select()->where("uniqueUrl", "=", $uniqueUrl)->count() > 0 ) {
                $uniqueUrl = str_replace(' ', '-', $completeName) . "-" . random_int(9999, 99999999);
            }

            $data["uniqueUrl"] = $uniqueUrl;

            $email = $data["email"];
            if(DB::table("users")->select()->where("email", "=", $email)->count() > 0) {
                return response()->json([
                    "response" => "Email em uso!",
                    "status" => 400
                ], 400);
            }

            // Caso for cadastrado com sucesso, retorna o Model User
            $result = User::create($data);

            if($result != null) {
                return response()->json([
                    "response" => $result,
                    "status" => 201
                ], 201);
            }
        }
    }



#
}