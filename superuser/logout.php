<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../app/vendor/autoload.php';

use app\SuperUser;

$SuperUser = new SuperUser();

$SuperUser->invalidateTokens(intval($_SESSION['auth_uid']), $_SESSION['auth_token']);

unset($_SESSION['auth_token']);
unset($_SESSION['auth_uid']);
unset($_SESSION['auth_uname']);
$_SESSION['success'] = "You have been logged out successfuly";
header("Location:". $_ENV['APP_URL'] . '/superuser');
