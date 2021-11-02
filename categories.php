<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION['auth_token']) || empty($_SESSION['auth_uid']) || empty($_SESSION['auth_uname'])) {
  header("HTTP/1.1 403 Forbidden");

  $forbiddenPage = file_get_contents('./403.php');

  // exit($forbiddenPage);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/dashboard.min.css">
  <title>Dashboard</title>
</head>
<body>
  <div class="main">

    <div class="navigation-bar glassmorphic">
      <div class="logo-section">
        <img src="assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        Logo
      </div>

      <div class="navigation-items-container">
        <a href="dashboard" class="navigation-item">
          <img src="assets/images/home-solid.svg" alt="home-solid Font Awesome icon">
          Dashboard
        </a>

        <h3>Projects</h3>

        <a href="#" class="navigation-item">
          <img src="assets/images/industry-solid.svg" alt="industry-solid Font Awesome icon">
          Powder Coating
        </a>

        <h3>Office</h3>
        <a href="locations" class="navigation-item">
          <img src="assets/images/map-marker-alt-solid.svg" alt="map-marker-alt-solid Font Awesome icon">
          Locations
        </a>

        <a href="warehouses" class="navigation-item">
          <img src="assets/images/store-alt-solid.svg" alt="store-alt-solid Font Awesome icon">
          Warehouses
        </a>

        <a href="categories" class="navigation-item active">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Categories
        </a>

        <a href="users.html" class="navigation-item">
          <img src="assets/images/chess-pawn-solid.svg" alt="chess-pawn-solid Font Awesome icon">
          Subcategories
        </a>

        <a href="users.html" class="navigation-item">
          <img src="assets/images/people-carry-solid.svg" alt="people-carry-solid Font Awesome icon">
          Vendor/Supplier
        </a>

        <a href="users.html" class="navigation-item">
          <img src="assets/images/user-friends-solid.svg" alt="user-friends-solid Font Awesome icon">
          Customers
        </a>

        <a href="users.html" class="navigation-item">
          <img src="assets/images/money-check-alt-solid.svg" alt="money-check-alt-solid Font Awesome icon">
          Sales
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Home / Locations
        </div>

        <div class="options">
          <input type="search" name="" value="">
          <img src="assets/images/user-solid.svg" alt="user-solid font awesome icon"><span><?php echo $_SESSION['auth_uname'] ?></span>

          <a href="logout">
            <img src="assets/images/sign-out-alt-solid.svg" alt="sign-out-alt-solid Font Awesome icon">
          </a>

          <img src="assets/images/bell-solid.svg" alt="bell-solid Font Awesome icon">
        </div>

      </div>



      <table class="table-data glassmorphic">
        <thead>
          <th colspan="8">
            <h2>Companies Present</h2>
          </th>
        </thead>
        <thead>
          <th>Company Name</th>
          <th>Company Email</th>
          <th>Subscription Expiry</th>
          <th>Activation Key</th>
          <th>Key Validity</th>
          <th>Date of creation</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($companiesPresentResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="8">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($companiesPresentResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="8">
                <h3>No companies present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($companiesPresentResponse['data'] as $singleCompanyInfo) {
            ?>
            <tr>
              <td><?php echo $singleCompanyInfo['company_name'] ?></td>
              <td><?php echo $singleCompanyInfo['email'] ?></td>
              <td><?php echo date('Y-m-d h:i:sa',$singleCompanyInfo['subscription_expiry']) ?></td>
              <td class="sensitive"><?php echo $singleCompanyInfo['activation_key'] ?></td>
              <td><?php echo $keyValidity = ($singleCompanyInfo['key_validity']) ? "Valid" : "Invalid" ; ?></td>
              <td><?php echo $singleCompanyInfo['date_created'] ?></td>
              <td>
                <button class="action-delete-btn" onclick="openModal('#deleteCompany<?php echo $singleCompanyInfo['entry_id']; ?>')">Delete</button>
              </td>
            </tr>
            <div class="modal" id="deleteCompany<?php echo $singleCompanyInfo['entry_id']; ?>">

              <div class="modal-dialog">
                  <div class="modal-head">
                    <h2>Delete company: <?php echo $singleCompanyInfo['company_name'] ?></h2>
                  </div>
                  <div class="modal-body">
                    <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/deleteCompany" method="post">
                      <?php
                          echo CSRF::createToken();
                      ?>
                      <p>Are you sure you want to delete this company?</p>
                      <input type="hidden" name="company_id" value="<?php echo $singleCompanyInfo['entry_id'] ?>">

                      <input type="submit" name="submit" value="Yes I am">
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="close-modal-btn" onclick="closeModal('#deleteCompany<?php echo $singleCompanyInfo['entry_id']; ?>')" name="button">No &times;</button>
                  </div>
              </div>

            </div>
            <?php
            }
          }
          ?>
        </tbody>
      </table>

    </div>

  </div>
</body>
</html>
