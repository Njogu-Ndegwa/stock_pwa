<?php
if (!isset($_GET['token']) || empty($_GET['token'])) {
  header("HTTP/1.1 403 Forbidden");

  $forbiddenPage = file_get_contents('./403.php');

  exit($forbiddenPage);
}
if (!isset($_SESSION)) {
    session_start();
}

require_once 'app/vendor/autoload.php';

use app\CSRF;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../assets/css/style.min.css">
  <link rel="stylesheet" href="../assets/css/messages.min.css">
  <title>Enter Token</title>
</head>
<body>
  <div class="form-container">
    <div class="image-section">
      <img src="../assets/images/bg.jpg" alt="Image">
    </div>
    <form class="" action="../app/formhandlers/tokenConfirm" method="post">
      <?php
          if (isset($_SESSION['error'])) {
      ?>
          <div class="error-message">
              <?php echo $_SESSION['error']; ?>
          </div>
      <?php
          unset($_SESSION['error']);
          }
      ?>

      <?php
          if (isset($_SESSION['success'])) {
      ?>
          <div class="success-message">
              <?php echo $_SESSION['success']; ?>
          </div>
      <?php
          unset($_SESSION['success']);
          }
      ?>

      <?php
          echo CSRF::createToken();
      ?>
      <input type="hidden" name="url_token" value="<?php echo $_GET['token'] ?>">
      <h1>Enter the code sent in your email</h1>
      <div class="input-wrapper">
        <input type="text" name="code" placeholder="Enter code*" value="">
      </div>

      <div class="input-wrapper">
        <input type="submit" name="button" value="Proceed"/>
      </div>

    </form>
  </div>
</body>
</html>
