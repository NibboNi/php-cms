<?php $base = strtok($_SERVER["REQUEST_URI"], "?"); ?>

<nav class="paginator">
  <a href="<?= $base; ?>?page=<?= $paginator->prev; ?>" class="paginator__link <?= $paginator->prev > 0 ? "" : "paginator__link--disabled" ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <title>previous page</title>
      <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
    </svg>
  </a>
  <a href="<?= $base; ?>?page=<?= $paginator->next; ?>" class="paginator__link <?= $page < $totalPages ? "" : "paginator__link--disabled" ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <title>next page</title>
      <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
    </svg>
  </a>
</nav>