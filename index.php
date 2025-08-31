<?php

require "includes/init.php";

$conn = require "includes/db.php";

$articles = Article::getAll($conn);

require "includes/header.php"

?>

<main class="container">

  <!-- <?php if (Auth::isLoggedIn()): ?>
    <a href="new-article.php" class="link">New article</a>
  <?php endif; ?> -->

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

</main>

<?php require "includes/footer.php"; ?>