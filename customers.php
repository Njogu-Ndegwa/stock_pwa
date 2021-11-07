<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION['auth_token']) || empty($_SESSION['auth_uid']) || empty($_SESSION['auth_uname'])) {
  header("HTTP/1.1 403 Forbidden");

  $forbiddenPage = file_get_contents('./403.php');

  // exit($forbiddenPage);
}
require_once 'app/vendor/autoload.php';

use app\CSRF;

use app\Customer;

$Customer = new Customer();

$getCustomersResponse = $Customer->getCustomers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/dashboard.min.css">
  <link rel="stylesheet" href="assets/css/casual.min.css">
  <link rel="stylesheet" href="assets/css/table.min.css">
  <link rel="stylesheet" href="assets/css/alert.min.css">
  <title>Customers</title>
</head>
<body>
  <?php
    if (isset($_SESSION['error'])) {
  ?>
  <div class="alert error">
    <span><?php echo $_SESSION['error'] ?></span>
    <span onclick="closeAlert(this)">&times;</span>
  </div>
  <?php
    unset($_SESSION['error']);
    }
  ?>

  <?php
    if (isset($_SESSION['success'])) {
  ?>
  <div class="alert success">
    <span><?php echo $_SESSION['success'] ?></span>
    <span onclick="closeAlert(this)">&times;</span>
  </div>
  <?php
    unset($_SESSION['success']);
    }
  ?>
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

        <a href="categories" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Categories
        </a>

        <a href="subcategories" class="navigation-item">
          <img src="assets/images/chess-pawn-solid.svg" alt="chess-pawn-solid Font Awesome icon">
          Subcategories
        </a>

        <a href="vendors" class="navigation-item">
          <img src="assets/images/people-carry-solid.svg" alt="people-carry-solid Font Awesome icon">
          Vendor/Supplier
        </a>

        <a href="customers" class="navigation-item active">
          <img src="assets/images/user-friends-solid.svg" alt="user-friends-solid Font Awesome icon">
          Customers
        </a>

        <a href="inventory" class="navigation-item">
          <img src="assets/images/money-check-alt-solid.svg" alt="money-check-alt-solid Font Awesome icon">
          Inventory
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Home / Categories
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newCustomer')">ADD A CUSTOMER</button>

        <div class="modal" id="newCustomer">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add a Customer</h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/customer/addCustomer" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                  <label for="customer_name">Customer Name</label>
                  <input type="text" required name="customer_name" placeholder="Customer name">

                  <label for="credit_limit">Credit Limit</label>
                  <input type="text" required name="credit_limit" placeholder="Credit limit">

                  <label for="contact_number">Contact Number</label>
                  <input type="tel" required name="contact_number" placeholder="Contact number">

                  <input type="submit" name="submit" value="Add Customer">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newCustomer')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getCustomersResponse['data'] as $singleCustomerInfo) {
        ?>
          <div class="modal" id="editCustomer<?php echo $singleCustomerInfo['customer_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Category: <?php echo $singleCustomerInfo['customer_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/customer/editCustomer" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <input type="hidden" name="customer_id" value="<?php echo $singleCustomerInfo['customer_id'] ?>">

                    <label for="customer_name">Customer Name</label>
                    <input type="text" required name="customer_name" placeholder="Customer name" value="<?php echo $singleCustomerInfo['customer_name'] ?>">

                    <label for="credit_limit">Credit Limit</label>
                    <input type="text" required name="credit_limit" placeholder="Credit limit" value="<?php echo $singleCustomerInfo['credit_limit'] ?>">

                    <label for="contact_number">Contact Number</label>
                    <input type="tel" required name="contact_number" placeholder="Contact number" value="<?php echo $singleCustomerInfo['contact_number'] ?>">


                    <input type="submit" name="submit" value="Submit Edits">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editCustomer<?php echo $singleCustomerInfo['customer_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deleteCustomer<?php echo $singleCustomerInfo['customer_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete customer: <?php echo $singleCustomerInfo['customer_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/customer/deleteCustomer" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this category?</p>
                    <input type="hidden" name="customer_id" value="<?php echo $singleCustomerInfo['customer_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deleteCustomer<?php echo $singleCustomerInfo['customer_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th colspan="5">
            <h2>Customers Present</h2>
          </th>
        </thead>
        <thead>
          <th>Customer Name</th>
          <th>Credit Limit</th>
          <th>Contact Number</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($getCustomersResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="5">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
        }else if($getCustomersResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="5">
                <h3>No customers present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getCustomersResponse['data'] as $singleCustomerInfo) {
            ?>
            <tr>
              <td><?php echo $singleCustomerInfo['customer_name'] ?></td>
              <td><?php echo $singleCustomerInfo['credit_limit'] ?></td>
              <td><?php echo $singleCustomerInfo['contact_number'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editCustomer<?php echo $singleCustomerInfo['customer_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deleteCustomer<?php echo $singleCustomerInfo['customer_id']; ?>')">Delete</button>
              </td>
            </tr>
            <?php
            }
          }
          ?>
        </tbody>
      </table>

    </div>

  </div>
  <script src="assets/js/app.min.js" charset="utf-8"></script>
</body>
</html>
