<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once 'app/vendor/autoload.php';

// check for the existence of a super admin
use app\User;

$User = new User();

$checkSuperAdminResponse = $User->checkSuperAdministrator();

if ($checkSuperAdminResponse['response'] == '204') {
  header("Location: superadministrator-signup");
  exit();
}


use app\CSRF;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/style.min.css">
  <link rel="stylesheet" href="assets/css/messages.min.css">
  <title>Login Page</title>
</head>
<body>
  <div class="form-container">
    <div class="image-section">
      <img src="assets/images/bg.jpg" alt="Image">
    </div>
    <form onsubmit="validateLoginForm(event)" action="app/formhandlers/loginUser" method="POST">
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
      <h1>Login to your account</h1>
      <div class="input-wrapper">
        <input type="text" name="username" required placeholder="Your email or username *" value="">
      </div>
      <div class="input-wrapper">
        <input type="password" name="password" required placeholder="Password *" value="">
      </div>
      <div class="input-wrapper">
        <div class="">
          <span>

            <input type="checkbox" name="remember_me" id="remember_me" value="Yes"><label for="remember_me">Remember Me</label>
          </span>
          <span>
            <a href="forgotpassword.html">Forgot Password?</a>
          </span>
        </div>

      </div>
      <div class="input-wrapper">
        <input type="submit" name="button" value="Log in"/>
      </div>

    </form>
  </div>
  <script src="assets/js/app.js"></script>
</body>
</html>
