<?php

require "../includes/init.php";

Auth::requireLogin();

$conn = require "../includes/db.php";

$id = $_GET["id"] ?? null;

if (isset($id)) {

  $article = Article::getById($id, $conn, "id, title, content");

  if (!$article) {

    die("Article not found!");
  }
} else {

  die("Article not found!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if ($article->delete($conn)) {
    Url::redirect("/admin");
  }
}

include "../includes/header.php";

?>

<form action="delete-article.php?id=<?= $article->id ?>" method="post" class="modal">
  <h2 class="modal__header">Are you sure you want to delete this post?</h2>
  <div class="article-preview">
    <h3 class="article-preview__title"><?= $article->title; ?></h3>
    <p class="article-preview__content"><?= $article->content; ?></p>
  </div>
  <div class="modal__actions">
    <button class="btn btn--delete">Delete</button>
    <a href="article.php?id=<?= $article->id ?>" class="btn">Cancel</a>
  </div>
</form>

<?php "../includes/footer.php"; ?>