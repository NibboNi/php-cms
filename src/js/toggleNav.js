function toggleNav() {
  const navBtn = document.querySelector("#navBtn");

  if (!navBtn) return;

  const navMenu = document.querySelector("#navMenu");

  navBtn.addEventListener("click", () => {
    navBtn.classList.toggle("open");
    navMenu.classList.toggle("open");
    document.documentElement.classList.toggle("scroll-nav");
  });
}

export default toggleNav;
