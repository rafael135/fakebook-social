function handleEventError(e: FocusEvent) {
    let input = e.target as HTMLInputElement;

    input.classList.remove("error");
    input.value = "";
    input.placeholder = input.getAttribute("data-input-placeholder") ?? "";
    removeEventError(input);
}

const addEventError = (input: HTMLInputElement) => {
    input.addEventListener("focus", handleEventError);
}

const removeEventError = (input: HTMLInputElement) => {
    input.removeEventListener("focus", handleEventError);
}

document.addEventListener("DOMContentLoaded", () => {
    let erroredInputs = document.querySelectorAll("input.error");

    erroredInputs.forEach((input) => {
        addEventError(input as HTMLInputElement);
    });

});