<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/28/2016
 * Time: 3:05 PM
 */
require APPPATH.'libraries/fpdf/PDF_Rotate.php';
class LcoSubscriberList extends PDF_Rotate
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
        $this->Cell(260,10,'Subscriber List Information',0,1,'C');
        $this->SetXY(10,30);
        $this->SetFont('Arial','B',12);
        $w=38;
        if($this->data['user_type'] != 'lco' && empty($this->data['id'])){
            $w=33;
            $w = $w-1;
        }

        $h=7;

        $this->Cell($w+20,$h,'Filter : '.ucwords($this->data['filter']),0,1,'L');
        if($this->data['user_type'] == 'lco'){
            $this->Cell($w+20,$h,'LCO : '.ucwords($this->data['parentName']),0,1,'L');
        }else{
            if(!empty($this->data['id'])){
                $this->Cell($w+20,$h,'LCO : '.ucwords($this->data['parentName']),0,1,'L');
            }
        }

        $this->Cell($w+10,$h,'Name','T,R,L',0,'C');

        if($this->data['user_type'] != 'lco' && empty($this->data['id'])){
            $this->Cell($w+10,$h,'Parent Name','T,R,L',0,'C');

        }
        $this->Cell($w,$h,'Total STB','T,R,L',0,'C');
        $this->Cell($w,$h,'Packages','T,R,L',0,'C');
        $this->Cell($w,$h,'Total Payable','T,R,L',0,'C');
        $this->Cell($w,$h,'Balance','T,R,L',0,'C');
        $this->Cell($w,$h,'Subscription','T,R,L',0,'C');
        $this->Cell($w,$h,'Status','T,R,L',1,'C');

    }
}