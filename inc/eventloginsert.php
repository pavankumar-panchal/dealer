<?php
if(imaxgetcookie('dealeruserid') <> '' || imaxgetcookie('dealeruserid') <>  false) 
{
	$pagelinksplit = explode('/',$pagelink);
	$pagelinkvalue = substr($pagelinksplit[2],0,-4);
	//echo($pagelinkvalue);exit;
	$userid = imaxgetcookie('dealeruserid');
	switch($pagelinkvalue)
	{
		case 'index':  $pagetextvalue = '224'; break;
		case 'customer':  $pagetextvalue = '225'; break;
		case 'dealerinteraction':  $pagetextvalue = '226'; break;
		case 'cus-cardattach':  $pagetextvalue = '227'; break;
		case 'crossproductinfo':  $pagetextvalue = '228'; break;
		case 'invoicing':  $pagetextvalue = '229'; break;
		case 'receipts':  $pagetextvalue = '230'; break;
		case 'invoiceregister':  $pagetextvalue = '231'; break;
		case 'receiptregister':  $pagetextvalue = '232'; break;
		case 'outstandingregister':  $pagetextvalue =  '233'; break;
		case 'implementation':  $pagetextvalue = '234'; break;
		case 'viewpurchase':  $pagetextvalue = '235'; break;
		case 'transactionsummary':  $pagetextvalue = '236'; break;
		case 'dealerstockreport':  $pagetextvalue = '237'; break;
		case 'registration':  $pagetextvalue = '238'; break;
		case 'cuscardattachreport':  $pagetextvalue = '239'; break;
		case 'labelsforcontactdetails':  $pagetextvalue ='240'; break;
		case 'custdata':  $pagetextvalue ='241'; break;
		case 'editprofile':  $pagetextvalue ='242'; break;
		case 'changepw':  $pagetextvalue ='243'; break;
		case 'matrixinvoicing':  $pagetextvalue ='266'; break;
		case 'matrixinvoiceregister':  $pagetextvalue ='267'; break;
		case 'implementationsummary':  $pagetextvalue ='268'; break;
		case 'implementationstatusreport':  $pagetextvalue ='269'; break;
		case 'matrixreceipts':  $pagetextvalue ='270'; break;
		case 'blockcancel':  $pagetextvalue ='271'; break;
		case 'handholddetailed':  $pagetextvalue ='276'; break;
	}
	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','".$pagetextvalue."','".date('Y-m-d').' '.date('H:i:s')."')";
	$eventresult = runmysqlquery($eventquery);
}
	
?>
