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

$coatingNumber = $Customer->generateToken(5, 1, 'numbers')[0];
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
  <link rel="stylesheet" href="assets/css/powdercoating.min.css">
  <title>Powder Coating</title>
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

        <a href="powdercoating" class="navigation-item active">
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

        <a href="customers" class="navigation-item">
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
          Home / Powder Coating
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

        <h3>Coating Job</h3>
        <form class="glassmorphic" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/inventory/addCoatingJob" method="post">
          <?php
              echo CSRF::createToken();
          ?>

          <div class="input-wrapper">
            <label for="customer_name">Customer</label>
            <select name="customer_name">
              <?php
                if ($getCustomersResponse['response'] == '204') {
              ?>
                  <option selected disabled>There are no customers present</option>
              <?php
                }else if($getCustomersResponse['response'] == '500') {
              ?>
                  <option selected disabled>There has been an error fetching the customers</option>
              <?php
                }else {
                  foreach ($getCustomersResponse['data'] as $singleCustomerInfo) {
                    ?>
                      <option value="<?php echo $singleCustomerInfo['customer_id'] ?>">
                        <?php echo $singleCustomerInfo['customer_name'] ?>
                      </option>
                    <?php
                  }
                }
              ?>
            </select>
          </div>

          <div class="input-wrapper">
            <label for="coating_job_no">Coating Job Number</label>
            <input type="text" readonly name="coating_job_no" value="<?php echo $coatingNumber ?>">
          </div>

          <div class="input-wrapper">
            <label for="quotation_no">Quotation Number</label>
            <input type="text" readonly name="quotation_no" value="<?php echo strrev($coatingNumber) ?>">
          </div>

          <div class="input-wrapper">
            <label for="lpo_no">LPO Number</label>
            <input type="text" name="lpo_no">
          </div>

          <div class="input-wrapper">
            <label for="delivery_number">Delivery Number</label>
            <input type="text" name="delivery_number">
          </div>

          <div class="input-wrapper">
            <label for="date">Date</label>
            <input type="date" name="date">
          </div>

          <div class="input-wrapper">
            <label for="material">Material</label>
            <select name="material">
              <option value="Aluminium">Aluminium</option>
              <option value="Steel">Steel</option>
            </select>
          </div>

          <div class="input-wrapper">
            <label for="">Goods in weight</label>
            <input type="number" min="0" name="weight" id="weightInput" onkeyup="calculateEstimate()" value="0">
          </div>

          <div class="input-wrapper">
            <label for="">Powder Profile Type</label>
            <select id="profileType" onchange="calculateEstimate()" class="" name="profile_type">
              <option value="Heavy">Heavy</option>
              <option value="Medium">Medium</option>
              <option value="Light">Light</option>
            </select>
          </div>

          <div class="input-wrapper">
            <label for="powder_estimate">Powder estimate</label>
            <input type="text" readonly value="0" id="powderEstimate" name="powder_estimate">
          </div>

          <div class="input-wrapper">
            <label for="powder_used">Actual powder used</label>
            <input type="text" name="powder_used">
          </div>

          <div class="input-wrapper">
            <label for="ral">RAL</label>
            <input type="text" name="ral">
          </div>

          <div class="input-wrapper">
            <label for="color">Color</label>
            <input type="text" name="color">
          </div>

          <div class="input-wrapper">
            <label for="code">Code</label>
            <input type="text" name="code">
          </div>

          <div class="input-wrapper">
            <label for="">In Date</label>
            <input type="date" name="in_date">
          </div>

          <div class="input-wrapper">
            <label for="">Ready date</label>
            <input type="date" name="ready_date">
          </div>

          <div class="input-wrapper">
            <label for="">Out date</label>
            <input type="date" name="out_date">
          </div>

          <div class="input-wrapper" style="text-align:left">
            <label>Belongs to:</label>
            <div>
              <input type="radio" name="owner" value="clients"><label for="owner">Owners/Clients</label>
            </div>
            <div>
              <input type="radio" name="owner" value="maruti"><label for="owner">Maruti</label>
            </div>
          </div>

          <div class="input-wrapper table">
            <table id="itemsTable">
              <thead>
                <th>No</th>
                <th>Code</th>
                <th>Description</th>
                <th>Qty</th>
                <th>KG</th>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td><input type="text" name="item_code[]" value=""></td>
                  <td><input type="text" name="item_description[]" value=""></td>
                  <td><input type="text" name="item_quantity[]" value=""></td>
                  <td><input type="text" name="item_kg[]" value=""></td>
                </tr>
              </tbody>
            </table>
            <button type="button" name="button" onclick="addItemRow()">ADD ONE MORE ITEM</button>
          </div>



          <div class="input-wrapper">
            <div class="">
              <label for="prepared_by">Prepared By:</label>
              <input type="text" name="prepared_by">
            </div>

            <div class="">
              <label for="approved_by">Approved By:</label>
              <input type="text" name="approved_by">
            </div>

            <div class="">
              <label for="supervisor">Supervisor:</label>
              <input type="text" name="supervisor">
            </div>

            <div class="">
              <label for="quality_by">Quality By:</label>
              <input type="text" name="quality_by">
            </div>
          </div>

          <div class="full-grid">
            <div class="input-wrapper">
              <button class="upload-btn" type="submit" value="upload" name="button">
                SAVE AND UPLOAD
              </button>
            </div>

            <div class="input-wrapper">
              <button class="quotation-btn" type="submit" value="create" name="button">
                SAVE AND CREATE QUOTATION
              </button>
            </div>

            <div class="input-wrapper">
              <button class="print-btn" type="submit" value="print" name="button">
                SAVE AND PRINT
              </button>
            </div>

            <div class="input-wrapper">
              <button class="send-btn" type="submit" value="send" name="button">
                SAVE AND SEND
              </button>
            </div>
          </div>

        </form>

    </div>

  </div>
  <script src="assets/js/app.min.js" charset="utf-8"></script>
</body>
</html>
