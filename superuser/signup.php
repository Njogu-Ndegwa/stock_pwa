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

use app\SuperUser;

$SuperUser = new SuperUser();

$checkSuperUserResponse = $SuperUser->checkSuperUser();

// there is a super user, this page should not be accessed
if ($checkSuperUserResponse['response'] == '200') {
  header("Location:". $_ENV['APP_URL']. "/superuser");
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
  <link rel="stylesheet" href="<?php echo $_ENV['APP_URL']; ?>/assets/css/style.min.css">
  <link rel="stylesheet" href="<?php echo $_ENV['APP_URL']; ?>/assets/css/messages.min.css">
  <title>Super User - Sign Up</title>
</head>
<body>
  <div class="form-container">
    <div class="image-section">
      <img src="<?php echo $_ENV['APP_URL']; ?>/assets/images/bg.jpg" alt="Image">
    </div>
    <form onsubmit="validateSuperAdminForm(event)" action="<?php echo $_ENV['APP_URL']; ?>/app/formhandlers/createSuperUser" method="POST">
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
      <h1>Super user sign up</h1>
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
  <script src="<?php echo $_ENV['APP_URL']; ?>/assets/js/app.js"></script>
</body>
</html>
