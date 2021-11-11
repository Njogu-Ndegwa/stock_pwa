<?php
require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Material;

use app\PDFGenerator;

if (!empty($_POST['customer_name']) && !empty($_POST['coating_job_no']) && !empty($_POST['quotation_no']) && !empty($_POST['lpo_no'])  && !empty($_POST['delivery_number'])  && !empty($_POST['date']) && !empty($_POST['material']) && !empty($_POST['weight']) && !empty($_POST['profile_type']) && !empty($_POST['powder_estimate'])) {
  $Material = new Material();

  $customerID = $Material->sanitiseInput($_POST['customer_name']);

  $coatingJobNumber = $Material->sanitiseInput($_POST['coating_job_no']);

  $lpoNo = $Material->sanitiseInput($_POST['lpo_no']);

  $deliveryNo = $Material->sanitiseInput($_POST['delivery_number']);

  $date = $Material->sanitiseInput($_POST['date']);

  $material = $Material->sanitiseInput($_POST['material']);

  $weight = $Material->sanitiseInput($_POST['weight']);

  $profileType = $Material->sanitiseInput($_POST['profile_type']);

  $powderEstimate = $Material->sanitiseInput($_POST['powder_estimate']);

  $powderUsed = $Material->sanitiseInput($_POST['powder_used']);

  $ral = $Material->sanitiseInput($_POST['ral']);

  $color = $Material->sanitiseInput($_POST['color']);

  $code = $Material->sanitiseInput($_POST['code']);

  $owner = $Material->sanitiseInput($_POST['owner']);

  $items = array();

  for ($i=0; $i < count($_POST['item_code']) ; $i++) {
    $rowItem = array(
      'item_code' => $_POST['item_code'][$i],
      'item_description' => $_POST['item_description'][$i],
      'item_quantity' => $_POST['item_quantity'][$i],
      'item_kg' => $_POST['item_kg'][$i]
    );
    array_push($items, $rowItem);
  }

  $itemsSectionData = json_encode($items);

  $preparedBy = $_POST['prepared_by'];

  $approvedBy = $_POST['approved_by'];

  $supervisor = $_POST['supervisor'];

  $qualityBy = $_POST['quality_by'];

  $inDate = $_POST['in_date'];

  $outDate = $_POST['out_date'];

  $readyDate = $_POST['ready_date'];

  $addJobResponse = $Material->addCoatingJob( $customerID, $coatingJobNumber, $lpoNo, $deliveryNo, $date, $material, $weight, $profileType, $powderEstimate, $powderUsed, $ral, $color, $code, $owner, $itemsSectionData, $preparedBy, $approvedBy, $supervisor, $qualityBy, $inDate, $outDate, $readyDate);

  // Column headings
  $header = array('Item Code', 'Item Quantity', 'Quantity', 'KG');

  $pdf = new pdfGenerator();

  $pdf->SetFont('Arial','',14);

  $pdf->AddPage();

  $pdf->makePDF($header,$items);

  $pdf->Output('D', 'quotation.pdf');

  if ($addJobResponse['response'] == '200') {
    $_SESSION['success'] = "New coating job has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add coating job";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a coating job is missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
