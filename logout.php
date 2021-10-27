<?php
if (!isset($_SESSION)) {
    session_start();
}
session_unset($_SESSION['auth_token']);
session_unset($_SESSION['auth_uid']);
session_unset($_SESSION['auth_uname']);
$_SESSION['success'] = "You have been logged out successfuly";
header("Location:superadministrator-signup");
