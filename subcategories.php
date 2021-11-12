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

use app\Category;

$Category = new Category();

$getCategoriesResponse = $Category->getCategories();

use app\Subcategory;

$Subcategory = new Subcategory();

$getSubcategoriesResponse = $Subcategory->getSubcategories();
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
  <title>Subcategories</title>
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

        <a href="subcategories" class="navigation-item active">
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newSubcategory')">ADD A SUBCATEGORY</button>

        <div class="modal" id="newSubcategory">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add a Subcategory</h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/subcategory/addSubcategory" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                  <label for="subcategory_name">Subcategory Name</label>
                  <input type="text" required name="subcategory_name" placeholder="Subcategory name">

                  <label for="subcategory_description">Subcategory Description</label>
                  <textarea required name="subcategory_description" placeholder="Subcategory description"></textarea>

                  <label for="subcategory_status">Subcategory Status</label>
                  <input type="text" required name="subcategory_status" placeholder="Subcategory status">

                  <label for="category_id">Select a category to link to</label>
                  <select name="category_id">
                    <?php
                      if (count($getCategoriesResponse['data'])<1) {
                        ?>
                          <option disabled selected value="">
                            No categories to pick from
                          </option>
                        <?php
                      }else {
                        foreach ($getCategoriesResponse['data'] as $singleCategoryInfo) {
                      ?>
                          <option value="<?php echo $singleCategoryInfo['category_id'] ?>">
                            <?php echo $singleCategoryInfo['category_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>

                  <input type="submit" name="submit" value="Add Subcategory">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newSubcategory')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getSubcategoriesResponse['data'] as $singleSubcategoryInfo) {
        ?>
          <div class="modal" id="editSubcategory<?php echo $singleSubcategoryInfo['subcategory_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Subcategory: <?php echo $singleSubcategoryInfo['subcategory_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/subcategory/editSubcategory" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <input type="hidden" name="subcategory_id" value="<?php echo $singleSubcategoryInfo['subcategory_id'] ?>">

                    <label for="location_name">Subcategory Name</label>
                    <input type="text" required name="subcategory_name" placeholder="Subcategory name" value="<?php echo $singleSubcategoryInfo['subcategory_name'] ?>">

                    <label for="subcategory_description">Subcategory Description</label>
                    <textarea required name="subcategory_description" placeholder="Subcategory description"><?php echo $singleSubcategoryInfo['subcategory_description'] ?></textarea>

                    <label for="subcategory_status">Subcategory Status</label>
                    <input type="text" required name="subcategory_status" placeholder="Subcategory status" value="<?php echo $singleSubcategoryInfo['subcategory_status'] ?>">

                    <label for="category_id">Choose Category</label>
                    <select name="category_id">
                        <?php
                          foreach ($getCategoriesResponse['data'] as $singleCategoryInfo) {
                            if ( $singleCategoryInfo['category_id'] == $singleSubcategoryInfo['category_id'] ) {
                              ?>
                                  <option selected value="<?php echo $singleCategoryInfo['category_id'] ?>">
                                    <?php echo $singleCategoryInfo['category_name']; ?>(CHOSEN)
                                  </option>
                              <?php
                              } else {
                              ?>
                                  <option value="<?php echo $singleCategoryInfo['category_id'] ?>">
                                    <?php echo $singleCategoryInfo['category_name']; ?>
                                  </option>
                              <?php
                            }
                          }
                        ?>
                    </select>


                    <input type="submit" name="submit" value="Submit Edits Category">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editSubcategory<?php echo $singleSubcategoryInfo['subcategory_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deleteSubcategory<?php echo $singleSubcategoryInfo['subcategory_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete subcategory: <?php echo $singleSubcategoryInfo['category_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/subcategory/deleteSubcategory" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this category?</p>
                    <input type="hidden" name="subcategory_id" value="<?php echo $singleSubcategoryInfo['subcategory_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deleteSubcategory<?php echo $singleSubcategoryInfo['subcategory_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th colspan="4">
            <h2>Subcategories Present</h2>
          </th>
        </thead>
        <thead>
          <th>Subcategory Name</th>
          <th>Category Name</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($getSubcategoriesResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="4">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getSubcategoriesResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="4">
                <h3>No subcategories present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getSubcategoriesResponse['data'] as $singleSubcategoryInfo) {
            ?>
            <tr>
              <td><?php echo $singleSubcategoryInfo['subcategory_name'] ?></td>
              <td><?php echo $singleSubcategoryInfo['category_name'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editSubcategory<?php echo $singleSubcategoryInfo['subcategory_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deleteSubcategory<?php echo $singleSubcategoryInfo['subcategory_id']; ?>')">Delete</button>
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
