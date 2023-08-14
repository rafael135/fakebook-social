<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\Page;
use App\Models\PagePost;
use App\Models\Post;
use App\Models\User;
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

        $itemsSearched = collect();

        switch($type) {
            case "group":
                $groupSearch = DB::table("groups")->select(["id"])->where("name", "like", "%$searchTerm%")->get();
                $groupSearch = Group::convertIdsToModels($groupSearch);

                $itemsSearched["groups"] = $groupSearch;
                break;
            case "page":
                $pageSearch = DB::table("pages")->select(["id"])->where("name", "like", "%$searchTerm%")->get();
                $pageSearch = Page::convertIdsToModels($pageSearch);

                $itemsSearched["pages"] = $pageSearch;

                break;
            case "profile":
                $profileSearch = DB::table("users")->select(["id"])->where("name", "like", "%$searchTerm%")->orWhere("email", "like", "$searchTerm%")->get();
                $profileSearch = User::convertIdsToModels($profileSearch);

                $itemsSearched["users"] = $profileSearch;
                
                break;
            case "post":
                $userPostSearch = DB::table("posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                $pagePostSearch = DB::table("pages_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                $groupPostSearch = DB::table("groups_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();

                $userPostSearch = Post::convertIdsToModels($userPostSearch);
                $pagePostSearch = PagePost::convertIdsToModels($pagePostSearch);
                $groupPostSearch = GroupPost::convertIdsToModels($groupPostSearch);

                $itemsSearched["posts"] = $userPostSearch;
                $itemsSearched["pagePosts"] = $pagePostSearch;
                $itemsSearched["groupPosts"] = $groupPostSearch;
                break;

            default:
                $profileSearch = DB::table("users")->select(["id"])->where("name", "like", "%$searchTerm%")->orWhere("email", "like", "$searchTerm%")->get();
                $pageSearch = DB::table("pages")->select(["id"])->where("name", "like", "%$searchTerm%")->get();
                $groupSearch = DB::table("groups")->select(["id"])->where("name", "like", "%$searchTerm%")->get();
                $profilesPostsSearch = DB::table("posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                $pagePostSearch = DB::table("pages_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();
                $groupPostSearch = DB::table("groups_posts")->select(["id"])->where("body", "like", "%$searchTerm%")->get();

                $profileSearch = User::convertIdsToModels($profileSearch);
                $pageSearch = Page::convertIdsToModels($pageSearch);
                $groupSearch = Group::convertIdsToModels($groupSearch);
                $profilesPostsSearch = Post::convertIdsToModels($profilesPostsSearch);
                $pagePostSearch = PagePost::convertIdsToModels($pagePostSearch);
                $groupPostSearch = GroupPost::convertIdsToModels($groupPostSearch);

                $itemsSearched["users"] = $profileSearch;
                $itemsSearched["pages"] = $pageSearch;
                $itemsSearched["groups"] = $groupSearch;
                $itemsSearched["posts"] = $profilesPostsSearch;
                $itemsSearched["pagePosts"] = $pagePostSearch;
                $itemsSearched["groupPosts"] = $groupPostSearch;
        }

        //dd($type, $searchTerm);


        // TODO
        dd($itemsSearched);
        
        $itemsSearched = $itemsSearched->sortBy(function ($item, int $key) {
            return -$item->updated_at;
        });

        return view("search", [
            "pageName" => "search",
            "loggedUser" => $loggedUser,
            "items" => $itemsSearched
        ]);
    }
}
