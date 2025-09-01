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
    <div class="table-container">
      <table class="table">
        <thead>
          <th>title</th>
          <th>published at</th>
          <th>last edited</th>
          <th colspan="2">actions</th>
        </thead>
        <tbody>
          <?php foreach ($articles as $article): ?>
            <tr>
              <td>
                <a href="/admin/article.php?id=<?= $article["id"]; ?>">
                  <?= htmlspecialchars($article["title"]); ?>
                </a>
              </td>
              <td>
                <time datetime="<?= date_format(new DateTime($article["published_at"]), "Y-m-d H:i:s") ?>">
                  <?= htmlspecialchars($article["published_at"]); ?>
                </time>
              </td>
              <td>
                <?php if (isset($article["updated_at"])): ?>
                  <time datetime="<?= date_format(new DateTime($article["updated_at"]), "Y-m-d H:i:s") ?>">
                    <?= htmlspecialchars($article["updated_at"]); ?>
                  </time>
                <?php else: ?>
                  <p>Not yet edited</p>
                <?php endif; ?>
              </td>
              <td>
                <a href="/admin/edit-article.php?id=<?= $article["id"]; ?>" class="form-action">
                  Edit
                </a>
              </td>
              <td>
                <a href="/admin/delete-article.php?id=<?= $article["id"]; ?>" class="form-action form-action--delete">
                  Delete
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</main>

<?php require "../includes/footer.php"; ?>