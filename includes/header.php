<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My PHP Blog</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>

<body>

  <header class="header-container">
    <div class="header">
      <h2 class="header__title">
        <a href="/">
          <?= $headerTitle ?? "My PHP CMS"; ?>
        </a>
      </h2>

      <button id="navBtn" class="nav-btn">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav id="navMenu" class="header__nav">

        <?php if (Auth::isLoggedIn()): ?>
          <a href="/admin" class="header__link">Admin panel</a>
          <a href="/admin/new-article.php" class="header__link header__link--btn">new article</a>
        <?php endif; ?>

        <a href="<?= Auth::isLoggedIn() ? "/logout.php" : "/login.php"; ?>" class="header__link header__link--btn mt-auto">
          <?= Auth::isLoggedIn() ? "logout" : "login"; ?>
        </a>

      </nav>
    </div>
  </header>