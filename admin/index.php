<?php

require  "../includes/init.php";

Auth::requireLogin();

$conn = require "../includes/db.php";

$totalArticles = Article::getTotal($conn);
$articlesPerPage = 10;
$totalPages = ceil($totalArticles / $articlesPerPage);

$page = $_GET["page"] ?? 1;

if ($page > $totalPages) {
  Url::redirect("/admin/?page=$totalPages");
}

if ($page < 1) {
  Url::redirect("/admin/?page=1");
}

$paginator = new Paginator($page, $articlesPerPage, $totalArticles);

$articles = Article::getPage($conn, $paginator->limit, $paginator->offset);

$headerTitle = "Admin Panel";

require "../includes/header.php"

?>

<main class="container">

  <?php include "../includes/pagination.php"; ?>

  <?php if (empty($articles)): ?>
    <p>No articles found.</p>
  <?php else: ?>
    <table>
      <thead>
        <th>title</th>
      </thead>
      <tbody>
        <?php foreach ($articles as $article): ?>
          <tr>
            <td>
              <a href="article.php?id=<?= $article["id"]; ?>">
                <?= htmlspecialchars($article["title"]); ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</main>

<?php require "../includes/footer.php"; ?>