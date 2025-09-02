<?php

require "includes/init.php";

$conn = require "includes/db.php";

$id = $_GET["id"] ?? null;

if (isset($id)) {
  $article = Article::getById($id, $conn);

  if ($article) {

    $dates = $article->getDates();
  } else {

    $article = null;
  }
} else {

  $article = null;
}

require "includes/header.php";

?>

<main class="container article-container">

  <?php if ($article): ?>

    <article class="article">
      <header class="article__header">
        <?php if ($article->image_file): ?>
          <img src="/uploads/<?= $article->image_file; ?>" alt="">
        <?php endif; ?>
        <h2 class="article__title"><?= htmlspecialchars($article->title); ?></h2>
        <div class="article__date">
          <div class="<?= isset($dates["updatedDate"]) ? "original-date" : "" ?>">

            <?php if (isset($dates["updatedDate"])): ?>
              <p>Original Publication:</p>
            <?php endif; ?>

            <time datetime="<?= $dates["publishedDateTime"] ?>"><?= $dates["publishedDate"]; ?></time>
          </div>

          <?php if (isset($dates["updatedDate"])): ?>
            <div class="update-date">
              <p>Last Updated:</p>
              <time datetime="<?= $dates["updatedDateTime"] ?>"><?= $dates["updatedDate"]; ?></time>
            </div>
          <?php endif; ?>

        </div>
      </header>
      <p class="article__body"><?= htmlspecialchars($article->content); ?></p>
    </article>

  <?php else: ?>

    <h2><a href="index.php">Article not found!</a></h2>

  <?php endif; ?>

</main>

<?php require "includes/footer.php"; ?>