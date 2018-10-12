<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/27/2016
 * Time: 12:48 PM
 */
require APPPATH.'libraries/fpdf/PDF_Rotate.php';
class ClientStatement extends PDF_Rotate
{
    private $data;

    public function __construct($orientation, $unit, $size)
    {
        parent::__construct($orientation, $unit, $size);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function Header()
    {
        $this->SetFont('Arial','B',16);
        $this->Cell(176,10,'Client Statement',0,1,'C');
        $this->SetY(20);

        $w=25;
        $h=8;
        //$this->SetX(15);
        $this->SetFont('Arial','B',11);
        $this->Cell($w,$h,'Client Name : ',0,0,'L');
        $this->SetFont('Arial','',11);
        $this->Cell($w+20,$h,'   '.$this->data['client_name'],0,1,'L');
        //$this->SetX(15);
        $this->SetFont('Arial','B',11);
        $this->Cell($w,$h,'LCO Name   : ',0,0,'L');
        $this->SetFont('Arial','',11);
        $this->Cell($w+20,$h,'   '.$this->data['lco_name'],0,1,'L');
        //$this->SetX(15);
        $this->SetFont('Arial','B',11);
        $this->Cell($w,$h,'From Date    : ',0,0,'L');
        $this->SetFont('Arial','',11);
        $this->Cell($w,$h,'   '.$this->data['from_date'],0,0,'L');
        $this->SetFont('Arial','B',11);
        $this->Cell($w,$h,'To Date: ',0,0,'R');
        $this->SetFont('Arial','',11);
        $this->Cell($w,$h,$this->data['to_date'],0,1,'R');

        $this->Cell($w,5,'',0,1,'C');
        $this->SetX(12);
        $this->SetFont('Arial','B',10);
        $this->Cell($w,10,'Sl','T,R,L',0,'C');
        $this->Cell($w+20,$h,'Date','T,R,L',0,'C');
        $this->Cell($w+20,$h,'Description','T,R,L',0,'C');
        $this->Cell($w+10,$h,'Credit','T,R,L',0,'C');
        $this->Cell($w+10,$h,'Debit','T,R,L',1,'C');
    }
}