import { FriendRelationRequest } from "../BASE/RequestTypes";


async function followProfile(btn: HTMLButtonElement, id: number) {
    //let follow = !(btn.getAttribute("data-following")!.toString() == "true") ? true : false;

    let headers = new Headers();
    headers.append("Content-Type", "application/json");

    let userToken = (document.querySelector("input[name='userToken']") as HTMLInputElement).value;

    console.log(userToken);
    
    // @ts-expect-error
    let req = await fetch(route("api.user.follow", { id: id }), {
        method: "POST",
        headers: headers,
        body: JSON.stringify({
            userToken: userToken
        })
    });

    let res: FriendRelationRequest = await req.json();

    if(res.status >= 400 && res.status <= 420) {
        return;
    }

    let countFollowersSpan : HTMLSpanElement = document.getElementById("user-info--follow-count--followers") as HTMLSpanElement;
    let countFollowersLink : HTMLAnchorElement = countFollowersSpan?.querySelector("a") as HTMLAnchorElement;

    if(res.status == 201) { // Resposta de status 201: Foi criado uma relação, então o usuário está sendo seguido
        countFollowersLink!.innerText = `${parseInt(countFollowersLink!.innerText) + 1}`;

        btn.setAttribute("data-following", "true");
        btn.innerText = "Seguindo";
    } else { // Resposta de status 200: Foi excluído a relação, então o usuário não é mais seguido
        countFollowersLink!.innerText = `${parseInt(countFollowersLink!.innerText) - 1}`;

        btn.setAttribute("data-following", "false");
        btn.innerText = "Seguir";
    }
}

function changeAvatar(imageInput: HTMLInputElement) {
    let form = document.getElementById("form-avatarImage") as HTMLFormElement;

    if(imageInput?.files!.length.valueOf() > 0) {
        form?.submit();
        return;
    }

    alert("Nenhum arquivo selecionado!");
}

function changeCover(imageInput: HTMLInputElement) {
    let form = document.getElementById("form-coverImage") as HTMLFormElement;

    if(imageInput?.files!.length.valueOf() > 0) {
        form?.submit();
        return;
    }

    alert("Nenhum arquivo selecionado!");
}