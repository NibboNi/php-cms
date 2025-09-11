import createModal from "./createModal.js";

function deleteFromArticle() {
  const deleteArticleBtn = document.querySelector("#delete-article");
  const deleteArticleImgBtn = document.querySelector("#delete-article-img");

  if (!deleteArticleBtn && !deleteArticleImgBtn) return;

  if (deleteArticleBtn) {
    deleteArticleBtn.addEventListener("click", e => {
      deleteRecord(e, "Are you sure you want to delete this article?");
    });
  }

  if (deleteArticleImgBtn) {
    deleteArticleImgBtn.addEventListener("click", e => {
      deleteRecord(e, "Are you sure you want to delete this image?");
    });
  }
}

function deleteRecord(event, message) {
  event.preventDefault();

  const action = event.target.href;
  createModal(action, message);
}

export default deleteFromArticle;
