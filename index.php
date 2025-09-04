<?php

require "includes/init.php";

$conn = require "includes/db.php";

$totalArticles = Article::getTotal($conn);
$articlesPerPage = 6;
$totalPages = ceil($totalArticles / $articlesPerPage);

$page = $_GET["page"] ?? 1;

if ($page > $totalPages) {
  Url::redirect("/?page=$totalPages");
}

if ($page < 1) {
  Url::redirect("/");
}

$paginator = new Paginator($page, $articlesPerPage, $totalArticles);

$articles = Article::getPage($conn, $paginator->limit, $paginator->offset);

require "includes/header.php"

?>

<main>

  <?php if (empty($articles)): ?>
    <p>No articles found.</p>
  <?php else: ?>
    <ul class="main-list">

      <?php foreach ($articles as $article): ?>
        <li class="main-list__item">
          <article class="article-card">
            <h2 class="article-card__title">
              <?= htmlspecialchars($article["title"]); ?>
            </h2>
            <p class="article-card__body"><?= htmlspecialchars($article["content"]); ?></p>
            <a href="article.php?id=<?= $article["id"]; ?>" class="article-card__link">keep reading</a>
          </article>
        </li>
      <?php endforeach; ?>

    </ul>
  <?php endif; ?>

  <?php include "includes/pagination.php"; ?>

</main>

<?php require "includes/footer.php"; ?>