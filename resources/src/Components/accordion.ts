let accordionForm = document.querySelector("form[accordion]") as HTMLFormElement;

let accordionBtn = accordionForm?.querySelector(".searchBar-accordion--btn") as HTMLDivElement;
let accordionBody = accordionForm?.querySelector(".searchBar-accordion--body") as HTMLDivElement;

const openAccordion = () => {
    accordionForm.querySelector("div.type-search")?.classList.toggle("opened");
    

    accordionBtn.classList.toggle("opened");
    accordionBody.classList.toggle("show");
}


accordionBtn?.addEventListener("click", () => {
    openAccordion();
});