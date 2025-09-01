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

<main class="container">

  <?php if (empty($articles)): ?>
    <p>No articles found.</p>
  <?php else: ?>
    <ul class="list">

      <?php foreach ($articles as $article): ?>
        <li class="list__item">
          <article>
            <h2>
              <a href="article.php?id=<?= $article["id"]; ?>">
                <?= htmlspecialchars($article["title"]); ?>
              </a>
            </h2>
            <p><?= htmlspecialchars($article["content"]); ?></p>
          </article>
        </li>
      <?php endforeach; ?>

    </ul>
  <?php endif; ?>

  <?php include "includes/pagination.php"; ?>

</main>

<?php require "includes/footer.php"; ?>