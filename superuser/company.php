<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION['auth_token']) || empty($_SESSION['auth_uid']) || empty($_SESSION['auth_uname'])) {
  header("HTTP/1.1 403 Forbidden");

  $forbiddenPage = file_get_contents('../403.php');

  // exit($forbiddenPage);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../assets/css/dashboard.min.css">
  <title>Dashboard</title>
</head>
<body>
  <div class="main">

    <div class="navigation-bar glassmorphic">
      <div class="logo-section">
        <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        Logo
      </div>

      <div class="navigation-items-container">
        <a href="dashboard" class="navigation-item">
          <img src="../assets/images/home-solid.svg" alt="home-solid Font Awesome icon">
          Dashboard
        </a>

        <a href="company" class="navigation-item active">
          <img src="../assets/images/industry-solid.svg" alt="industry-solid Font Awesome icon">
          Companies
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Super User / Company
        </div>

        <div class="options">
          <input type="search" name="" value="">
          <img src="../assets/images/user-solid.svg" alt="user-solid font awesome icon">
            <span>
              <!-- <?php echo $_SESSION['auth_uname'] ?> -->
            </span>

          <a href="logout">
            <img src="../assets/images/sign-out-alt-solid.svg" alt="sign-out-alt-solid Font Awesome icon">
          </a>

          <img src="../assets/images/bell-solid.svg" alt="bell-solid Font Awesome icon">
        </div>

      </div>

      <div class="widget-section">

        <div class="widget glassmorphic">
          <div class="text">
            <span>Total Projects</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Open Projects</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Cancelled Projects</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Total Projects</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

      </div>

      <div class="">
        <table>
          <thead>
            <th></th>
          </thead>
        </table>
      </div>

    </div>

  </div>
</body>
</html>
