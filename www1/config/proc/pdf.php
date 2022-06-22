<?php
    require '../include/fpdf/fpdf.php';
    require_once '../include/core.php';

    $invoice = getSession('invoice');
    $name = "$company"."_"."$invoice.pdf";

    $sql = "SELECT * FROM `stock_in` WHERE `invoice` = '$invoice'";
    $stmt = $pdo->query($sql);

    $pdf = new FPDF('P','mm','A4');

    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->SetX(120);
    $pdf->Cell(0,10,$invoice,'');
    $pdf->SetX(15);
    $pdf->Cell(0,10,$company,'','L');
    $pdf->Ln('10');
    $pdf->SetX(0);
    $pdf->SetFont('Arial','',12);
    $pdf->SetLeftMargin(10);
    $pdf->SetRightMargin(10);
    $pdf->SetTopMargin(10);
    $pdf->Cell(20,6,'QTY',1);
    $pdf->Cell(100,6,'DESCRIPTION',1);
    $pdf->Cell(30,6,'UNIT PRICE',1);
    $pdf->Cell(30,6,'AMOUNT',1);

    $pdf->Ln('6');
    $total = 0;
    while ($item = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        $total += $item['price'] * $item['quantity'];
        $desc = substr($item['item'],0,30);
        $pdf->SetFont('Arial','',8);
        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(10);
        $pdf->SetTopMargin(10);
        $pdf->Cell(20,6,$item['quantity'],1);
        $pdf->Cell(100,6,$item['item'],1);
        $pdf->Cell(30,6,number_format($item['price'],2),1);
        $pdf->Cell(30,6,number_format($item['price'] * $item['quantity'],2),1);
        $pdf->Ln('6');
    }
    $pdf->Ln(10);
    $pdf->SetFont('','B','10');

    $pdf->Cell('100','6',"Sub Total : ".number_format($total,2),'','1');
    $pdf->Cell('100','6','Tax (4) : '. cal_percentage($tax_rate,$total),'','1');
    $pdf->Cell('100','6','Total : ' . number_format($total + cal_percentage($tax_rate,$total),2),'','1');


    $pdf->Output();
//    $pdf->Output('D',$name);