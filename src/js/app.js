import formatSupport from "./imageSupport.js";
import toggleNav from "./toggleNav.js";
import toggleText from "./toggleText.js";

document.addEventListener("DOMContentLoaded", () => {
  formatSupport(
    (window.sandboxApi &&
      window.sandboxApi.parentWindow &&
      window.sandboxApi.parentWindow.document) ||
      document
  );

  toggleNav();
  toggleText();
});
