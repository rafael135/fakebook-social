<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class PageController extends Controller
{
    public $pageFiles = "pages/";


    public function create(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }



        return view("pages.createNewPage", [
            "pageName" => "newPage",
            "loggedUser" => $loggedUser
        ]);
    }

    public function createAction (Request $request) {
        $loggedUser = AuthController::checkLogin();
        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $errors = collect();

        $data["name"] = $request->input("name", null);
        $data["description"] = $request->input("description", null);
        $data["private"] = ($request->input("private", null) == "on") ? true : false;

        $image = $request->file("image", false);
        //$ddd = new ImageManager();
        //$intImage = $ddd->make($image->getPathname());

        $acceptedFormats = [
            "image/jpg",
            "image/jpeg",
            "image/png"
        ];

        if($image != false) {
            if(array_search($image->getMimeType(), $acceptedFormats) == false) {
                $errors->put("image", "O arquivo não é válido!");
            }
        }
        
        if($data["name"] == null) {
            $errors->put("name", "Nome da página não preenchido!");
        }

        if($data["description"] == null) {
            $data["description"] = "";
        }


        if($errors->count() == 0) {
            $data["creator_id"] = $loggedUser->id;

            $nameEncoded = str_replace(" ", "-", $data["name"]);
            $data["uniqueUrl"] = $nameEncoded . "-" . random_int(9999, 9999999);

            $newPage = Page::create($data);

            if($newPage == null) {
                $errors->put("createPageError", "Houve um erro ao tentar criar a página");
                return redirect()->route("page.create")->with("errors", $errors);
            }

            $folderPath = $this->pageFiles . $newPage->id;
            Storage::disk("public")->makeDirectory($folderPath);

            if($image != false) {
                $imageFileName = "image." . $image->clientExtension();


                $storeFileRes = Storage::putFileAs("public/" . $folderPath, $image, $imageFileName);

                if($storeFileRes != false) {
                    $newPage->image_url = $imageFileName;
                    $newPage->save();
                }
            }
            return redirect()->route("page.show", ["uniqueUrl" => $newPage->uniqueUrl]);
        }

        return redirect()
            ->route("page.create")
        ->with("errors", $errors);
        
    }

    public static function getPageImagePath($id) {
        $pageFiles = "pages/";
        $folder = $pageFiles . $id."/";

        $imageUrl = DB::table("pages")->select(["image_url"])->where("id", "=", $id)->get()->first()->image_url;
        $imagePath = Storage::url($folder . $imageUrl);

        return $imagePath;
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
            return redirect()->route("home");
        }

        $page = Page::find($sqlRes->first()->id);

        // Obtem a url da imagem da pagina
        $page->image_url = $this->getPageImagePath($page->id);

        return view("pages.showPage", [
            "pageName" => "page",
            "loggedUser" => $loggedUser,
            "page" => $page
        ]);
    }


    public function changeImage(Request $request) {

    }

    public function changeCover(Request $request) {
        
    }
}
