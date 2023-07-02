type chatMessage = {
    id: number;
    user_from: number;
    user_to: number;
    body: string;
    updated_at: string;
}

let templateMessage = document.getElementById("template-message") as HTMLDivElement;
let messageInput = document.getElementById("messageInput") as HTMLTextAreaElement;

let activeChat = document.getElementById("activeChat") as HTMLDivElement;


let activeChatMessages: chatMessage[] = [];



messageInput.addEventListener("keydown", (e) => {
    if(e.key === "Enter") {
        alert("Teste");
    }
});


function resetChat() {
    (activeChat.querySelector("div.chat") as HTMLDivElement).innerHTML = "";
    
    activeChatMessages = [];
}

function setChat() {

}

async function sendMessage(msg: string, from: number, to: number) {
    
}


function checkMessages() {

}