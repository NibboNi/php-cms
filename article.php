<?php

require "includes/init.php";

$conn = require "includes/db.php";

$id = $_GET["id"] ?? null;

if (isset($id)) {
  $article = Article::getWithCategories($id, $conn);

  if ($article) {

    $dates = ["updated_at" => $article[0]["updated_at"], "published_at" => $article[0]["published_at"]];
    $dates = Article::getDates($dates);
  } else {

    $article = null;
  }
} else {

  $article = null;
}

require "includes/header.php";

?>

<main>

  <?php if ($article): ?>

    <article class="article">
      <header class="article__header">

        <?php if ($article[0]["image_file"]): ?>
          <div class="article__img">
            <img src="/uploads/<?= $article[0]["image_file"]; ?>" alt="">
          </div>
        <?php endif; ?>

        <p class="article__crumbs"><a href="/">Articles/</a><span><?= htmlspecialchars($article[0]["title"]); ?></span></p>

        <?php if ($article[0]["category_name"]): ?>
          <div class="article__categories">
            <?php foreach ($article as $a): ?>
              <p class="category category-<?= $a["category_name"]; ?>"><?= htmlspecialchars($a["category_name"]) ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <h2 class="article__title toggle-text"><?= htmlspecialchars($article[0]["title"]); ?></h2>

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
      <p class="article__body"><?= htmlspecialchars($article[0]["content"]); ?></p>
    </article>

  <?php else: ?>

    <h2><a href="index.php">Article not found!</a></h2>

  <?php endif; ?>

</main>

<?php require "includes/footer.php"; ?>