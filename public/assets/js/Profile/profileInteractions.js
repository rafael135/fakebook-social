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
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let userToken = document.querySelector("input[name='userToken']").value;
        console.log(userToken);
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
        if (res.status == 201) {
            countFollowersLink.innerText = `${parseInt(countFollowersLink.innerText) + 1}`;
            btn.setAttribute("data-following", "true");
            btn.innerText = "Seguindo";
        }
        else {
            countFollowersLink.innerText = `${parseInt(countFollowersLink.innerText) - 1}`;
            btn.setAttribute("data-following", "false");
            btn.innerText = "Seguir";
        }
    });
}
function changeAvatar(imageInput) {
    let form = document.getElementById("form-avatarImage");
    if ((imageInput === null || imageInput === void 0 ? void 0 : imageInput.files.length.valueOf()) > 0) {
        form === null || form === void 0 ? void 0 : form.submit();
        return;
    }
    alert("Nenhum arquivo selecionado!");
}
function changeCover(imageInput) {
    let form = document.getElementById("form-coverImage");
    if ((imageInput === null || imageInput === void 0 ? void 0 : imageInput.files.length.valueOf()) > 0) {
        form === null || form === void 0 ? void 0 : form.submit();
        return;
    }
    alert("Nenhum arquivo selecionado!");
}
