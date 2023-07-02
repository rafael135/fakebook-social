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
let templateMessage = document.getElementById("template-message");
let messageInput = document.getElementById("messageInput");
let activeChat = document.getElementById("activeChat");
let activeChatMessages = [];
messageInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        alert("Teste");
    }
});
function resetChat() {
    activeChat.querySelector("div.chat").innerHTML = "";
    activeChatMessages = [];
}
function setChat() {
}
function sendMessage(msg, from, to) {
    return __awaiter(this, void 0, void 0, function* () {
    });
}
function checkMessages() {
}
