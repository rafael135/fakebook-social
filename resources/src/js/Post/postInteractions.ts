import { PostLikeType, PostType, RequestType } from "../BASE/RequestTypes.js";
//import Routes from "../BASE/Routes.js";


let newPostForm = document.getElementById("newPostForm");

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


async function addNewPost(btn: HTMLElement) {
    let body = btn.parentElement?.querySelector("a")!.innerText;
    body = body?.replace(`${newPostForm?.querySelector("span")!.innerHTML}`, "");

    if(body == "") {
        return;
    }

    let userTokenInput = document.querySelector("input[name='userToken']") as HTMLInputElement;
    let userToken = userTokenInput.value;

    let headers =  new Headers();
    headers.append("Content-Type", "application/json");

    let req = await fetch("/api/post/new", {
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



async function likePost(likeBtn: HTMLSpanElement, postId: number) {
    let userToken = (document.querySelector("input[name='userToken']") as HTMLInputElement).value;

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

function openComments(id: number) {

}

function sharePost(id: number) {

}