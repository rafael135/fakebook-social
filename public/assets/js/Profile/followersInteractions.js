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
function followProfile(spanBtn) {
    var _a, _b;
    return __awaiter(this, void 0, void 0, function* () {
        let userId = spanBtn.getAttribute("data-user-id");
        if (userId == null) {
            return;
        }
        userId = parseInt(userId);
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        let req = yield fetch(route("api.user.follow", { id: userId }), {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                userToken: userTokenInput.value
            })
        });
        let res = yield req.json();
        if (res.status >= 400 && res.status <= 420) {
            return;
        }
        let followersCountLink = (_b = (_a = spanBtn.parentElement) === null || _a === void 0 ? void 0 : _a.parentElement) === null || _b === void 0 ? void 0 : _b.querySelector("span.follower-count");
        if (res.status === 200) {
            let refreshedCount = parseInt(followersCountLink.innerText) - 1;
            followersCountLink.innerText = refreshedCount.toString();
            spanBtn.innerText = "Seguir";
        }
        else {
            let refreshedCount = parseInt(followersCountLink.innerText) + 1;
            followersCountLink.innerText = refreshedCount.toString();
            spanBtn.innerText = "Seguindo";
        }
    });
}
