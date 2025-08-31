<?php

require "includes/init.php";

Auth::requireLogin();

$conn = require "includes/db.php";

$id = $_GET["id"] ?? null;

if (isset($id)) {

  $article = Article::getById($id, $conn, "id, title, content, updated_at");

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

  if ($article->update($conn)) {
    Url::redirect("/article.php?id={$article->id}");
  }
}

require "includes/header.php";

?>

<main class="container">
  <h2>Edit Article</h2>

  <?php

  $action = "Update";
  include "includes/article-form.php";

  ?>
</main>

<?php require "includes/footer.php"; ?>