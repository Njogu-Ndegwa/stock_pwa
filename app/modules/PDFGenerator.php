<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class PDFGenerator extends FPDF
{

    public function makePDF($header,$data)
    {


      // Column widths
      $w = array(40, 35, 40, 45);
      // Header
      for($i=0;$i<count($header);$i++)
          $this->Cell($w[$i],7,$header[$i],1,0,'C');
      $this->Ln();
      // Data
      foreach($data as $row)
      {
          $this->Cell($w[0],6,$row['item_name'],1);
          $this->Cell($w[1],6,$row['item_description'],1);
          $this->Cell($w[2],6,$row['item_quantity'],1);
          $this->Cell($w[3],6,$row['item_kg'],1);
          $this->Ln();
      }
      // Closing line
      $this->Cell(array_sum($w),0,'','T');
    }

}
