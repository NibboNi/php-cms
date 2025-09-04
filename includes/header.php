<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My PHP Blog</title>
  <link rel="stylesheet" href="/dist/assets/css/index.css">
</head>

<body>

  <header class="main-header-container">
    <div class="main-header">
      <h2 class="main-header__title">
        <a href="/">
          <?= $headerTitle ?? "My PHP CMS"; ?>
        </a>
      </h2>

      <button id="navBtn" class="nav-btn">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav id="navMenu" class="main-header__nav">

        <?php if (Auth::isLoggedIn()): ?>
          <a href="/admin" class="main-header__link">Admin panel</a>
          <a href="/admin/new-article.php" class="main-header__link main-header__link--btn">new article</a>
        <?php endif; ?>

        <a href="<?= Auth::isLoggedIn() ? "/logout.php" : "/login.php"; ?>" class="main-header__link main-header__link--btn mt-auto">
          <?= Auth::isLoggedIn() ? "logout" : "login"; ?>
        </a>

      </nav>
    </div>
  </header>