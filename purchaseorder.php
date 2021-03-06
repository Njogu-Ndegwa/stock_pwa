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
$getMaterialsResponse = $Material->getMaterials();
$getPurchaseOrderResponse = $PurchaseOrder->getPurchaseOrders();
$tabledata = [];
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
  <link rel="stylesheet" href="assets/css/dropdown.min.css">
  <link rel="stylesheet" href="assets/css/select2.min.css" />
  <link rel="stylesheet" href="assets/css/purchaseorder.css" />
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

        <form  class="" method="post" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchaseorder/filterData" >
        <?php
                      echo CSRF::createToken();
        ?>
          <label for="from_date"> From</label>
          <input type="date" name="from_date">

          <label for="to_date"> To</label>
          <input type="date" name="to_date">

          <label for="vendor_name"> Vendor Name</label>
          <select name="vendor_name" class="vendor-option">
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
          <input type="submit" name="Filter Data">
  </form>


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

                <div>
                <label for="vendor_name">Vendor Name</label>
                <div class="dropdown">
                      <a onclick="openDropdown(this)" class="dropbtn">+ Add a vendor</a>
                      <div class="dropdown-content">
                        <button type="button" onclick="closeDropdown(this)" name="button">Close&#10005;</button>
                        <input type="text" placeholder="Vendor name">
                        <input type="email" placeholder="Vendor email">
                        <input type="tel" placeholder="Vendor mobile">
                        <input type="text" placeholder="Vendor description">
                        <button type="button" name="button" onclick="addVendor(this)">
                          Add the Vendor
                        </button>
                      </div>
                    </div>
                <select name="vendor_name" class="vendor-option">
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
                  </div>
                      <div>
                <label for="record_date">Record Date</label>
                <input type="date" required name="record_date" placeholder="Record Date" value="">
                </div>
                      <div>
                <label for="due_date">Due Date</label>
                <input type="date" required name="due_date" placeholder="Due Date" value="">
                </div>
                      <div>
                <label for="quotation_reference">Quotation Reference</label>
                <input type="text" required name="quotation_reference" placeholder="Quotation Reference" value="">
                </div>
                <div>
                <label for="quotation_date">Quotation Date</label>
                <input type="date" required name="quotation_date" placeholder="Quotation Date" value="">
                </div>
                <div>
                <label for="amount">Total  Amount</label>
                <input type="number" id="total-amount" required name="amount" placeholder="Total Amount">
                </div>
                <div class="full-grid table">
            <table id="itemsTable">
              <thead>
                <th>No</th>
                <th>Item</th>
                <th>Description</th>
                <th>Qty</th>
                <th>KG</th>
                <th>Unit Cost</th>
                <th>Amount</th>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>
                  <select name="item_name[]" onchange="populateItemList(this)">
                    <?php
                      if ($getMaterialsResponse['response'] == '204') {
                      ?>
                        <option selected disabled value="">There are no materials present in the system</option>
                      <?php
                      }else {
                        ?>
                        <option selected disabled value="">Choose an item</option>
                      <?php
                        foreach ($getMaterialsResponse['data'] as $materialInfo) {
                          
                      ?>
                          <option data-description="<?php echo $materialInfo['description'] ?>" data-item-type="<?php echo $materialInfo['inventory_type']?>" data-unit-cost="<?php echo $materialInfo['unit_cost']?>" value="<?php echo $materialInfo['item_id'] ?>">
                            <?php echo $materialInfo['item_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>
                  </td>
                  <td><input type="text" name="item_description[]" value=""></td>
                  <td><input type="text" name="item_quantity[]" value="0"></td>
                  <td><input type="text" name="item_kg[]" value="0"></td>
                  <td><input type="text" name="item_unit_cost[]" value=""></td>
                  <td><input type="text" name="item_amount[]" value=""></td>
                </tr>
              </tbody>
            </table>
            <!-- <div> -->
            <button type="button" name="button" style="
    width: 100%; class="full-grid" onclick="addItemRow()">ADD ONE MORE ITEM</button>
            <!-- </div> -->
          </div>
                <div>
                <label for="status">Status</label>
                <input name="po_status" placeholder="Status" />
                </div>
                <div>
                <label for="document">Document</label>
                <input type="file" name="document" placeholder="Document" />
                </div>
                <div>
                <label for="memo">Memo</label>
                <input name="text" placeholder="memo" />
                </div>
                <div>
                <label for="terms_and_conditions">Terms and Conditions</label>
                <textarea name="terms_and_conditions" rows="3"></textarea>
                </div>

                  <input type="submit" class="full-grid" name="submit" value="Add Purchase Order">
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

                     <div>
                <label for="vendor_name">Vendor Name</label>
                <div class="dropdown">
                      <a onclick="openDropdown(this)" class="dropbtn">+ Add a vendor</a>
                      <div class="dropdown-content">
                        <button type="button" onclick="closeDropdown(this)" name="button">Close&#10005;</button>
                        <input type="text" placeholder="Vendor name">
                        <input type="email" placeholder="Vendor email">
                        <input type="tel" placeholder="Vendor mobile">
                        <input type="text" placeholder="Vendor description">
                        <button type="button" name="button" onclick="addVendor(this)">
                          Add the Vendor
                        </button>
                      </div>
                    </div>
                    <?php
                                          echo '<script>';
                                          echo 'console.log('. json_encode( $singlePurchaseOrderInfo ) .')';
                                          echo '</script>';
                                          ?>
                <select name="vendor_name" class="vendor-option">
                    <?php
                    foreach ($getVendorsResponse['data'] as $vendorInfo) {
                      // print_r($singlePurchaseOrderInfo['vendor_id']);
                      if ($singlePurchaseOrderInfo['vendor_name'] == $vendorInfo['vendor_id']) {
                        
                      ?>
                        <option selected disabled value=" <?php echo $vendorInfo['vendor_name']?>">
                          <?php echo $vendorInfo['vendor_name']?> (CHOSEN)
                        </option>
                      <?php
                      }else {
                      ?>
                          <option value="<?php echo $vendorInfo['vendor_id'] ?>">
                            <?php echo $vendorInfo['vendor_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>
                  </div>
                      <div>
                <label for="record_date">Record Date</label>
                <input type="date" required name="record_date" placeholder="Record Date" value="<?php echo $singlePurchaseOrderInfo['record_date'] ?>">
                </div>
                      <div>
                <label for="due_date">Due Date</label>
                <input type="date" required name="due_date" placeholder="Due Date" value="<?php echo $singlePurchaseOrderInfo['due_date'] ?>">
                </div>
                      <div>
                <label for="quotation_reference">Quotation Reference</label>
                <input type="text" required name="quotation_reference" placeholder="Quotation Reference" value="<?php echo $singlePurchaseOrderInfo['quotation_reference'] ?>">
                </div>
                <div>
                <label for="quotation_date">Quotation Date</label>
                <input type="date" required name="quotation_date" placeholder="Quotation Date" value="<?php echo $singlePurchaseOrderInfo['quotation_date'] ?>">
                </div>
                <div>
                <label for="amount">Total  Amount</label>
                <input type="number" id="total-amount" required name="amount" placeholder="Total Amount" value="<?php echo $singlePurchaseOrderInfo['amount'] ?>">
                </div>
                <div class="full-grid table">
            <table id="itemsTable">
              <thead>
                <th>No</th>
                <th>Item</th>
                <th>Description</th>
                <th>Qty</th>
                <th>KG</th>
                <th>Unit Cost</th>
                <th>Amount</th>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>
                  <select name="item_name[]" onchange="populateItemList(this)">
                    <?php
                      if ($getMaterialsResponse['response'] == '204') {
                      ?>
                        <option selected disabled value="">There are no materials present in the system</option>
                      <?php
                      }else {
                        ?>
                        <option selected disabled value="">Choose an item</option>
                      <?php
                        foreach ($getMaterialsResponse['data'] as $materialInfo) {
                          
                      ?>
                          <option data-description="<?php echo $materialInfo['description'] ?>" data-item-type="<?php echo $materialInfo['inventory_type']?>" data-unit-cost="<?php echo $materialInfo['unit_cost']?>" value="<?php echo $materialInfo['item_id'] ?>">
                            <?php echo $materialInfo['item_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>
                  </td>
                  <td><input type="text" name="item_description[]" value=""></td>
                  <td><input type="text" name="item_quantity[]" value="0"></td>
                  <td><input type="text" name="item_kg[]" value="0"></td>
                  <td><input type="text" name="item_unit_cost[]" value=""></td>
                  <td><input type="text" name="item_amount[]" value=""></td>
                </tr>
              </tbody>
            </table>
            <!-- <div> -->
            <button type="button" name="button" style="
    width: 100%; class="full-grid" onclick="addItemRow()">ADD ONE MORE ITEM</button>
            <!-- </div> -->
          </div>
                <div>
                <label for="status">Status</label>
                <input name="po_status" placeholder="Status" value="<?php echo $singlePurchaseOrderInfo['po_status'] ?>" />
                </div>
                <div>
                <label for="document">Document</label>
                <input type="file" name="document" placeholder="Document" value="<?php echo $singlePurchaseOrderInfo['document'] ?>" />
                </div>
                <div>
                <label for="memo">Memo</label>
                <input type="text" name="memo" placeholder="memo" value="<?php echo $singlePurchaseOrderInfo['memo'] ?>"/>
                </div>
                <div>
                <label for="terms_and_conditions">Terms and Conditions</label>
                <textarea name="terms_and_conditions" rows="3"><?php echo $singlePurchaseOrderInfo['terms_and_conditions'] ?></textarea>
                </div>

                  <input type="submit" class="full-grid" name="submit" value="Submit Purchase Order">
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
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchaseorder/generatepdf" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this purchase order?</p>
                    <input type="hidden" name="purchase_order_id" value="<?php echo $singlePurchaseOrderInfo['purchase_order_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deletePurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>')" name="button">No &times;</button>
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
            if ($getPurchaseOrderResponse['response'] == '500' ) {
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
            $tabledata =  $getPurchaseOrderResponse['data'];
        
            foreach ( $tabledata as $singlePurchaseOrderInfo) {
            ?>
            <tr>
            <td><?php echo $singlePurchaseOrderInfo['created_at'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['vendor_name'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['due_date'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['amount'] ?></td>
              <td><?php echo $singlePurchaseOrderInfo['po_status'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editPurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>')">Edit</button>
                <!-- <button class="action-delete-btn" onclick="openModal('#deletePurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>')">Delete</button> -->
                <div class="dropdown"><button class=" dropbtn action-delete-btn">More</button><div class="dropdown-content"><a href="#" onclick="openModal('#deletePurchaseOrder<?php echo $singlePurchaseOrderInfo['purchase_order_id']; ?>')">Delete</a> 
                <form  method="post" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/purchaseorder/addPurchaseOrde" ><input type="submit" name="Export as pdf"/></form></div></div>
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
  <script src="assets/js/app.js" charset="utf-8"></script>
  <script type="text/javascript" src="assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/select2.min.js"></script>
  <script>
  $(document).ready(function(){
    $(function () {
        $("select").select2();
      });
  })
  </script>
</body>
</html>
