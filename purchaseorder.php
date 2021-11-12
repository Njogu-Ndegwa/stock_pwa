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

use app\PurchaseOrder;

use app\Vendor;

use app\Material;

$PurchaseOrder = new PurchaseOrder();
$Vendor = new Vendor();
$Material = new Material();

$getVendorsResponse = $Vendor->getVendors();
$getMaterialResponse = $Material->getMaterials();

$getPurchaseOrderResponse = $PurchaseOrder->getPurchaseOrders();
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
  <title>Purchase Order</title>
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

        <a href="purchaseorder" class="navigation-item active">
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
          Home / Puchase Order
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newPurchaseOrder')">ADD A PURCHASE ORDER</button>

        <div class="modal" id="newPurchaseOrder">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add a Purchase Order </h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchaseorder/addPurchaseOrder" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                <input type="hidden" name="purchase_order_id" value="">

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
                <textarea name="item_description" rows="3"></textarea>

                <label for="unit_cost">Unit Cost</label>
                <input type="number" required name="unit_cost" placeholder="Unit Cost" value="">

                <label for="qty">Qty</label>
                <input type="number" required name="qty" placeholder="Qty" value="">

                <label for="amount">Amount</label>
                <input type="number" required name="amount" placeholder="Amount" value="">


                <label for="status">Status</label>
                <input name="po_status" placeholder="Status" />

                <label for="document">Document</label>
                <input type="file" name="document" placeholder="Document" />

                <label for="memo">Memo</label>
                <input name="text" placeholder="memo" />

                <label for="terms_and_conditions">Terms and Conditions</label>
                <textarea name="terms_and_conditions" rows="3"></textarea>

                  <input type="submit" name="submit" value="Add Purchase Order">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newPurchaseOrder')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getPurchaseOrderResponse['data'] as $singlePurchaseOrderInfo) {
        ?>
          <div class="modal" id="editPurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Purchase Order: <?php echo $singlePurchaseOrderInfo['purchase_order_id'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchaseorder/editPurchaseOrder" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                     <input type="hidden" name="purchase_order_id" value="">

                <label for="voucher_number">Voucher Number</label>
                <input type="text" required name="voucher_number" placeholder="Voucher Number" value="">

                <label for="record_date">Record Date</label>
                <input type="date" required name="record_date" placeholder="Record Date" value="">

                <label for="due_date">Due Date</label>
                <input type="date" required name="due_date" placeholder="Due Date" value="">

                <label for="vendor_name">Vendor Name</label>
                <input type="text" required name="vendor_name" placeholder="Vendor Name" value="">

                <label for="under_project">Under Project</label>
                <input type="text" required name="under_project" placeholder="Under Project" value="">

                <label for="item">Item</label>
                <input type="text" required name="item" placeholder="Item" value="">

                <label for="description">Description</label>
                <input type="text" required name="description" placeholder="Description" value="">

                <label for="qty">Qty</label>
                <input type="number" required name="description" placeholder="Qty" value="">

                <label for="unit_cost">Unit Cost</label>
                <input type="number" required name="unit_cost" placeholder="Unit Cost" value="">

                <label for="amount">Amount</label>
                <input type="number" required name="amount" placeholder="Amount" value="">


                <label for="document">Document</label>
                <input type="txt" required name="document" placeholder="Document" value="">

                <label for="memo">Memo</label>
                <input type="txt" required name="memo" placeholder="" value="">

                <label for="narration">Narration</label>
                <textarea name="customer_notes" rows="3"></textarea>

                <label for="terms_and_conditions">Terms and Conditions</label>
                <textarea name="terms_and_conditions" rows="3"></textarea>

                    <input type="submit" name="submit" value="Submit Edits Purchase Order">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editPurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deletePurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete Purchase Order: <?php echo $singlePurchaseOrderInfo['purchase_order_id'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchaseorder/deletePurchaseOrder" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this purchase order?</p>
                    <input type="hidden" name="purchase_order_id" value="<?php echo $singlePurchaseOrderInfo['purchase_order_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deletePurchaseOrder<?php echo $singlePurchaseInfo['purchase_order_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th colspan="7">
            <h2>Purchase Orders Present</h2>
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
            if ($getPurchaseOrderResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="7">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getPurchaseOrderResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="7">
                <h3>No categories present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getPurchaseOrderResponse['data'] as $singlePurchaseOrderInfo) {
            ?>
            <tr>
            <td><?php echo $singlePurchaseOrderInfo['created_at'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['vendor_name'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['due_date'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['amount'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['po_status'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editPurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deletePurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>')">Delete</button>
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
