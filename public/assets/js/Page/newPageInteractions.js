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
let imagePreview = document.getElementById("image-preview");
function setImage(fileInput) {
    var _a;
    return __awaiter(this, void 0, void 0, function* () {
        if (((_a = fileInput.files) === null || _a === void 0 ? void 0 : _a.length) == 0) {
            return;
        }
        // Array with the accepted formats:
        let acceptedFormats = ["image/png", "image/jpeg", "image/jpg"];
        // file uploaded:
        let img = fileInput.files[0];
        if (acceptedFormats.includes(img.type) == false) {
            alert("O arquivo não é suportado!");
            return;
        }
        // Create a new instance to read the image
        const fileReader = new FileReader();
        // Read image:
        fileReader.readAsDataURL(img);
        // When is complete, set the source of the preview image:
        fileReader.addEventListener("load", () => {
            imagePreview.src = fileReader.result.toString();
        });
    });
}
