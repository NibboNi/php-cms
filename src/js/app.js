import toggleNav from "./toggleNav.js";
import toggleText from "./toggleText.js";
import dragDropFile from "./dropFile.js";
import validateForm from "./validateForm.js";
import formatSupport from "./imageSupport.js";
import deleteFromArticle from "./deleteFromArticle.js";

document.addEventListener("DOMContentLoaded", () => {
  formatSupport(
    (window.sandboxApi &&
      window.sandboxApi.parentWindow &&
      window.sandboxApi.parentWindow.document) ||
      document
  );

  toggleNav();
  toggleText();
  dragDropFile();
  validateForm(document.querySelector(".article-form"));
  validateForm(document.querySelector(".contact-form"));
  deleteFromArticle();
});
