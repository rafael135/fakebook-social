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
function followProfile(btn, id) {
    return __awaiter(this, void 0, void 0, function* () {
        //let follow = !(btn.getAttribute("data-following")!.toString() == "true") ? true : false;
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let userToken = document.querySelector("input[name='userToken']").value;
        console.log(userToken);
        // @ts-expect-error
        let req = yield fetch(route("api.user.follow", { id: id }), {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                userToken: userToken
            })
        });
        let res = yield req.json();
        if (res.status >= 400 && res.status <= 420) {
            return;
        }
        let countFollowersSpan = document.getElementById("user-info--follow-count--followers");
        let countFollowersLink = countFollowersSpan === null || countFollowersSpan === void 0 ? void 0 : countFollowersSpan.querySelector("a");
        if (res.status == 201) { // Resposta de status 201: Foi criado uma relação, então o usuário está sendo seguido
            countFollowersLink.innerText = `${parseInt(countFollowersLink.innerText) + 1}`;
            btn.setAttribute("data-following", "true");
            btn.innerText = "Seguindo";
        }
        else { // Resposta de status 200: Foi excluído a relação, então o usuário não é mais seguido
            countFollowersLink.innerText = `${parseInt(countFollowersLink.innerText) - 1}`;
            btn.setAttribute("data-following", "false");
            btn.innerText = "Seguir";
        }
    });
}
