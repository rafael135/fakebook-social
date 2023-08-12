let imagePreview = document.getElementById("image-preview") as HTMLImageElement;

async function setImage(fileInput: HTMLInputElement) {
    if(fileInput.files?.length == 0) {
        return;
    }

    // Array with the accepted formats:
    let acceptedFormats = ["image/png", "image/jpeg", "image/jpg"];

    // file uploaded:
    let img: File = fileInput.files![0];

    if(acceptedFormats.includes(img.type) == false) {
        alert("O arquivo não é suportado!");
        return;
    }

    // Create a new instance to read the image
    const fileReader = new FileReader();
    // Read image:
    fileReader.readAsDataURL(img);
    // When is complete, set the source of the preview image:
    fileReader.addEventListener("load", () => {
        imagePreview.src = fileReader.result!.toString();
    });
}