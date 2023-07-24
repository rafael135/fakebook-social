"use strict";
let accordionForm = document.querySelector("form[accordion]");
let accordionBtn = accordionForm === null || accordionForm === void 0 ? void 0 : accordionForm.querySelector(".searchBar-accordion--btn");
let accordionBody = accordionForm === null || accordionForm === void 0 ? void 0 : accordionForm.querySelector(".searchBar-accordion--body");
const openAccordion = () => {
    var _a;
    (_a = accordionForm.querySelector("div.type-search")) === null || _a === void 0 ? void 0 : _a.classList.toggle("opened");
    accordionBtn.classList.toggle("opened");
    accordionBody.classList.toggle("show");
};
accordionBtn === null || accordionBtn === void 0 ? void 0 : accordionBtn.addEventListener("click", () => {
    openAccordion();
});
