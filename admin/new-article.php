<?php

require "../includes/init.php";

Auth::requireLogin();

$conn = require "../includes/db.php";

$article = new Article();
$categories = Category::getAll($conn);
$categoriesIds = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $article->title = $_POST["title"];
  $article->content = $_POST["content"];

  $categoriesIds = $_POST["category"] ?? [];

  if ($article->create($conn)) {

    $article->setCategories($conn, $categoriesIds);
    Url::redirect("/admin/article.php?id={$article->id}");
  }
}

require "../includes/header.php";

?>

<main class="container">
  <h2 class="heading">New Article</h2>

  <?php

  $action = "Create";
  include "../includes/article-form.php";

  ?>
</main>

<?php require "../includes/footer.php"; ?>