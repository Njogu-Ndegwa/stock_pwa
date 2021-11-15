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
          $this->Cell($w[0],6,$row[$header[1]],1);
          $this->Cell($w[1],6,$row['record_date'],1);
         
          $this->Ln();
      }
      // Closing line
      $this->Cell(array_sum($w),0,'','T');
    }

}
