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

use app\Expense;

$Expense = new Expense();

$getExpenseResponse = $Expense->getExpenses();
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
  <title>Expenses</title>
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

        <a href="expense" class="navigation-item active">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Expenses
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Home / Expenses
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newExpense')">ADD A EXPENSE</button>

        <div class="modal" id="newExpense">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add an Expense </h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/expenses/addExpense" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                <input type="hidden" name="expense_id" value="">

                <label for="voucher_number">Voucher Number</label>
                <input type="text" required name="voucher_number" placeholder="Voucher number" value="">

                <label for="paid_from">Paid From</label>
                <select name="paid_from" onchange="changeUnit(this)">
                <option disabled >There are no acccounts present in the system...</option>
                <option>Cash</option>
                  </select>

                <label for="expense_date">Expense Record Date</label>
                <input type="date" required name="expense_date" placeholder="Expense Record Date" value="">

                <label for="expense_type">Type of expense</label>
                <select name="expense_type" onchange="changeUnit(this)">
                    <option value="Cash Expense">Cash Expense</option>
                    <option value="Credit Expense">Credit Expense</option>
                  </select>

                <label for="expense_description">Description</label>
                <textarea name="expense_description" rows="3"></textarea>

                <label for="tax">Tax</label>
                <input type="number" required name="tax" placeholder="Tax" value="">

                <label for="expense_amount">Expense Amount</label>
                <input type="number" required name="expense_amount" placeholder="Amount" value="">

                <label for="expense_status">Expense Status</label>
                <input type="text" required name="expense_status" placeholder="Expense Status" value="">

                <label for="document">Document</label>
                <input type="file"  name="document" placeholder="document" value="">

                <label for="memo">Memo</label>
                <input type="text" name="memo" placeholder="Memo" value="">


                <label for="narration">Narration</label>
                <textarea name="narration" rows="3"></textarea>

                  <input type="submit" name="submit" value="Add Expense">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newExpense')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getExpenseResponse['data'] as $singleExpenseInfo) {
        ?>
          <div class="modal" id="editExpense<?php echo $singleExpenseInfo['expense_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Expense: <?php echo $singleExpenseInfo['expense_id'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/expenses/editExpense" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <input type="hidden" name="expense_id" value="">

                    <label for="voucher_name">Voucher Name</label>
                    <input type="text" required name="voucher_name" placeholder="Vendor name" value="">

                    <label for="account">Paid from Account</label>
                    <input type="text" required name="account" placeholder="Account" value="">

                    <label for="expense_date">Expense Record Date</label>
                    <input type="date" required name="expense_date" placeholder="Expense Record Date" value="">

                    <label for="type_of_expense">Type of expense</label>
                    <input type="text" required name="type_of_expense" placeholder="Type of Expense" value="">

                    <label for="description">Description</label>
                    <input type="text" required name="description" placeholder="Description" value="">

                    <label for="tax">Tax</label>
                    <input type="text" required name="tax" placeholder="Tax" value="">

                    <label for="expense_amount">Amount</label>
                    <input type="number" required name="expense_amount" placeholder="Amount" value="">

                    <label for="document">Document</label>
                    <input type="text" required name="document" placeholder="document" value="">

                    <label for="memo">Memo</label>
                    <input type="text" required name="memo" placeholder="Memo" value="">


                    <label for="narration">Narration</label>
                    <textarea name="narration" rows="3"></textarea>



                    <input type="submit" name="submit" value="Submit Edits Expense">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editExpense<?php echo $singleExpenseInfo['expense_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deleteExpense<?php echo $singleExpenseInfo['expense_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete Expense: <?php echo $singleExpenseInfo['expense_id'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/expenses/deleteExpense" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this expense?</p>
                    <input type="hidden" name="expense_id" value="<?php echo $singleExpenseInfo['expense_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deleteExpense<?php echo $singleExpenseInfo['expense_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th colspan="7">
            <h2>Expenses Present</h2>
          </th>
        </thead>
        <thead>
            <th>Date</th>
          <th>Vocher Number</th>
          <th>Expense Date</th>
          <th>Paid From</th>
          <th>Expense Amount</th>
          <th>Created by</th>
          <th>Status</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php
            if ($getExpenseResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="7">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getExpenseResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="7">
                <h3>No expenses present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getExpenseResponse['data'] as $singleExpenseInfo) {
            ?>
            <tr>
              <td><?php echo $singleExpenseInfo['created_at'] ?></td>
              <td><?php echo $singleExpenseInfo['voucher_number'] ?></td>
              <td><?php echo $singleExpenseInfo['expense_date'] ?></td>
              <td><?php echo $singleExpenseInfo['paid_from'] ?></td>
              <td><?php echo $singleExpenseInfo['expense_amount'] ?></td>
              <td><?php echo $singleExpenseInfo['created_by'] ?></td>
              <td><?php echo $singleExpenseInfo['expense_status'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editExpense<?php echo $singleExpenseInfo['expense_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deleteExpense<?php echo $singleExpenseInfo['expense_id']; ?>')">Delete</button>
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
