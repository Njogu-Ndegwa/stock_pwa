<?php

namespace app;


use app\PDFGenerator;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class PurchaseOrder extends Database
{
    private $DBConnection;

    public function __construct()
    {
        $connectionAttempt = $this->dbConnect();
        if ($connectionAttempt['response'] == '200') {
            $this->DBConnection = $connectionAttempt['data'];
        } else {
            Logger::logToFile('Error', $connectionAttempt['message']);
            throw new \Exception("Error experienced connecting the Purchase Order module to the database", 1);
            exit();
        }
    }

    /**
     * Sanitize the input to prevent against SQL injection
     */
    public function sanitiseInput($inputVariable)
    {
        return $this->sanitiseData($inputVariable, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function getPurchaseOrders()
    {
        $getPurchaseOrderSQL = "SELECT * FROM `purchase_order` INNER JOIN vendors ON vendors.vendor_id  = purchase_order.vendor_name";

        return $this->selectSQLStatement($getPurchaseOrderSQL, $this->DBConnection);
    }

    /**
     * Add Purchase Order
     */
    public function addPurchaseOrder(String $vendorName, int $totalAmount, String $itemsSectionData, String $recordDate, String $dueDate, String $quotationReference, String $quotationDate,  String $termsConditions,   $poStatus, String $createdBy)
    {
        $addPurchaseOrderSQL = "INSERT INTO `purchase_order`(vendor_name, amount, item, record_date, due_date, quotation_reference, quotation_date, terms_and_conditions, po_status, created_by) VALUES ('$vendorName', '$totalAmount', '$itemsSectionData,', '$recordDate', '$dueDate', '$quotationReference', '$quotationDate', '$termsConditions', '  $poStatus', '$createdBy')";

        return $this->insertSQLStatement($addPurchaseOrderSQL, $this->DBConnection);
    }

    /**
     * Edit Purchase Order
     */
    public function editPurchaseOrder(String $purchaseOrderID, String $vendorName, int $totalAmount, String $itemsSectionData, String $recordDate, String $dueDate, String $quotationReference, String $quotationDate,  String $termsConditions,   String $poStatus, String $updatedBy)
    {
        $updatePurchaseOrderSQL = "UPDATE `purchase_order` SET vendor_name = '$vendorName', amount = '$totalAmount', item = '$itemsSectionData', record_date= '$recordDate', due_date = '$dueDate', quotation_reference = '$quotationReference', quotation_date= '$quotationDate', terms_and_conditions ='$termsConditions', po_status='$poStatus', updated_by='$updatedBy' WHERE purchase_order_id = '$purchaseOrderID'";

        return $this->updateSQLStatement($updatePurchaseOrderSQL, $this->DBConnection);
    }

    /**
     * Delete Purchase Order
     */
    public function deletePurchaseOrder(String $purchaseOrderID)
    {
        $deletePurchaseOrderSQL = "DELETE FROM `purchase_order` WHERE purchase_order_id = '$purchaseOrderID'";

        return $this->deleteSQLStatement($deletePurchaseOrderSQL, $this->DBConnection);
    }

    public function generatePdf() {
        // print_r("JHdshada");
        // $data = "Generate pdf hit";
        // echo '<script>';
        // echo 'console.log('. json_encode( $data ) .')';
        // echo '</script>';
        $display_heading = array('vendor_name'=>'Vendor Name', 'quotation_reference'=> 'Quotation Reference');
        $resultSQLSmt = "SELECT `vendor_name`, `quotation_reference` FROM `purchase_order`";
        $result = $this->selectSQLStatement($resultSQLSmt, $this->DBConnection);
        // print_r($result['data']);
        $header = array('vendor_name','quotation_reference');
        // $fileName = "../formhandlers/purchaseorder".time()."-uploadFile.pdf";
        $pdf = new PDFGenerator();
        $pdf->SetFont('Arial','',14);
        $pdf->AddPage();
        $pdf->makePDF($header,$result['data']);
         $pdf->Output();
    }

    public function filterData(String $vendorId, $fromDate, $toDate) {
        $getPurchaseOrderSQL = "SELECT * FROM `purchase_order` INNER JOIN vendors ON vendors.vendor_id  = purchase_order.vendor_name WHERE purchase_order.vendor_name = '$vendorId' and purchase_order.created_by >$fromDate and purchase_order.created_by<$toDate " ;
        $data =  $this->selectSQLStatement($getPurchaseOrderSQL, $this->DBConnection);
        return $data;
    }

}
