import { PostComment, PostLikeType, PostRequestedComments, PostType, RequestType } from "../BASE/RequestTypes.js";
//import Routes from "../BASE/Routes.js";

let userTokenInput = document.getElementById("userToken") as HTMLInputElement;

let newPostForm = document.getElementById("newPostForm");

let openedPostModal = document.getElementById("openPost-modal") as HTMLDivElement;
let openedPostComments = openedPostModal.querySelector("div.openedPost-comments") as HTMLDivElement;

newPostForm?.addEventListener("focusin", (e) => {
    newPostForm!.querySelector("span")!.style.display = "none";
});

newPostForm?.addEventListener("focusout", (e) => {
    if(newPostForm!.innerText == "") {
        newPostForm!.querySelector("span")!.style.display = "flex";
    }
});

newPostForm?.addEventListener("keydown", (e) => {
    if(e.key === "Backspace") {
        if(newPostForm!.innerText == "") {
            e.preventDefault();
        }
    }
});


let postInteractingId: number;
let postInteractingReference: HTMLSpanElement;


async function addNewPost(btn: HTMLElement) {
    let body = btn.parentElement?.querySelector("a")!.innerText;
    body = body?.replace(`${newPostForm?.querySelector("span")!.innerHTML}`, "");

    if(body == "") {
        return;
    }

    let userToken = userTokenInput.value;

    let headers =  new Headers();
    headers.append("Content-Type", "application/json");

    // @ts-expect-error
    let req = await fetch(route("api.post.new"), {
        method: "POST",
        headers: headers,
        body: JSON.stringify({
            body: body,
            userToken: userToken
        })
    });
    let status = req.status;

    if(status >= 400 && status <= 404) {
        alert("Erro");
        return;
    }

    let res: PostType = await req.json();
    if(res.status === 201) {
        let newPostNode = document.querySelector("div.post")!.cloneNode(true);
        let newPost = newPostNode.childNodes[0].parentElement as HTMLDivElement;

        (newPost!.querySelector(".author--name")! as HTMLDivElement).innerHTML = `${res.response.user!.name}`;
        (newPost!.querySelector(".post--created_at")! as HTMLDivElement).innerHTML = `${res.response.post.created_at}`;

        console.log(newPost);
    }
    
}

function setPostToDelete(postReference: HTMLSpanElement, postId: number) {
    postInteractingId = postId
    postInteractingReference = postReference;
}


async function deletePost() {
    let userToken = userTokenInput.value;

    let postId = postInteractingId;
    let postReference = postInteractingReference;

    if(Number.isInteger(postId) == false || postReference instanceof HTMLSpanElement == false) {
        return;
    }

    let headers =  new Headers();
    headers.append("Content-Type", "application/json");

    // @ts-expect-error
    let req = await fetch(route("api.post.delete", { id: postId }), {
        method: "DELETE",
        headers: headers,
        body: JSON.stringify({
            userToken: userToken
        })
    });

    let res: RequestType = await req.json();

    if(res.status == 200) {
        let post = postReference.parentElement?.parentElement!.parentElement!.parentElement!.parentElement;

        post?.remove();
    } else {

    }

}



async function likePost(likeBtn: HTMLSpanElement, postId: number) {
    let userToken = userTokenInput.value;

    let headers =  new Headers();
    headers.append("Content-Type", "application/json");

    // @ts-expect-error
    let req = await fetch(route("api.post.like", {id: postId}), {
        method: "POST",
        headers: headers,
        body: JSON.stringify({
            userToken: userToken
        })
    });



    let res: PostLikeType = await req.json();

    if(res.status === 201) {
        likeBtn.classList.add("text-blue-600");
        likeBtn.classList.remove("text-gray-700");
    } else {
        likeBtn.classList.remove("text-blue-600");
        likeBtn.classList.add("text-gray-700");
    }


}


