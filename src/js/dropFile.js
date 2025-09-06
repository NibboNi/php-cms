function dragDropFile() {
  const dropZone = document.querySelector("#drop-zone");
  if (!dropZone) return;

  const imgInput = document.querySelector("#image");

  dropZone.addEventListener("dragover", e => {
    e.preventDefault();
    dropZone.classList.add("dropped");
  });

  dropZone.addEventListener("dragleave", () => {
    if (!imgInput.value) {
      dropZone.classList.remove("dropped");
    }
  });

  dropZone.addEventListener("drop", e => {
    e.preventDefault();

    const files = e.dataTransfer.files;
    imgInput.files = files;

    createFile(files[0]);
  });

  imgInput.addEventListener("change", () => {
    const [file] = imgInput.files;

    createFile(file);
  });
}

function createFile(file) {
  const dropZone = document.querySelector("#drop-zone");
  const imgPreview = document.querySelector("#img-preview");
  const imgPreviewName = document.querySelector(".img-preview__name");

  if (file) {
    dropZone.classList.add("dropped");
    imgPreviewName.textContent = file.name;
    imgPreview.src = URL.createObjectURL(file);
    imgPreview.classList.add("img-preview__img--display");
  }
}

export default dragDropFile;
