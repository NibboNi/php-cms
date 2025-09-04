<?php

require "includes/init.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $conn = require "includes/db.php";

  $user = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  if (User::auth($conn, $user, $password)) {
    Auth::login();
    Url::redirect("/");
  } else {
    $loginError = "Incorrect Credentials!";
  }
}

include "includes/header.php";

?>

<form method="post" class="login-form">
  <h2>Login</h2>

  <?php if (!empty($loginError)): ?>
    <div class="errors">
      <p class="error"><?= $loginError; ?></p>
    </div>
  <?php endif; ?>

  <div class="input-container">
    <input id="username" name="username" type="text" placeholder=" " class="input" required>
    <label for="username" class="label">Username</label>
  </div>

  <div class="input-container">
    <input id="password" name="password" type="password" placeholder=" " class="input" required>
    <label for="password" class="label">Password</label>
  </div>

  <button type="submit" class="btn btn--submit">Login</button>
</form>

<?php include "includes/footer.php"; ?>