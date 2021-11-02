<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION['auth_token']) || empty($_SESSION['auth_uid']) || empty($_SESSION['auth_uname'])) {
  header("HTTP/1.1 403 Forbidden");

  $forbiddenPage = file_get_contents('../403.php');

  exit($forbiddenPage);
}

require_once '../app/vendor/autoload.php';

use app\CSRF;

use app\Company;

$Company = new Company();

$companiesPresentResponse = $Company->getCompanies();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../assets/css/dashboard.min.css">
  <link rel="stylesheet" href="../assets/css/casual.min.css">
  <link rel="stylesheet" href="../assets/css/table.min.css">
  <link rel="stylesheet" href="../assets/css/alert.min.css">
  <title>Super User - Dashboard</title>
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
        <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        Logo
      </div>

      <div class="navigation-items-container">
        <a href="dashboard" class="navigation-item active">
          <img src="../assets/images/home-solid.svg" alt="home-solid Font Awesome icon">
          Dashboard
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Super User / Home
        </div>

        <div class="options">
          <input type="search" name="" value="">
          <img src="../assets/images/user-solid.svg" alt="user-solid font awesome icon">
            <span>
              <?php echo $_SESSION['auth_uname'] ?>
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
            <span>Not Subscribed</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Active</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Future</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Past Due</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Unpaid</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Incomplete</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Cancelled</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

        <div class="widget glassmorphic">
          <div class="text">
            <span>Incomplete Expired</span>
            <span>00</span>
          </div>
          <img src="../assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        </div>

      </div>

      <div class="content">
        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newSubscription')">CREATE NEW SUBSCRIPTION</button>

        <div class="modal" id="newSubscription">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Create a new subscription</h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/createCompany" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                  <label for="company_email">Company Email</label>
                  <input type="email" required name="company_email" placeholder="Company Email">

                  <label for="company_email">Company Name</label>
                  <input type="text" required name="company_name" placeholder="Company Email">

                  <label for="keyInput">Activation Key(Refresh page for a new key)</label>
                  <input type="text" required id="keyInput" name="activation_key">

                  <label for="key_duration">Key Validity Duration</label>
                  <div class="input-wrapper">
                    <input type="number" name="duration_number">
                    <select name="duration_period">
                      <option value="days">Day(s)</option>
                      <option value="months">Month(s)</option>
                      <option value="years">Year(s)</option>
                    </select>
                  </div>

                  <label for="trial">Trial Period<input type="checkbox" name="trial" value="true"></label>

                  <input type="submit" name="submit" value="Add Subscription">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newSubscription')" name="button">Close &times;</button>
              </div>
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

  </div>
  <script src="../assets/js/app.min.js" charset="utf-8"></script>
</body>
</html>
