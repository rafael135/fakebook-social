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
//import Routes from "../BASE/Routes.js";
let newPostForm = document.getElementById("newPostForm");
newPostForm.addEventListener("focusin", (e) => {
    newPostForm.querySelector("span").style.display = "none";
});
newPostForm.addEventListener("focusout", (e) => {
    if (newPostForm.innerText == "") {
        newPostForm.querySelector("span").style.display = "flex";
    }
});
newPostForm.addEventListener("keydown", (e) => {
    if (e.key === "Backspace") {
        if (newPostForm.innerText == "") {
            e.preventDefault();
        }
    }
});
function addNewPost(btn) {
    return __awaiter(this, void 0, void 0, function* () {
        let body = btn.parentElement.querySelector("a").innerText;
        body = body.replace(`${newPostForm.querySelector("span").innerHTML}`, "");
        if (body == "") {
            return;
        }
        let userTokenInput = newPostForm.querySelector("input[name='userToken']");
        let userToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let req = yield fetch("/api/post/new", {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                body: body,
                userToken: userToken
            })
        });
        let status = req.status;
        if (status >= 400 && status <= 404) {
            alert("Erro");
            return;
        }
        let res = yield req.json();
        if (res.status === 201) {
            let newPostNode = document.querySelector("div.post").cloneNode(true);
            let newPost = newPostNode.childNodes[0].parentElement;
            newPost.querySelector(".author--name").innerHTML = `${res.response.user.name}`;
            newPost.querySelector(".post--created_at").innerHTML = `${res.response.post.created_at}`;
            console.log(newPost);
        }
    });
}
function likePost(likeBtn, postId) {
    return __awaiter(this, void 0, void 0, function* () {
        let userToken = newPostForm.querySelector("input[name='userToken']").value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let req = yield fetch(`/api/post/${postId}/like`, {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                userToken: userToken
            })
        });
        let res = yield req.json();
        if (res.status === 201) {
            likeBtn.classList.add("text-blue-600");
            likeBtn.classList.remove("text-gray-700");
        }
        else {
            likeBtn.classList.remove("text-blue-600");
            likeBtn.classList.add("text-gray-700");
        }
    });
}
function openComments(id) {
}
function sharePost(id) {
}
