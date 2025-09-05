<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My PHP Blog</title>
  <link rel="stylesheet" href="/src/assets/css/index.css">
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
          <a href="/admin/new-article.php" class="main-header__link main-header__link--btn">
            <span>
              new article
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <title>create new article</title>
              <path d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M12,17V15H7V17H12M17,11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11M17,9V7H7V9H17Z" />
            </svg>
          </a>
        <?php endif; ?>

        <a href="<?= Auth::isLoggedIn() ? "/logout.php" : "/login.php"; ?>" class="main-header__link main-header__link--btn <?= Auth::isLoggedIn() ? "mt-auto" : ""; ?>">

          <span>
            <?= Auth::isLoggedIn() ? "logout" : "login"; ?>
          </span>

          <?php if (Auth::isLoggedIn()): ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <title>logout</title>
              <path d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12M4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z" />
            </svg>
          <?php else: ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <title>login</title>
              <path d="M11 7L9.6 8.4L12.2 11H2V13H12.2L9.6 15.6L11 17L16 12L11 7M20 19H12V21H20C21.1 21 22 20.1 22 19V5C22 3.9 21.1 3 20 3H12V5H20V19Z" />
            </svg>
          <?php endif; ?>

        </a>

      </nav>
    </div>
  </header>