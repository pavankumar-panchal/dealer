<?php
 
ini_set('memory_limit', '2048M');
include('../functions/phpfunctions.php');
require_once('tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 


// set font
$pdf->SetFont('times', '', 9);

// add a page
$pdf->AddPage();
	$query = "SELECT * from inv_mas_customer where slno = '13457'";
	$fetchresult = runmysqlqueryfetch($query);
	
	$query1 = "SELECT inv_mas_product.productcode as productcode , inv_mas_product.productname as productname, 
	inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, 
	inv_mas_scratchcard.cardid as cardno, inv_mas_scratchcard.scratchnumber as pinno
	FROM inv_dealercard LEFT JOIN inv_mas_scratchcard 
	ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product
	ON inv_mas_product.productcode = inv_dealercard.productcode  WHERE inv_dealercard.cusbillnumber = '3817';";
	$result = runmysqlquery($query1);
	
	
	$query2 = "SELECT inv_billdetail.productamount from inv_billdetail where inv_billdetail.cusbillnumber = '3817';";
	$result2 = runmysqlquery($query2);
	while($fetch2 = mysqli_fetch_array($result2))
	{
		$amount[] = $fetch2['productamount'];
	}
	
	$query3 = "Select * from inv_bill where inv_bill.slno = '3817'";
	$result3 = runmysqlqueryfetch($query3);
	
	$cellcontent = '<strong>RELYON SOFTECH LTD.</strong><br /><font color="#008000"><strong>The Ultimate Arena for Software Products.</strong></font><br /> No. 73, Shreelekha Complex,<br />WOC Road,Bangalore :560 086<br  /><strong>Phone</strong>: 91-08-23002100  <br  /><strong>Telefax</strong>: 91-08-23193552<br  /><strong>Email</strong>: info@relyonsoft.com<br  /><strong>Service Tax No</strong> : AASCR7796NST001</span>';
	$content = '<span ><font color="#FF3300"><strong>TAX INVOICE</strong></font></span>';
	$linkgrid = '<span ><table width="100%" border="0" cellspacing="0" cellpadding="1" style="font-size:8">
	<tr>  <td width="50%"><strong>To<br />  M/s,</strong></td> <td><strong>Bill No : 3817</strong></td></tr>
	<tr><td width="50%"> '.$fetchresult['businessname'].'</td>  <td> <strong>Email :</strong> '.$fetchresult['emailid'].'</td>
	</tr><tr><td width="50%"> '.$fetchresult['address'].'</td> <td> <strong>Cutomer ID: </strong> '.$fetchresult['customerid'].'</td> </tr><tr><td width="50%">'.$fetchresult['place'].' : '.$fetchresult['pincode'].'</td> <td><strong>Date :</strong> '.date('d/m/Y').'</td> </tr></table></span>';
	$grid .= '<table width="100%" border="1" cellspacing="0" cellpadding="3">';
	$grid .= '<tr bgcolor="#faebd7"> <th style="text-align:center;"  width="5%"><strong>Slno</strong></th> <th style="text-align:center;" width="65%"><strong>Description</strong></th> <th style="text-align:center;" width="30%"><strong>Amount</strong></th> </tr>';
	$k = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		$slno++;
		$grid .= '<tr >';
		$grid .= '<td width="5%" style="text-align:centre;">'.$slno.'</td>';
		$grid .= "<td width='65%'>".$fetch['productname']."<br/><strong>Purchase Type</strong> : ".$fetch['purchasetype']."&nbsp;/&nbsp;<strong>Usage Type</strong> : ".$fetch['usagetype']."&nbsp;/&nbsp;<strong>PIN Serial Number</strong> : ".$fetch['cardno']."&nbsp;/<br/><strong>PIN Number </strong>: ".$fetch['pinno']."</td>";
		$grid .= '<td  width="30%" style="text-align:right;" >'.$amount[$k].'</td>';
		$grid .= "</tr>";
		$k++;
	}
	$grid .= '<tr><td width="70%" style="text-align:right;border:none" colspan="2"><strong>Total</strong> </td><td width="30%" style="text-align:right;"><strong>'.$result3['total'].'</strong></td></tr>';
	$grid .= '<tr><td width="70%" colspan="2" style="text-align:right;border:none"><strong>Service tax@ 10.3%</strong></td><td width="30%" style="text-align:right;"><strong>'.$result3['taxamount'].'</strong></td></tr>';
	$grid .= '<tr><td width="70%" style="text-align:right;border:none" colspan="2"><strong>Net Amount</strong></td><td width="30%" style="text-align:right;"><strong>'.$result3['netamount'].'</strong></td></tr>';
	$grid .= '<tr><td  colspan="3">Rupee In Words : </td></tr>';
	$grid .= '<tr><td  colspan="2" width="70%">&nbsp; </td><td width="30%"><font color="#ff0000">For <strong>RELYON SOFTECH LTD</strong></font><br/>
	<div  style="text-align:centre;">Webmaster (Imax)</div></td></tr>';
	$grid .= '</table>';
	$content2 = '<span ><font size="8">This is a computer generated invoice and hence do not require signature.</font></span>';
	
	
			
$pdf->writeHTMLCell(0, 0, '', '', $cellcontent, 'LRT', 1, 0, true, 'L');
$pdf->writeHTMLCell(0, 0, '', '', $content, 'LRB', 1, 0, true, 'C');
$pdf->writeHTMLCell(0, 0, '', '', $linkgrid, 'LR', 1, 0, true, '');
$pdf->writeHTML($grid, true, 0, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $content2, '', 1, 0, true, 'C');
$pdf->Image('../images/relyon-logo.jpg', 149, 10, 40, 15, '', '', '', true, 150);

// reset pointer to the last page
$pdf->lastPage();

$addstring ="/";
if($_SERVER['HTTP_HOST'] == "meghanab")
	$addstring = "/rnd";
	$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/upload/test4.pdf';
	
//$pdf->Output($filepath ,'F');
$pdf->Output('example.pdf' ,'I');



?>
