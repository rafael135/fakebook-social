import { RequestedMessagesResponse, Message, newMessageResponse } from "../BASE/RequestTypes";

let userTokenInput = document.getElementById("userToken") as HTMLInputElement;
let templateMessage = document.getElementById("template-msg") as HTMLDivElement;
let messageInput = document.getElementById("messageInput") as HTMLTextAreaElement;

let activeChat = (document.getElementById("activeChat") as HTMLDivElement).querySelector(".chat") as HTMLDivElement;
let loadingSpin = document.getElementById("loading-spin") as HTMLDivElement;


let activeChatMessages: Message[] = [];

let activeFriendId: number = 0;



messageInput.addEventListener("keydown", (e) => {
    if(e.key === "Enter") {
        e.preventDefault();
        sendMessage(messageInput.value, userTokenInput.value, activeFriendId);
    }
});


function resetChat() {
    activeChat.innerHTML = "";
    
    activeChatMessages = [];
}

function setLoadingScreen() {
    let loadingSpinClone = loadingSpin.cloneNode(true) as HTMLDivElement;

    loadingSpinClone.classList.remove("hide");
    loadingSpinClone.classList.add("show");

    activeChat.appendChild(loadingSpinClone);

    activeChat.classList.add("loading-screen");
}

function loadingCompleted() {
    activeChat.classList.remove("loading-screen");
}

async function checkMessages(target: number) {
    let userToken = userTokenInput.value;

    let headers = new Headers();
    headers.append("Content-Type", "application/json");

    // @ts-expect-error
    let req = await fetch(route("api.chat.getMessages"), {
        method: "POST",
        headers: headers,
        body: JSON.stringify({
            userToken: userToken,
            targetId: target
        })
    });

    let res: RequestedMessagesResponse = await req.json();

    if(res.status == 204) {
        return;
    }

    loadingCompleted();
    resetChat();

    res.response.forEach((msg) => {
        addNewMessageToActiveChat(msg);
    });
}

function addNewMessageToActiveChat(msg: Message) {
    let newMessage = templateMessage.cloneNode(true) as HTMLDivElement;
        
    newMessage.removeAttribute("id");
    newMessage.classList.remove("hidden");

    if(msg.is_mine == true) {
        newMessage.classList.add("mine");
    }

    let time = new Date(msg.updated_at);

    (newMessage.querySelector(".msg-author") as HTMLDivElement).innerText = msg.author;
    (newMessage.querySelector(".msg-message") as HTMLDivElement).innerText = msg.body;
    (newMessage.querySelector(".msg-time") as HTMLDivElement).innerText = time.toLocaleTimeString();

    activeChat.appendChild(newMessage);
}

function setChat(friendId: number) {
    resetChat();

    setLoadingScreen();

    activeFriendId = friendId;
    checkMessages(friendId);
}

async function sendMessage(msg: string, userToken: string, targetId: number) {

    let headers = new Headers();
    headers.append("Content-Type", "application/json");

    // @ts-expect-error
    let req = await fetch(route("api.message.new"), {
        method: "POST",
        headers: headers,
        body: JSON.stringify({
            userToken: userToken,
            targetId: targetId,
            body: msg
        })
    });

    let res: newMessageResponse = await req.json();

    if(res.status == 400 || res.status == 401) {
        return;
    }

    if(res.status == 201) {
        addNewMessageToActiveChat(res.response);
        messageInput.value = "";
    }
}



