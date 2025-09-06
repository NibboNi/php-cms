<?php

require "../includes/init.php";

Auth::requireLogin();

$conn = require "../includes/db.php";

$id = $_GET["id"] ?? null;

if (isset($id)) {

  $article = Article::getById($id, $conn, "id, image_file");

  if (!$article || !$article->image_file) {
    die("Article not found!");
  }
} else {
  die("Article not found!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $previousImage = $article->image_file;

  if ($article->setImageFile($conn, null)) {

    if ($previousImage) {
      unlink("../uploads/$previousImage");
    }

    Url::redirect("/admin/edit-article-image.php?id={$article->id}");
  }
}

require "../includes/header.php";

?>

<main class="container">
  <h2>Delete Article Image</h2>

  <form method="post" class="modal">

    <h2 class="modal__header">Are you sure you want to delete this image?</h2>
    <div class="article-preview">
      <?php if ($article->image_file): ?>
        <img src="/uploads/<?= $article->image_file; ?>" alt="" class="article-preview__img">
      <?php endif; ?>
    </div>

    <div class="modal__actions">
      <button class="btn btn--delete">Delete</button>
      <a href="article.php?id=<?= $article->id ?>" class="btn">Cancel</a>
    </div>
  </form>

</main>

<?php require "../includes/footer.php"; ?>