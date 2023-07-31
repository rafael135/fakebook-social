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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function loginPage(Request $request) : View | RedirectResponse {
        $errors = false;
        if($request->session()->has("errors")) {
            $errors = $request->session()->get("errors");
            $request->session()->forget("errors");
        }

        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }
        

        return view("auth.loginPreview", [
            "loggedUser" => false,
            "errors" => $errors
        ]);
    }

    public function registerPage(Request $request) : View | RedirectResponse {
        $errors = false;
        if($request->session()->has("errors")) {
            $errors = $request->session()->get("errors");
            $request->session()->forget("errors");
        }

        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }


        return view("auth.registerPreview", [
            "loggedUser" => false,
            "errors" => $errors
        ]);
    }

    public static function checkLogin() : User | false {
        if(Auth::check() == true) {
            $userFiles = "users/";
            $user = User::find(Auth::getUser()->getAuthIdentifier());

            if($user->avatar != null) {
                $user["avatar_url"] = Storage::url("public/" . $userFiles . $user->id . "/" . $user->avatar);
            }

            if($user->cover != null) {
                $user["cover_url"] = Storage::url("public/".  $userFiles. $user->id. "/". $user->cover);
            }

            return $user;
        }

        return false;
    }

# Action Functions:
    public function registerAction(Request $request) : RedirectResponse {
        

        $errors = collect();
        
        $data["name"] = $request->input("name", false);
        $data["email"] = $request->input("email", false);
        $data["password"] = $request->input("password", false);
        $passwordConfirm = $request->input("passwordConfirm", false);
        $remember = ($request->input("remember", false) == "on") ? true : false;


        if($data["password"] == null) {
            $errors->put("name", "Nome não informado!");
        }

        if($data["password"] == false) {
            $errors->put("password", "Senha não informada");
        } else if(strlen($data["password"]) < 8) {
            $errors->put("password", "Mínimo de 8 caracteres!");
        } else if ($data["password"] != $passwordConfirm) {
            $errors->put("password", "As senhas são diferentes!");
            //return redirect()->route("auth.register");
        }

        if($data["email"] == false) {
            $errors->put("email", "E-mail não informado");
        } else if(filter_var($data["email"], FILTER_VALIDATE_EMAIL) == false) {
            $errors->put("email", "O E-mail não é válido");
        }
        
        if($errors->count() == 0) {
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

        return redirect()->route("auth.register")->with("errors", $errors);
    }

    public function loginAction(Request $request) : RedirectResponse {
        if(self::checkLogin() != false) {
            return redirect()->route("home");
        }

        $errors = collect();

        $data["email"] = $request->input("email", false);
        $data["password"] = $request->input("password", false);
        $remember = ($request->input("remember", false) == "on") ? true : false;

        if($data["email"] == false) {
            $errors->put("email", "E-mail não informado");
        } 
        else if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) == false) {
            $errors->put("email", "O E-mail não é válido");
        }

        if(strlen($data["password"]) < 8) {
            $errors->put("password", "Mínimo de 8 caracteres!");
        }
        
        if(count($errors) == 0) {
            $res = Auth::attempt($data, $remember);
            if($res == true) {
                $rawUser = DB::table("users")->select(["id"])->where("email", "=", $data["email"])->get();
                $user = User::find($rawUser[0]->id);

                Auth::login($user, $remember);
                return redirect()->route("home");
            } else {
                $errors->put("all", "E-mail e/ou Senha incorreta");
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