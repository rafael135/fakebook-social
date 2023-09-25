import { FriendRelationRequest } from "../BASE/RequestTypes";

let userTokenInput = document.getElementById("userToken") as HTMLInputElement;



async function followProfile(spanBtn: HTMLSpanElement) {
    let userId: string | number | null = spanBtn.getAttribute("data-user-id") as string;
    if(userId == null) {
        return;
    }

    userId = parseInt(userId);

    let headers = new Headers();
    headers.append("Content-Type", "application/json");

    // @ts-expect-error
    let req = await fetch(route("api.user.follow", { id: userId }), {
        method: "POST",
        headers: headers,
        body: JSON.stringify({
            userToken: userTokenInput.value
        })
    });

    let res: FriendRelationRequest = await req.json();

    if(res.status >= 400 && res.status <= 420) {
        return;
    }

    
    let followersCountLink = spanBtn.parentElement?.parentElement?.querySelector("span.follower-count") as HTMLSpanElement;

    if(res.status === 200) {
        
        let refreshedCount = parseInt(followersCountLink.innerText) - 1;
        followersCountLink.innerText = refreshedCount.toString();
        
        spanBtn.innerText = "Seguir";
    } else {
        let refreshedCount = parseInt(followersCountLink.innerText) + 1;
        followersCountLink.innerText = refreshedCount.toString();
        
        spanBtn.innerText = "Seguindo";
    }
    
}