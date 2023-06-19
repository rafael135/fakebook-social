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
let userTokenInput = document.querySelector("input[name='userToken']");
let newPostForm = document.getElementById("newPostForm");
newPostForm === null || newPostForm === void 0 ? void 0 : newPostForm.addEventListener("focusin", (e) => {
    newPostForm.querySelector("span").style.display = "none";
});
newPostForm === null || newPostForm === void 0 ? void 0 : newPostForm.addEventListener("focusout", (e) => {
    if (newPostForm.innerText == "") {
        newPostForm.querySelector("span").style.display = "flex";
    }
});
newPostForm === null || newPostForm === void 0 ? void 0 : newPostForm.addEventListener("keydown", (e) => {
    if (e.key === "Backspace") {
        if (newPostForm.innerText == "") {
            e.preventDefault();
        }
    }
});
function addNewPost(btn) {
    var _a;
    return __awaiter(this, void 0, void 0, function* () {
        let body = (_a = btn.parentElement) === null || _a === void 0 ? void 0 : _a.querySelector("a").innerText;
        body = body === null || body === void 0 ? void 0 : body.replace(`${newPostForm === null || newPostForm === void 0 ? void 0 : newPostForm.querySelector("span").innerHTML}`, "");
        if (body == "") {
            return;
        }
        let userToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        // @ts-expect-error
        let req = yield fetch(route("api.post.new"), {
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
function deletePost(postReference, postId) {
    var _a;
    return __awaiter(this, void 0, void 0, function* () {
        let userToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        // @ts-expect-error
        let req = yield fetch(route("api.post.delete", { id: postId }), {
            method: "DELETE",
            headers: headers,
            body: JSON.stringify({
                userToken: userToken
            })
        });
        let res = yield req.json();
        if (res.status == 200) {
            let post = (_a = postReference.parentElement) === null || _a === void 0 ? void 0 : _a.parentElement.parentElement.parentElement.parentElement;
            post === null || post === void 0 ? void 0 : post.remove();
        }
        else {
        }
    });
}
function likePost(likeBtn, postId) {
    return __awaiter(this, void 0, void 0, function* () {
        let userToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        // @ts-expect-error
        let req = yield fetch(route("api.post.like", { id: postId }), {
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
