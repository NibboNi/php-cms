<?php

require "includes/init.php";

if (!Auth::isLoggedIn()) {
  Url::redirect("/login.php");
}

$article = new Article();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $conn = require "includes/db.php";

  $article->title = $_POST["title"];
  $article->content = $_POST["content"];

  if ($article->create($conn)) {
    Url::redirect("/article.php?id={$article->id}");
  }
}

require "includes/header.php";

?>

<main class="container">
  <h2>New Article</h2>

  <?php

  $action = "Create";
  include "includes/article-form.php";

  ?>
</main>

<?php require "includes/footer.php"; ?>