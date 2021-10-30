<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!empty($_SESSION['auth_token']) || !empty($_SESSION['auth_uid']) || !empty($_SESSION['auth_uname'])) {
  header("Location: dashboard");

  exit();
}

require_once '../app/vendor/autoload.php';

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
  <title>Forgot Password Page</title>
</head>
<body>
  <div class="form-container">
    <div class="image-section">
      <img src="../assets/images/bg.jpg" alt="Image">
    </div>
    <form class="" action="../app/formhandlers/forgotPasswordSuperUser" method="post">
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
      <h1>Forgot password</h1>
      <div class="input-wrapper">
        <input type="text" name="username" required placeholder="Enter username or email of associated account *" value="">
      </div>

      <div class="input-wrapper">
        <input type="submit" name="button" value="Proceed"/>
      </div>

    </form>
  </div>
</body>
</html>
