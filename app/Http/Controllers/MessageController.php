<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function showMessages(Request $request) {
        $loggedUser = AuthController::checkLogin();

        if($loggedUser == false) {
            return redirect()->route("auth.login");
        }

        $following = FriendRelationsController::getFollowing($loggedUser);

        //dd($following);

        $friends = collect();

        foreach($following as $friend) {
            $user = User::find($friend);

            $friends->add(UserController::checkUser($user));
        }

        
        //dd($friends);

        return view("messages", [
            "loggedUser" => $loggedUser,
            "friends" => $friends
        ]);
    }

    private function getAndSetAuthorMessages(Collection $rawMessages, User $loggedUser) : Collection {

        $verifiedMessages = collect();

        foreach($rawMessages as $rawMessage) {
            $msg = Message::find($rawMessage->id);

            $msgAuthor = $msg->author()->sole();
            if($msgAuthor->id == $loggedUser->id) {
                $msg["author"] = $msgAuthor->name;
                $msg["is_mine"] = true;
            } else {
                $msg["author"] = $msgAuthor->name;
                $msg["is_mine"] = false;
            }

            $verifiedMessages->add($msg);
        }

        return $verifiedMessages;
    }

    public function getChatMessages(Request $request) : JsonResponse {
        $userToken = $request->input("userToken", false);
        $targetId = $request->input("targetId", false);

        if($userToken == false || $targetId == false) {
            return response()->json([
                "status" => 401
            ], 401);
        }

        $loggedUser = DB::table("users")->select(["id"])->where("remember_token", "=", $userToken)->get();

        if($loggedUser->count() > 0) {
            $loggedUser = User::find($loggedUser->first()->id);
        } else {
            return response()->json([
                "status" => 401,
            ], 401);
        }

        $activeChat = null;

        $existentChat = DB::table("chats")->select()->where("user_from", "=", $targetId)->where("user_to", "=", $loggedUser->id)->get();

        if($existentChat->count() == 0) {
            $existentChat = DB::table("chats")->select()->where("user_from", "=", $loggedUser->id)->where("user_to", "=", $targetId)->get();
            if($existentChat->count() == 0) {
                return response()->json([
                    "messages" => false,
                    "status" => 204
                ], 204);
            }
        }
        $activeChat = Chat::find($existentChat->first()->id);


        $rawMessages = DB::table("messages")->where("chat_id", "=", $activeChat->id)->get();

        $messages = $this->getAndSetAuthorMessages($rawMessages, $loggedUser);



        return response()->json([
            "response" => $messages,
            "status" => 200
        ], 200);
    }


    public function newMessageTo(Request $request) : JsonResponse {
        $userToken = $request->input("userToken", false);
        $targetId = $request->input("targetId", false);
        $msgBody = $request->input("body", false);

        if($userToken == false || $targetId == false || $msgBody == false) {
            return response()->json([
                "status" => 401
            ], 401);
        }

        $rawUsr = DB::table("users")->select(["id"])->where("remember_token", "=", $userToken)->get();

        if($rawUsr->count() == 0) {
            return response()->json([
                "status" => 401
            ], 401);
        }

        $loggedUser = User::find($rawUsr->first()->id);

        if($loggedUser->id == $targetId) {
            return response()->json([
                "status" => 400
            ], 400);
        }

        $activeChat = null;
        $existentChat = DB::table("chats")->select(["id"])->where("user_from", "=", $loggedUser->id)->where("user_to", "=", $targetId)->get();

        if($existentChat->count() == 0) {
            $existentChat = DB::table("chats")->select(["id"])->where("user_from", "=", $targetId)->where("user_to", "=", $loggedUser->id)->get();
            if($existentChat->count() == 0) {
                $activeChat = Chat::create([
                    "user_from" => $loggedUser->id,
                    "user_to"   => $targetId,
                ]);
            } else {
                $activeChat = Chat::find($existentChat->first()->id);
            }
        } else {
            $activeChat = Chat::find($existentChat->first()->id);
        }


        $newMessage = Message::create([
            "chat_id" => $activeChat->id,
            "user_from" => $loggedUser->id,
            "user_to" => $targetId,
            "body" => $msgBody
        ]);

        $newMessage["is_mine"] = true;
        $newMessage["author"] = $loggedUser->name;

        return response()->json([
            "response" => $newMessage,
            "status" => 201
        ], 201);
    }
}
