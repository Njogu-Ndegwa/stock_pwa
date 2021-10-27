<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once 'app/vendor/autoload.php';

use app\CSRF;

use app\User;

$User = new User();

$checkSuperAdminResponse = $User->checkSuperAdministrator();

if ($checkSuperAdminResponse['response'] == '200') {
  header("Location:". $_ENV['APP_URL']);
  exit();
}

// if there is a superadministrator go back to index
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/style.min.css">
  <link rel="stylesheet" href="assets/css/messages.min.css">
  <title>Super Administrator Sign Up Page</title>
</head>
<body>
  <div class="form-container">
    <div class="image-section">
      <img src="assets/images/bg.jpg" alt="Image">
    </div>
    <form onsubmit="validateSuperAdminForm(event)" action="app/formhandlers/signUpSuperUser" method="POST">
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
          echo CSRF::createToken();
      ?>
      <h1>Super admin sign up</h1>
      <div class="input-wrapper">
        <input type="email" name="email" required placeholder="Super administrator email *" value="">
      </div>
      <div class="input-wrapper">
        <input type="text" name="username" required placeholder="Super administrator username *" value="">
      </div>
      <div class="input-wrapper">
        <input type="password" name="password" required placeholder="Password *" value="">
      </div>
      <div class="input-wrapper">
        <input type="password" name="conf_password" required placeholder="Confirm password *" value="">
      </div>
      <div class="input-wrapper">
        <input type="submit" name="button" value="Sign up"/>
      </div>

    </form>
  </div>
  <script src="assets/js/app.js"></script>
</body>
</html>
