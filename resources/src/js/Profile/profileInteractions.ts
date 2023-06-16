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

    if(res.status == 201) { // Resposta de status 201: Foi criado uma relação, então o usuário está sendo seguido
        btn.setAttribute("data-following", "true");
        btn.innerText = "Seguindo";
    } else { // Resposta de status 200: Foi excluído a relação, então o usuário não é mais seguido
        btn.setAttribute("data-following", "false");
        btn.innerText = "Seguir";
    }




}