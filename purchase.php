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

use app\Purchase;

use app\Vendor;

use app\Material;

$Purchase = new Purchase();
$Vendor = new Vendor();
$Material = new Material();

$getVendorsResponse = $Vendor->getVendors();
$getMaterialResponse = $Material->getMaterials();

$getPurchaseResponse = $Purchase->getPurchases();
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
  <title>Purchases</title>
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

        <a href="purchase" class="navigation-item active">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Purchases
        </a>

        <a href="purchaseorder" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Purchase Order
        </a>
        
        <a href="expense" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Expenses
        </a>
      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Home / Purchases
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newPurchase')">ADD A PURCHASE</button>

        <div class="modal" id="newPurchase">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add a Purchase </h2>
              </div>
              <div class="modal-body">
                <form class=""  action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchases/addPurchase" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                <input type="hidden" name="purchase_id" value="">

                <label for="vendor_name">Vendor Name</label>
                <select name="vendor_name">
                    <?php
                      if ($getVendorsResponse['response'] == '204') {
                      ?>
                        <option selected disabled value="">There are no vendors present in the system</option>
                      <?php
                      }else {
                        foreach ($getVendorsResponse['data'] as $vendorInfo) {
                      ?>
                          <option value="<?php echo $vendorInfo['vendor_id'] ?>">
                            <?php echo $vendorInfo['vendor_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>

                <label for="record_date">Record Date</label>
                <input type="date" required name="record_date" placeholder="Record Date" value="">

                <label for="due_date">Due Date</label>
                <input type="date" required name="due_date" placeholder="Due Date" value="">

                <label for="quotation_reference">Quotation Reference</label>
                <input type="text" required name="quotation_reference" placeholder="Quotation Reference" value="">

                <label for="quotation_date">Quotation Date</label>
                <input type="date" required name="quotation_date" placeholder="Quotation Date" value="">

                <label for="item">Item</label>
                <select name="item">
                    <?php
                      if ($getMaterialResponse['response'] == '204') {
                      ?>
                        <option selected disabled value="">There are no materials present in the system</option>
                      <?php
                      }else {
                        foreach ($getMaterialResponse['data'] as $materialInfo) {
                      ?>
                          <option value="<?php echo $materialInfo['material_id'] ?>">
                            <?php echo $materialInfo['item_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>

                <label for="description">Description</label>
                <input type="text" required name="purchase_description" placeholder="Description" value="">

                <label for="unit_cost">Unit Cost</label>
                <input type="number" required name="unit_cost" placeholder="Unit Cost" value="">

                <label for="qty">Qty</label>
                <input type="number" required name="qty" placeholder="Qty" value="">

                <label for="amount">Amount</label>
                <input type="number" required name="amount" placeholder="Amount" value="">

                <label for="document">Document</label>
                <input type="file" name="document" placeholder="Document" />

                <label for="memo">Memo</label>
                <input name="text" placeholder="memo" />

                <label for="status">Status</label>
                <input name="purchase_status" placeholder="Status" />

                <label for="terms_and_conditions">Terms and Conditions</label>
                <textarea name="terms_and_conditions" rows="3"></textarea>

                  <input type="submit" name="submit" value="Add Purchase">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newPurchase')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getPurchaseResponse['data'] as $singlePurchaseInfo) {
        ?>
          <div class="modal" id="editPurchase<?php echo $singlePurchaseInfo['purchase_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Purchase: <?php echo $singlePurchaseInfo['vendor_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchases/editPurchase" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <input type="hidden" name="purchase_id" value="<?php echo $singlePurchaseInfo['purchase_id'] ?>">

                    <label for="vendor_name">Vendor Name</label>
                    <input type="text" required name="vendor_name" placeholder="Vendor name" value="<?php echo $singlePurchaseInfo['vendor_name'] ?>">

                    <label for="record_date">Record Date</label>
                    <input type="text" required name="record_date" placeholder="Record Date" value="<?php echo $singlePurchaseInfo['record_date'] ?>">

                    <label for="due_date">Due Date</label>
                    <input type="date" required name="due_date" placeholder="Due Date" value="<?php echo $singlePurchaseInfo['due_date'] ?>">

                    <label for="quotation_reference">Quotation Reference</label>
                    <input type="text" required name="quotation_reference" placeholder="Quotation Reference" value="<?php echo $singlePurchaseInfo['quotation_reference'] ?>">

                    <label for="quotation_date">Quotation Date</label>
                    <input type="text" required name="quotation_date" placeholder="Quotation Date" value="<?php echo $singlePurchaseInfo['quotation_date'] ?>">

                    <label for="item">Item</label>
                    <input type="text" required name="item" placeholder="Item" value="<?php echo $singlePurchaseInfo['item'] ?>">

                    <label for="purchase_status">Purchase Status</label>
                    <input type="text" required name="purchase_status" placeholder="Purchase status" value="<?php echo $singlePurchaseInfo['purchase_status'] ?>">



                    <input type="submit" name="submit" value="Submit Edits Purchase">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editPurchase<?php echo $singlePurchaseInfo['purchase_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deletePurchase<?php echo $singlePurchaseInfo['purchase_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete Purchase: <?php echo $singlePurchaseInfo['vendor_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchases/deletePurchase" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this purchase?</p>
                    <input type="hidden" name="purchase_id" value="<?php echo $singlePurchaseInfo['purchase_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deletePurchase<?php echo $singlePurchaseInfo['purchase_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th colspan="7">
            <h2>Purchases Present</h2>
          </th>
        </thead>
        <thead>
            <th>Date</th>
          <th>Vendor Name</th>
          <th>Due Date</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($getPurchaseResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="7">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getPurchaseResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="7">
                <h3>No categories present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getPurchaseResponse['data'] as $singlePurchaseInfo) {
            ?>
            <tr>
              <td><?php echo $singlePurchaseInfo['created_at'] ?></td>
              <td><?php echo $singlePurchaseInfo['vendor_name'] ?></td>
              <td><?php echo $singlePurchaseInfo['due_date'] ?></td>
              <td><?php echo $singlePurchaseInfo['amount'] ?></td>
              <td><?php echo $singlePurchaseInfo['purchase_status'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editPurchase<?php echo $singlePurchaseInfo['purchase_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deletePurchase<?php echo $singlePurchaseInfo['purchase_id']; ?>')">Delete</button>
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
