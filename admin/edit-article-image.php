<?php

require "../includes/init.php";

Auth::requireLogin();

$conn = require "../includes/db.php";

$id = $_GET["id"] ?? null;

if (isset($id)) {

  $article = Article::getById($id, $conn, "id, image_file");

  if (!$article) {
    die("Article not found!");
  }
} else {
  die("Article not found!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  try {
    if (empty($_FILES)) {
      throw new Exception("Invalid upload");
    }

    switch ($_FILES["image"]["error"]) {
      case UPLOAD_ERR_OK:
        break;

      case UPLOAD_ERR_INI_SIZE:
        throw new Exception("File too large");
        break;

      case UPLOAD_ERR_NO_FILE:
        throw new Exception("No file uploaded");
        break;

      default:
        throw new Exception("An error ocurred!");
    }

    if ($_FILES["image"]["size"] > 1000000) {
      throw new Exception("File is too large!");
    }

    $mimeTypes = ["image/gif", "image/png", "image/jpeg", "image/webp", "image/avif"];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES["image"]["tmp_name"]);

    if (!in_array($mimeType, $mimeTypes)) {
      throw new Exception("Invalid image format!");
    }

    // Creating file if it doesn't exist
    $uploadsDir = __DIR__ . "/../uploads";

    if (!is_dir($uploadsDir)) {
      mkdir($uploadsDir, 0755, true);
    }

    // Sanitazing file name
    $pathInfo = pathinfo($_FILES["image"]["name"]);
    $base = $pathInfo["filename"];
    $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);
    $base = mb_substr($base, 0, 200);
    $fileName  = $base . "." . $pathInfo["extension"];

    // Move the uploaded file
    $destination = $uploadsDir . "/$fileName";

    // Check if file name doesn't already exists
    $suffix = 1;
    while (file_exists($destination)) {
      $fileName  = $base . "-$suffix." . $pathInfo["extension"];
      $destination = $uploadsDir . "/$fileName";

      $suffix++;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {

      $previousImage = $article->image_file;

      if ($article->setImageFile($conn, $fileName)) {

        if ($previousImage) {
          unlink("../uploads/$previousImage");
        }

        Url::redirect("/admin/edit-article-image.php?id={$article->id}");
      }
    } else {
      throw new Exception("Unable to save uploaded image.");
    }
  } catch (Exception $e) {
    $fileErrorMessage = $e->getMessage();
  }
}

require "../includes/header.php";

?>

<main class="container">
  <h2>Edit Article Image</h2>

  <?php if ($article->image_file): ?>
    <img src="/uploads/<?= $article->image_file; ?>" alt="">
    <a href="/admin/delete-article-image.php?id=<?= $article->id; ?>">Delete</a>
  <?php endif; ?>

  <?php if (isset($fileErrorMessage)): ?>
    <p><?= $fileErrorMessage; ?></p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="form">

    <div class="input-container">
      <input id="image" name="image" type="file" class="input">
      <label for="image" class="label">Image</label>
    </div>

    <button type="submit" class="btn">Upload</button>
  </form>

</main>

<?php require "../includes/footer.php"; ?>