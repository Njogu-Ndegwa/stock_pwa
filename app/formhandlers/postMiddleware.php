<?php
date_default_timezone_set('Africa/Nairobi');

use app\CSRF;

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
    header("HTTP/1.1 403 Forbidden");

    $forbiddenPage = file_get_contents(__DIR__.'/../../403.php');

    exit($forbiddenPage);
}

if (!CSRF::validate_token($_POST['token'])) {
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
}
