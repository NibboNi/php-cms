<?php

require "../includes/init.php";

Auth::requireLogin();

$conn = require "../includes/db.php";

$id = $_GET["id"] ?? null;
$categories = Category::getAll($conn);

if (isset($id)) {

  $article = Article::getById($id, $conn, "id, title, content, updated_at");
  $categoriesIds = array_column($article->getCategories($conn), "id");

  if (!$article) {
    die("Article not found!");
  }
} else {
  die("Article not found!");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $article->title = $_POST["title"];
  $article->content = $_POST["content"];
  $article->updated_at = date("Y-m-d H:i:s");

  $categoriesIds = $_POST["category"] ?? [];

  if ($article->update($conn)) {

    $article->setCategories($conn, $categoriesIds);
    Url::redirect("/admin/article.php?id={$article->id}");
  }
}

require "../includes/header.php";

?>

<main class="container">
  <h2 class="heading">Edit Article</h2>

  <?php

  $action = "Update";
  include "../includes/article-form.php";

  ?>
</main>

<?php require "../includes/footer.php"; ?>