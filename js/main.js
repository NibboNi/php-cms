document.addEventListener("DOMContentLoaded", () => {
  toggleNav();

  function toggleNav() {
    const navBtn = document.querySelector("#navBtn");

    if (!navBtn) return;

    const navMenu = document.querySelector("#navMenu");

    navBtn.addEventListener("click", () => {
      navMenu.classList.toggle("open");
    });
  }
});