async function openPost(id: number) {
    openedPostComments.innerHTML = "";

    let headers = new Headers();

    headers.append("Content-Type", "application/json");


    //(openedPostModal.querySelector("div.author--img") as HTMLDivElement).classList.add("loading");
    (openedPostModal.querySelector("div.author--name") as HTMLDivElement).classList.add("loading");
    (openedPostModal.querySelector("div.author--createdAt") as HTMLDivElement).classList.add("loading");
    (openedPostModal.querySelector("div.post--text") as HTMLDivElement).classList.add("loading");

    // @ts-expect-error
    let req = await fetch(route("api.post.get", { id: id }), {
        method: "GET",
        headers: headers
    });

    let res: PostType = await req.json();

    if(res.status >= 400 && res.status <= 404) {
        return;
    }

    if(res.response.user === null || res.response.post === null) {
        return;
    }

    //(openedPostModal.querySelector("div.author--img") as HTMLDivElement).classList.remove("loading");
    (openedPostModal.querySelector("div.author--name") as HTMLDivElement).classList.remove("loading");
    (openedPostModal.querySelector("div.author--createdAt") as HTMLDivElement).classList.remove("loading");
    (openedPostModal.querySelector("div.post--text") as HTMLDivElement).classList.remove("loading");



    (openedPostModal.querySelector("span.like-btn") as HTMLSpanElement).setAttribute("data-post-id", res.response.post.id.toString());
    (openedPostModal.querySelector("span.chat-btn") as HTMLSpanElement).setAttribute("data-post-id", res.response.post.id.toString());
    (openedPostModal.querySelector("span.share-btn") as HTMLSpanElement).setAttribute("data-post-id", res.response.post.id.toString());


    if(res.response.user.avatar !== null) {
        (openedPostModal.querySelector("img#openedPost-author--img") as HTMLImageElement).src = res.response.user.avatar;
    } else {
        (openedPostModal.querySelector("img#openedPost-author--img") as HTMLImageElement).src = "https://flowbite.com/docs/images/people/profile-picture-5.jpg";
    }

    (openedPostModal.querySelector("div.author--name") as HTMLDivElement).innerText = res.response.user.name;

    let date = new Date(res.response.post.updated_at);
    (openedPostModal.querySelector("div.author--createdAt") as HTMLDivElement).innerText = date.toLocaleString();

    (openedPostModal.querySelector("div.post--text") as HTMLDivElement).innerText = res.response.post.body;
    
}

function addCommentsToPost(comment: PostComment) {
    let newComment = (document.getElementById("template-comment") as HTMLDivElement).cloneNode(true) as HTMLDivElement;

    newComment.removeAttribute("id");
    newComment.setAttribute("data-comment-id", comment.id.toString());

    if(comment.author.avatar == null) {
        (newComment.querySelector("img") as HTMLImageElement).src = "https://flowbite.com/docs/images/people/profile-picture-5.jpg";
    } else {
        (newComment.querySelector("img") as HTMLImageElement).src = comment.author.avatar;
    }

    (newComment.querySelector("span.comment--author") as HTMLSpanElement).innerText = comment.author.name;

    (newComment.querySelector("div.comment--body") as HTMLDivElement).innerText = comment.body;

    (newComment.querySelector("span.comment--likes") as HTMLSpanElement).innerText = comment.like_count.toString();


    openedPostComments.append(newComment);
}

async function openComments(actionBtn: HTMLSpanElement) {
    let postId = parseInt(actionBtn.getAttribute("data-post-id") as string);

    let headers = new Headers();
    headers.append("Content-Type", "application/json");

    // @ts-expect-error
    let req = await fetch(route("api.post.comments", { id: postId }), {
        method: "GET",
        headers: headers
    })

    let res: PostRequestedComments = await req.json();

    if(res.status === 400 || res.status === 404) {
        return;
    }

    let commentsInPost = openedPostComments.childNodes;

    res.response.forEach((comment) => {
        let valid = true;

        commentsInPost.forEach((postComment) => {
            if((postComment as HTMLDivElement).getAttribute("data-comment-id") === comment.id.toString()) {
                valid = false;
            }
        });

        if(valid == true) {
            addCommentsToPost(comment);
        }
    });

    
}

function sharePost(id: number) {
    
}