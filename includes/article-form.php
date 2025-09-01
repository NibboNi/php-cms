<?php if (!empty($article->formErrors)): ?>
  <div class="errors">
    <?php foreach ($article->formErrors as $index => $error): ?>
      <p class="error" style="animation-delay: <?= "0" . ".{$index}s"; ?>;"><?= "Caution: " . $error . "!" ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form method="post" class="form">
  <div class="input-container">
    <input id="title" name="title" type="text" placeholder=" " value="<?= htmlspecialchars($article->title ?? ""); ?>" maxlength="128" class="input" required>
    <label for="title" class="label">Title</label>
  </div>
  <div class="input-container">
    <textarea name="content" rows="7" id="content" placeholder=" " class="input input--textarea" required><?= htmlspecialchars($article->content ?? ""); ?></textarea>
    <label for="content" class="label">Content</label>
  </div>
  <button type="submit" class="btn"><?= $action ?? "save" ?></button>
</form>