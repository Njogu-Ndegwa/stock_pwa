<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';


use app\PurchaseOrder;

$PurchaseOrder = new PurchaseOrder;


$PurchaseOrder->generatePdf();