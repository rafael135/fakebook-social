"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
Object.defineProperty(exports, "__esModule", { value: true });
let userTokenInput = document.getElementById("userToken");
let templateMessage = document.getElementById("template-msg");
let messageInput = document.getElementById("messageInput");
let activeChat = document.getElementById("activeChat").querySelector(".chat");
let loadingSpin = document.getElementById("loading-spin");
let activeChatMessages = [];
let activeFriendId = 0;
messageInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        sendMessage(messageInput.value, userTokenInput.value, activeFriendId);
    }
});
function resetChat() {
    activeChat.innerHTML = "";
    activeChatMessages = [];
}
function setLoadingScreen() {
    let loadingSpinClone = loadingSpin.cloneNode(true);
    loadingSpinClone.classList.remove("hide");
    loadingSpinClone.classList.add("show");
    activeChat.appendChild(loadingSpinClone);
    activeChat.classList.add("loading-screen");
}
function loadingCompleted() {
    activeChat.classList.remove("loading-screen");
}
function checkMessages(target) {
    return __awaiter(this, void 0, void 0, function* () {
        let userToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        // @ts-expect-error
        let req = yield fetch(route("api.chat.getMessages"), {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                userToken: userToken,
                targetId: target
            })
        });
        let res = yield req.json();
        if (res.status == 204) {
            return;
        }
        loadingCompleted();
        resetChat();
        res.response.forEach((msg) => {
            addNewMessageToActiveChat(msg);
        });
    });
}
function addNewMessageToActiveChat(msg) {
    let newMessage = templateMessage.cloneNode(true);
    newMessage.removeAttribute("id");
    newMessage.classList.remove("hidden");
    if (msg.is_mine == true) {
        newMessage.classList.add("mine");
    }
    let time = new Date(msg.updated_at);
    newMessage.querySelector(".msg-author").innerText = msg.author;
    newMessage.querySelector(".msg-message").innerText = msg.body;
    newMessage.querySelector(".msg-time").innerText = time.toLocaleTimeString();
    activeChat.appendChild(newMessage);
}
function setChat(friendId) {
    resetChat();
    setLoadingScreen();
    activeFriendId = friendId;
    checkMessages(friendId);
}
function sendMessage(msg, userToken, targetId) {
    return __awaiter(this, void 0, void 0, function* () {
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        // @ts-expect-error
        let req = yield fetch(route("api.message.new"), {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                userToken: userToken,
                targetId: targetId,
                body: msg
            })
        });
        let res = yield req.json();
        if (res.status == 400 || res.status == 401) {
            return;
        }
        if (res.status == 201) {
            addNewMessageToActiveChat(res.response);
            messageInput.value = "";
        }
    });
}
