<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

require "includes/init.php";

$email = "";
$subject = "";
$message = "";
$sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $subject = $_POST["subject"];
  $message = $_POST["message"];

  $errors = [];

  if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors[] = "Please enter a valid email address.";
  }

  if (trim($subject) === "") {
    $errors[] = "Please enter a subject.";
  }

  if (trim($message) === "") {
    $errors[] = "Please enter a message.";
  }

  if (empty($errors)) {
    $mail = new PHPMailer(true);

    try {

      $mail->isSMTP();
      $mail->Host = SMTP_HOST;
      $mail->SMTPAuth = true;
      $mail->Username = SMTP_USER;
      $mail->Password = SMTP_PASS;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = SMTP_PORT;

      $mail->setFrom(SMTP_USER, "Contact");
      $mail->addAddress(SMTP_USER);
      $mail->addReplyTo($email);
      $mail->Subject = $subject;
      $mail->Body = $message;

      $mail->send();
      $sent = true;
    } catch (Exception $e) {

      $errors[] = $mail->ErrorInfo;
    }
  }
}

require "includes/header.php";

?>

<form method="post" class="contact-form">
  <?php if (!empty($errors)): ?>
    <div class="errors">
      <?php foreach ($errors as $index => $error): ?>
        <p class="error" style="animation-delay: <?= "0.{$index}s"; ?>;"><?= "Caution: " . $error ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($sent): ?>
    <p>Email Sent!</p>
  <?php endif; ?>

  <h1>Contact</h1>
  <div class="input-container">
    <input id="email" name="email" type="email" placeholder=" " class="input" value="<?= htmlspecialchars($email); ?>" required>
    <label for="email" class="label">Your Email</label>
  </div>

  <div class="input-container">
    <input id="subject" name="subject" type="text" placeholder=" " class="input" value="<?= htmlspecialchars($subject); ?>" required>
    <label for="subject" class="label">Subject</label>
  </div>

  <div class="input-container">
    <textarea id="message" name="message" placeholder=" " class="input" required><?= htmlspecialchars($message); ?></textarea>
    <label for="message" class="label">Your message</label>
  </div>

  <button type="submit" class="btn">Send</button>

</form>

<?php require "includes/footer.php" ?>