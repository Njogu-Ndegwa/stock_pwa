<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once '../app/vendor/autoload.php';

use app\SuperUser;

use app\CSRF;

$SuperUser = new SuperUser();

$checkSuperUserResponse = $SuperUser->checkSuperUser();

if ($checkSuperUserResponse['response'] == '204') {
  header("Location: ". $_ENV['APP_URL'] .'/superuser/signup');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo $_ENV['APP_URL']; ?>/assets/css/style.min.css">
  <link rel="stylesheet" href="<?php echo $_ENV['APP_URL']; ?>/assets/css/messages.min.css">
  <title>Super User - Login Page</title>
</head>
<body>
  <div class="form-container">
    <div class="image-section">
      <img src="<?php echo $_ENV['APP_URL']; ?>/assets/images/bg.jpg" alt="Image">
    </div>
    <form onsubmit="validateLoginForm(event)" action="<?php echo $_ENV['APP_URL']; ?>/app/formhandlers/loginSuperUser" method="POST">
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
            <a href="forgotpassword">Forgot Password?</a>
          </span>
        </div>

      </div>
      <div class="input-wrapper">
        <input type="submit" name="button" value="Log in"/>
      </div>

    </form>
  </div>
  <script src="<?php echo $_ENV['APP_URL']; ?>/assets/js/app.min.js"></script>
</body>
</html>
