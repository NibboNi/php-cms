function createModal(
  action = null,
  message = "Are you sure you want to delete this?"
) {
  document.querySelector(".modal")?.remove();

  const modalEl = document.createElement("form");
  const modalHeader = document.createElement("h2");
  const modalActions = document.createElement("div");
  const modalDeleteBtn = document.createElement("button");
  const modalCancelBtn = document.createElement("button");

  modalCancelBtn.type = "button";
  modalCancelBtn.classList.add("btn");
  modalCancelBtn.textContent = "cancel";
  modalCancelBtn.addEventListener("click", cancel);

  modalDeleteBtn.type = "submit";
  modalDeleteBtn.classList.add("btn", "btn--delete");
  modalDeleteBtn.textContent = "delete";

  modalActions.classList.add("modal__actions");
  modalActions.appendChild(modalDeleteBtn);
  modalActions.appendChild(modalCancelBtn);

  modalHeader.classList.add("modal__header");
  modalHeader.textContent = message;

  if (action) {
    modalEl.action = action;
  }
  modalEl.method = "post";
  modalEl.classList.add("modal");
  modalEl.appendChild(modalHeader);
  modalEl.appendChild(modalActions);

  document.body.appendChild(modalEl);
  document.body.classList.add("no-scroll");

  modalCancelBtn.focus();

  function cancel() {
    modalEl.remove();
    document.body.classList.remove("no-scroll");
  }
}

export default createModal;
