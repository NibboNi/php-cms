<form method="post" class="article-form">

  <?php if (!empty($article->formErrors)): ?>
    <div class="errors">
      <?php foreach ($article->formErrors as $index => $error): ?>
        <p class="error" style="animation-delay: <?= "0" . ".{$index}s"; ?>;"><?= "Caution: " . $error . "!" ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="input-container">
    <input id="title" name="title" type="text" placeholder=" " value="<?= htmlspecialchars($article->title ?? ""); ?>" maxlength="128" class="input" required>
    <label for="title" class="label">Title</label>
  </div>
  <div class="input-container">
    <textarea name="content" rows="7" id="content" placeholder=" " class="input input--textarea" required><?= htmlspecialchars($article->content ?? ""); ?></textarea>
    <label for="content" class="label">Content</label>
  </div>

  <?php if ($categories): ?>

    <fieldset class="categories">
      <legend class="categories__title">Categories</legend>

      <?php foreach ($categories as $category):  ?>

        <div class="category-input">
          <input
            id="category-<?= $category["id"]; ?>"
            type="checkbox"
            name="category[]"
            value="<?= $category["id"]; ?>"
            <?php if (in_array($category["id"], $categoriesIds)): ?>
            checked
            <?php endif; ?>>
          <label for="category-<?= $category["id"]; ?>"><?= htmlspecialchars($category["name"]) ?></label>
        </div>

      <?php endforeach; ?>


    </fieldset>

  <?php endif; ?>

  <button type="submit" class="btn"><?= $action ?? "save" ?></button>
</form>