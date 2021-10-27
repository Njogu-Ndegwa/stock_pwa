<?php
if (!isset($_SESSION)) {
    session_start();
}
unset($_SESSION['auth_token']);
unset($_SESSION['auth_uid']);
unset($_SESSION['auth_uname']);
$_SESSION['success'] = "You have been logged out successfuly";
header("Location:superadministrator-signup");
