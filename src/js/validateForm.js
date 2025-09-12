function validateForm(form) {
  if (!form) return;

  const requiredInputs = form.querySelectorAll("[required]");
  const submitBtn = form.querySelector("[type=submit]");
  const errors = {};

  if (requiredInputs.length === 0) return;

  requiredInputs.forEach(input => {
    const fieldName = input.name.charAt(0).toUpperCase() + input.name.slice(1);
    const inputContainer = input.parentElement;
    const maxLength = input.maxLength;

    const checkValidity = () => {
      if (validateEmpty(input)) {
        return false;
      }
      if (maxLength > 0 && validateLength(input, maxLength)) {
        return false;
      }

      return true;
    };

    const showWarnings = () => {
      removeWarning(inputContainer.querySelector(".form-warning"));

      if (validateEmpty(input)) {
        invalidWarning(
          inputContainer,
          `Warning: ${fieldName} is a required field.`
        );
      } else if (maxLength > 0 && validateLength(input, maxLength)) {
        invalidWarning(
          inputContainer,
          `Warning: a ${fieldName} can't have more than ${maxLength} characters.`
        );
      }
    };

    const handleValidation = (show = false) => {
      const isValid = checkValidity();
      errors[input.name] = isValid;

      if (show && !isValid) {
        showWarnings();
      } else if (isValid) {
        removeWarning(inputContainer.querySelector(".form-warning"));
      }

      toggleSubmitBtn(errors, submitBtn);
    };

    input.addEventListener("input", () => handleValidation(true));
    input.addEventListener("blur", () => handleValidation(true));

    handleValidation();
  });
}

function toggleSubmitBtn(errors, submitBtn) {
  submitBtn.disabled = Object.values(errors).includes(false);
}

function validateEmpty(input) {
  return input.value.trim() === "";
}

function validateLength(input, maxLength) {
  return input.value.trim().length > maxLength;
}

function removeWarning(element) {
  if (element) element.remove();
}

function invalidWarning(inputContainer, message) {
  const warningExists = inputContainer.querySelector(".form-warning");

  if (!warningExists) inputContainer.appendChild(createWarning(message));
}

function createWarning(message, softWarning = false) {
  const warningEl = document.createElement("p");

  warningEl.classList.add("form-warning");

  if (softWarning) {
    warningEl.classList.add("form-warning--soft");
  }

  warningEl.textContent = message;

  return warningEl;
}

export default validateForm;
