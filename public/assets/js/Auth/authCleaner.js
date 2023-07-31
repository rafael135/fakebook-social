"use strict";
function handleEventError(e) {
    var _a;
    let input = e.target;
    input.classList.remove("error");
    input.value = "";
    input.placeholder = (_a = input.getAttribute("data-input-placeholder")) !== null && _a !== void 0 ? _a : "";
    removeEventError(input);
}
const addEventError = (input) => {
    input.addEventListener("focus", handleEventError);
};
const removeEventError = (input) => {
    input.removeEventListener("focus", handleEventError);
};
document.addEventListener("DOMContentLoaded", () => {
    let erroredInputs = document.querySelectorAll("input.error");
    erroredInputs.forEach((input) => {
        addEventError(input);
    });
});
