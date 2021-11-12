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

use app\Payment;

use app\Vendor;

$Payment = new Payment();

$getPaymentResponse = $Payment->getPayment();
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
  <title>Payments</title>
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

        <a href="customers" class="navigation-item">
          <img src="assets/images/user-friends-solid.svg" alt="user-friends-solid Font Awesome icon">
          Customers
        </a>

        <a href="inventory" class="navigation-item">
          <img src="assets/images/money-check-alt-solid.svg" alt="money-check-alt-solid Font Awesome icon">
          Inventory
        </a>

        <a href="purchase" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Purchases
        </a>

        <a href="expense" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Expenses
        </a>

        <a href="payments" class="navigation-item active">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Payments
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Home / Payments
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newPayment')">ADD A PAYMENT</button>

        <div class="modal" id="newPayment">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add a Payment </h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/payments/addPayment" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                <input type="hidden" name="payment_id" value="">

                <label for="voucher_number">Voucher Number</label>
                <input type="text" required name="voucher_number" placeholder="Voucher Number" value="">

                <label for="payment_date">Payment Date</label>
                <input type="date" required name="payment_date" placeholder="Payment Date" value="">

                <label for="payment_from">Pay From</label>
                <select name="payment_from" onchange="changeUnit(this)">
                <option disabled >There are no acccounts present in the system...</option>
                <option>Cash</option>
                  </select>

                <label for="payment_to">Pay To</label>
                <select name="payment_to" onchange="changeUnit(this)">
                <option disabled >There are no acccounts present in the system...</option>
                <option >Vendor A</option>
                  </select>

                <label for="payment_amount">Payment Amount</label>
                <input type="number" required name="payment_amount" placeholder="Payment Amount" value="">

                <label for="tds_deducted">Tds Deducted</label>
                <input type="number" required name="tds_deducted" placeholder="Tds Deducted" value="">

                <label for="payment_mode">Payment Mode</label>
                <select name="payment_mode" onchange="changeUnit(this)">
                <option>Bank</option>
                <option>Cash</option>
                  </select>

                <label for="payment_description">Description</label>
                <textarea name="payment_description" rows="3"></textarea> 


                  <input type="submit" name="submit" value="Add Payment">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newPayment')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getPaymentResponse['data'] as $singlePaymentInfo) {
        ?>
          <div class="modal" id="editPayment<?php echo $singlePaymentInfo['payment_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Payment: <?php echo $singlePaymentInfo['payment_id'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/payments/editPayment" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                                    <input type="hidden" name="payment_id" value="">

                  <label for="voucher_number">Voucher Number</label>
                  <input type="text" required name="voucher_number" placeholder="Voucher Number" value="">

                  <label for="payment_date">Payment Date</label>
                  <input type="text" required name="payment_date" placeholder="Payment Date" value="">

                  <label for="pay_from">Pay From</label>
                  <input type="text" required name="payment_to" placeholder="Pay From" value="">

                  <label for="payment_to">Pay To</label>
                  <input type="text" required name="payment_to" placeholder="Pay To" value="">

                  <label for="payment_amount">Payment Amount</label>
                  <input type="number" required name="payment_amount" placeholder="Payment Amount" value="">

                  <label for="tds_deducted">Tds Deducted</label>
                  <input type="number" required name="tds_deducted" placeholder="Tds Deducted" value="">

                  <label for="payment_mode">Payment Mode</label>
                  <input type="text" name="payment_mode" placeholder="Payment Mode" value="">

                  <label for="payment_description">Description</label>
                  <textarea name="narration" rows="3"></textarea> 

                    <input type="submit" name="submit" value="Submit Edits Payment">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editPayment<?php echo $singlePaymentInfo['payment_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deletePayment<?php echo $singlePaymentInfo['payment_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete Payment: <?php echo $singlePaymentInfo['payment_id'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/payments/deletePayment" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this payment?</p>
                    <input type="hidden" name="payment_id" value="<?php echo $singlePaymentInfo['payment_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deletePayment<?php echo $singlePaymentInfo['payment_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th colspan="7">
            <h2>Payments Present</h2>
          </th>
        </thead>
        <thead>
        <th>Vocher Number</th>
          <th>Payment Date</th>
          <th>Payment To</th>
          <th>Amount</th>
          <th>Payment Mode</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php
            if ($getPaymentResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="7">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getPaymentResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="7">
                <h3>No payments present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getPaymentResponse['data'] as $singlePaymetnInfo) {
            ?>
            <tr>
              <td><?php echo $singlePaymentInfo['voucher_number'] ?></td>
              <td><?php echo $singlePaymentInfo['payment_date'] ?></td>
              <td><?php echo $singlePaymentInfo['payment_to'] ?></td>
              <td><?php echo $singlePaymentInfo['payment_amount'] ?></td>
              <td><?php echo $singlePaymentInfo['payment_mode'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editPayment<?php echo $singlePaymentInfo['payment_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deletePayment<?php echo $singlePaymentInfo['payment_id']; ?>')">Delete</button>
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
