<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';


use app\PurchaseOrder;

$PurchaseOrder = new PurchaseOrder;
print_r("Filet");

$vendorName = $PurchaseOrder->sanitiseInput($_POST['vendor_name']);
$fromDate = $PurchaseOrder->sanitiseInput($_POST['from_date']);
$toDate = $PurchaseOrder->sanitiseInput($_POST['to_date']);
$PurchaseOrder->filterData($vendorName, $fromDate, $toDate);