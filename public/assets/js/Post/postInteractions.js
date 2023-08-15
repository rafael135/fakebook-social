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
let postsDiv = document.querySelector("div.posts");
let newPostForm = document.getElementById("newPostForm");
let openedPostId = -1;
let openedPostModal = document.getElementById("openPost-modal");
let openedPostComments = openedPostModal === null || openedPostModal === void 0 ? void 0 : openedPostModal.querySelector("div.openedPost-comments");
let newCommentInput = document.getElementById("newCommentInput");
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
newCommentInput === null || newCommentInput === void 0 ? void 0 : newCommentInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        makeNewComment();
    }
});
let postInteractingId;
let postInteractingReference;
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
            window.location.reload();
        }
    });
}
function setPostToDelete(postReference, postId) {
    postInteractingId = postId;
    postInteractingReference = postReference;
}
function deletePost() {
    var _a;
    return __awaiter(this, void 0, void 0, function* () {
        let userToken = userTokenInput.value;
        let postId = postInteractingId;
        let postReference = postInteractingReference;
        if (Number.isInteger(postId) == false || postReference instanceof HTMLSpanElement == false) {
            return;
        }
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
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
function likeOpenedPost(likeBtn, postId) {
    return __awaiter(this, void 0, void 0, function* () {
        let userToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let req = yield fetch(route("api.post.like", { id: postId }), {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                userToken: userToken
            })
        });
        let res = yield req.json();
        let outsidePost = document.querySelector(`div.post[data-post-id='${postId}']`);
        let outsidePostLikeBtn = outsidePost.querySelector("span.like-btn");
        if (res.status === 201) {
            likeBtn.classList.add("liked");
            outsidePostLikeBtn.classList.add("text-blue-600");
            outsidePostLikeBtn.classList.remove("text-gray-700");
        }
        else {
            likeBtn.classList.remove("liked");
            outsidePostLikeBtn.classList.add("text-gray-700");
            outsidePostLikeBtn.classList.remove("text-blue-600");
        }
    });
}
function openPost(id) {
    return __awaiter(this, void 0, void 0, function* () {
        openedPostId = id;
        openedPostModal.querySelector("img#openedPost-author--img").src = "";
        openedPostComments.innerHTML = "";
        openedPostModal.querySelector("span.like-btn").classList.remove("liked");
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        openedPostModal.querySelector("div.author--name").classList.add("loading");
        openedPostModal.querySelector("div.author--createdAt").classList.add("loading");
        openedPostModal.querySelector("div.post--text").classList.add("loading");
        let req = yield fetch(route("api.post.get", { id: id }), {
            method: "PATCH",
            headers: headers,
            body: JSON.stringify({
                userToken: userTokenInput.value
            })
        });
        let res = yield req.json();
        if (res.status >= 400 && res.status <= 404) {
            return;
        }
        if (res.response.user === null || res.response.post === null) {
            return;
        }
        openedPostModal.querySelector("div.author--name").classList.remove("loading");
        openedPostModal.querySelector("div.author--createdAt").classList.remove("loading");
        openedPostModal.querySelector("div.post--text").classList.remove("loading");
        openedPostModal.querySelector(".post--action span.like-btn").setAttribute("data-post-id", res.response.post.id.toString());
        openedPostModal.querySelector("span.chat-btn").setAttribute("data-post-id", res.response.post.id.toString());
        openedPostModal.querySelector("span.share-btn").setAttribute("data-post-id", res.response.post.id.toString());
        if (res.response.user.avatar !== null) {
            openedPostModal.querySelector("img#openedPost-author--img").src = res.response.user.avatar_url;
        }
        else {
            openedPostModal.querySelector("img#openedPost-author--img").src = "https://flowbite.com/docs/images/people/profile-picture-5.jpg";
        }
        openedPostModal.querySelector("div.author--name").innerText = res.response.user.name;
        let date = new Date(res.response.post.updated_at);
        openedPostModal.querySelector("div.author--createdAt").innerText = date.toLocaleString();
        openedPostModal.querySelector("div.post--text").innerText = res.response.post.body;
        if (res.response.post.is_liked == true) {
            openedPostModal.querySelector(".post--action span.like-btn").classList.add("liked");
        }
        openedPostModal.querySelector(".post--action span.like-btn").setAttribute("onClick", `likeOpenedPost(this, ${res.response.post.id})`);
        openComments(openedPostModal.querySelector("span.chat-btn"));
    });
}
function addCommentToPost(comment) {
    let newComment = document.getElementById("template-comment").cloneNode(true);
    newComment.removeAttribute("id");
    if (Number.isInteger(comment.id) == true) {
        newComment.setAttribute("data-comment-id", comment.id.toString());
    }
    else {
        newComment.setAttribute("data-comment-id", comment.id);
    }
    if (comment.author.avatar_url == null) {
        newComment.querySelector("img").src = "https://flowbite.com/docs/images/people/profile-picture-5.jpg";
    }
    else {
        newComment.querySelector("img").src = comment.author.avatar_url;
    }
    newComment.querySelector("span.comment--author").innerText = comment.author.name;
    newComment.querySelector("div.comment--body").innerText = comment.body;
    if (Number.isInteger(comment.like_count) == true) {
        newComment.querySelector("div.comment--likes").querySelector("span.like-count").innerText = comment.like_count.toString();
    }
    else {
        newComment.querySelector("div.comment--likes").querySelector("span.like-count").innerText = comment.like_count;
    }
    newComment.querySelector("div.comment--likes span.like-btn").setAttribute("onclick", `likeComment(this, ${comment.id})`);
    if (comment.liked == true) {
        newComment.querySelector("div.comment--likes span.like-btn").classList.add("liked");
    }
    openedPostComments.append(newComment);
}
function openComments(actionBtn) {
    return __awaiter(this, void 0, void 0, function* () {
        let postId = parseInt(actionBtn.getAttribute("data-post-id"));
        let loggedUserToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        headers.append("usrToken", loggedUserToken);
        let req = yield fetch(route("api.post.comments", { id: postId }), {
            method: "GET",
            headers: headers
        });
        let res = yield req.json();
        if (res.status === 400 || res.status === 404) {
            return;
        }
        let commentsInPost = openedPostComments.childNodes;
        res.response.forEach((comment) => {
            let valid = true;
            commentsInPost.forEach((postComment) => {
                if (postComment.getAttribute("data-comment-id") === comment.id.toString()) {
                    valid = false;
                }
            });
            if (valid == true) {
                addCommentToPost(comment);
            }
        });
        openedPostComments.scrollTo(0, openedPostComments.scrollHeight);
    });
}
function makeNewComment() {
    return __awaiter(this, void 0, void 0, function* () {
        let loggedUserToken = userTokenInput.value;
        let postId = openedPostId;
        let commentTxt = newCommentInput.value;
        if (commentTxt == "") {
            return;
        }
        newCommentInput.value = "";
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let req = yield fetch(route("api.post.comments.new", { id: postId }), {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                userToken: loggedUserToken,
                body: commentTxt
            })
        });
        let res = yield req.json();
        if (res.status == 400 || res.status == 401) {
            return;
        }
        addCommentToPost(res.response);
        openedPostComments.scrollTo(0, openedPostComments.scrollHeight);
    });
}
function likeComment(commentRef, id) {
    return __awaiter(this, void 0, void 0, function* () {
        let loggedUserToken = userTokenInput.value;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let req = yield fetch(route("api.comments.like", { id: id }), {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                id: id,
                token: loggedUserToken
            })
        });
        let res = yield req.json();
        if (res.status == 201) {
            commentRef.classList.add("liked");
            let likes = parseInt(commentRef.parentElement.querySelector("span.like-count").innerText);
            likes++;
            commentRef.parentElement.querySelector("span.like-count").innerText = likes.toString();
        }
        else if (res.status == 200) {
            commentRef.classList.remove("liked");
            let likes = parseInt(commentRef.parentElement.querySelector("span.like-count").innerText);
            likes--;
            commentRef.parentElement.querySelector("span.like-count").innerText = likes.toString();
        }
    });
}
function replyComment(id) {
    return __awaiter(this, void 0, void 0, function* () {
    });
}
function sharePost(id) {
}
