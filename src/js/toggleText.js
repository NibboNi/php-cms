function toggleText() {
  const texts = document.querySelectorAll(".toggle-text");

  if (texts.length <= 0) return;

  texts.forEach(text => {
    text.addEventListener("click", () => {
      text.classList.toggle("shorted");
    });
  });
}

export default toggleText;
