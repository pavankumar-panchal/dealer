<?php
if($selectedcookievalue <> '')
{
    //Total product quantity
    for($i=0;$i<count($productquantitysplit);$i++)
    {
        $totalproductquantity += $productquantitysplit[$i];
    }
    
    //per product price
    $productpricearraysplit = explode('*',$productpricearray);
    
    //Make the product details to an array
    $arraysplit = explode('#',$selectedcookievalue);
    $purchasetypesplit = explode(',',$purchasetype);
    $usagetypesplit = explode(',',$usagetype);


    $k = 0;
    for($i = 0; $i < $totalproductquantity; $i++)
    {
        for($j = 0; $j < $productquantitysplit[$i]; $j++)
        {
            //Execute the PIN number Purchase
            $query7 = "SELECT attachPIN() as cardid;";
            $result7 = runmysqlqueryfetch($query7);
            
            $cardid = $result7['cardid'];
            //$cardid = 401079;
            
            $usagetypevalue = $usagevaluesplit[$i];
            if($usagetypevalue == 'addlic')
            {
                $usagetype = 'singleuser';
                $addlicence = 'yes';
            }
            else
            {
                $usagetype = $usagevaluesplit[$i];
                $addlicence = '';
            }

            //Attach that PIN Number to that customer
            $query7 = "INSERT INTO inv_dealercard(dealerid,cardid,productcode,date,usagetype,purchasetype,userid,customerreference,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,cusbillnumber,scheme,cuscardattacheddate,cuscardattachedby,usertype,addlicence,invoiceid) values('".$currentdealer."','".$cardid."','".$arraysplit[$i]."','".date('Y-m-d').' '.date('H:i:s')."','".$usagetype."','".$purchasevaluesplit[$i]."','2','".$lastslno."','".$usagetype."','".$purchasevaluesplit[$i]."','".$arraysplit[$i]."','".$currentdealer."','".$firstbillnumber."','1','".date('Y-m-d').' '.date('H:i:s')."','".$currentdealer."','dealer','".$addlicence."','".$onlineinvoiceslno."')";
            $result1 = runmysqlquery($query7);
            $k++;

        }
    }
}
if($paymenttype <> 'credit/debit')
{
    //If payment is made now, insert the data to receipts table
    if($paymenttypeselected <> 'paymentmadelater')
    {
        $query = "INSERT INTO inv_mas_receipt(invoiceno,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,chequedate,chequeno,drawnon,depositdate,receiptdate,receipttime,module,partialpayment) values('".$onlineinvoiceslno."','".$paymentamount."','".$paymentmode."','','','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$lastslno."','".$chequedate."','".$chequeno."','".$drawnon."','".$depositdate."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','dealer_module','".$partialpayment."');";
        $result = runmysqlquery($query);
    }
}
if($selectedcookievalue <> '')
{
    $carddetailsquery = "select * from inv_dealercard left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid  where invoiceid = '".$onlineinvoiceslno."';";
    $carddetailsresult = runmysqlquery($carddetailsquery);
    $slno = 0;
    $k;
    $descriptioncount = 0;
    $k=0;
    while($carddetailsfetch = mysqli_fetch_array($carddetailsresult))
    {
        $slno++;
        if($carddetailsfetch['purchasetype'] == 'new')
            $purchasetype = 'New';
        else
            $purchasetype = 'Updation';
        if($carddetailsfetch['addlicence'] == 'yes')
        {
            $usagetype = 'Additional License';
        }
        else
        {
            if($carddetailsfetch['usagetype'] == 'singleuser')
                $usagetype = 'Single User';
            else
                $usagetype = 'Multi User';
        }
        
        if($descriptioncount > 0)
            $description .= '*';
        //query changed for productcodes
        $procode = $carddetailsfetch['productcode'];
        $subgroup = $carddetailsfetch['subgroup'];
        if($procode == "690" || $procode == "222" || $procode == "221" || $procode == "219" || $procode == "223"
        || $procode == "220" || $procode == "101")
        {
            $description .= $slno.'$'.$carddetailsfetch['productname'].'$'.
        $purchasetype.'$'.$usagetype.'$'.$carddetailsfetch['scratchnumber'].'$'.$carddetailsfetch['cardid'].'$'.
        $productpricearraysplit[$k];
        }
        elseif($subgroup == "ESS")
		{
			$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.
			$purchasetype.'$'.''.'$'.$carddetailsfetch['scratchnumber'].'$'.$carddetailsfetch['cardid'].'$'.
			$productpricearraysplit[$k];
		}
        else
        {
        $description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.
        $purchasetype.'$'.$usagetype.'$'.$carddetailsfetch['scratchnumber'].'$'.$carddetailsfetch['cardid'].'$'.
        $productpricearraysplit[$k];
        }
        $k++;
        $descriptioncount++;
    }
}
if($servicelist <> '')
{
    $servicecount = 0;
    for($i=0; $i<count($servicelistsplit); $i++)
    {
        $slno++;
        if($servicecount > 0)
            $servicegrid .= '*';
        $servicegrid .= $slno.'$'.$servicelistsplit[$i].'$'.$serviceamountvaluessplit[$i];
        $servicecount++;
    }
}
if($descriptiontypevalues <> '')
{
    $offerdescriptioncount = 0;
    for($i=0; $i<count($descriptiontypevaluessplit); $i++)
    {
        $slno++;
        if($offerdescriptioncount > 0)
            $offerdescriptiongrid .= '*';
        $offerdescriptiongrid .= $descriptiontypevaluessplit[$i].'$'.$descriptionvaluesplit[$i].'$'.$descriptionamountvaluessplit[$i];
        $offerdescriptioncount++;
    }
}
//Update online invoice number
$invoicequery = "update inv_invoicenumbers set description = '".$description."', offerdescription = '".$offerdescriptiongrid."', servicedescription = '".$servicegrid."' where slno  ='".$onlineinvoiceslno."';";
$invoiceresult = runmysqlquery($invoicequery);

//update preonline purchase table with invoice no and other details 
$query1 = "UPDATE dealer_online_purchase SET paymentdate = '".date('Y-m-d')."', paymenttime = '".date('H:i:s')."',paymentremarks = '".$paymentremarksnew."',onlineinvoiceno = '".$onlineinvoiceslno."' WHERE slno = '".$slnotobeinserted."'";
$result = runmysqlquery($query1);

$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','122','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno.'$$'.$onlineinvoiceslno."')";
$eventresult = runmysqlquery($eventquery);

//Online bill Generation in PDF.
$pdftype = 'send';
$invoicedetails = vieworgeneratepdfinvoice($onlineinvoiceslno,$pdftype);
$invoicedetailssplit = explode('^',$invoicedetails);
$filebasename = $invoicedetailssplit[0];
sendpurchasesummaryemail($currentdealer,$slnotobeinserted);
    
#########  Mailing Starts -----------------------------------
if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
{
    $emailid = 'rashmi.hk@relyonsoft.com';
}
else
{
    $emailid = $resultantemailid;
}
    
$emailarray = explode(',',$emailid);
$emailcount = count($emailarray);

for($i = 0; $i < $emailcount; $i++)
{
    if(checkemailaddress($emailarray[$i]))
    {
            $emailids[$emailarray[$i]] = $emailarray[$i];
    }
}

if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
{
    $dealeremailid = 'archana.ab@relyonsoft.com';
}
else
{
    $dealeremailid = $dealeremailid;
}
//CC to Sales person
$ccemailarray = explode(',',$dealeremailid);
$ccemailcount = count($ccemailarray);
for($i = 0; $i < $ccemailcount; $i++)
{
    if(checkemailaddress($ccemailarray[$i]))
    {
        if($i == 0)
            $ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
        else
            $ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
    }
}
//Get the statename and district name
$query11 = "select districtname,statename,category as regionname,branchname from inv_mas_district left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_district.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid where inv_mas_district.districtcode = '".$district."'";
$resultfetch1 = runmysqlqueryfetch($query11);

$districtname = $resultfetch1['districtname'];
$statename = $resultfetch1['statename'];
$regionname = $resultfetch1['regionname'];
$branchname = $resultfetch1['branchname'];

$fromname = "Relyon";
$fromemail = "imax@relyon.co.in";
require_once("../inc/RSLMAIL_MAIL.php");
$msg = file_get_contents("../mailcontents/paymentinfo1.htm");
$textmsg = file_get_contents("../mailcontents/paymentinfo1.txt");
$subject = "Relyon Online Invoice | ".$invoicenoformat;
$date = date('d-m-Y');
$time = date('H:i:s');
$array = array();
//$company = $fetch['businessname'];
$array[] = "##DATE##%^%".$date;
$array[] = "##TIME##%^%".$time;
$array[] = "##COMPANYNAME##%^%".$businessname;
$array[] = "##CUSTOMERID##%^%".cusidcombine($generatedcustomerid);
$array[] = "##CONTACTPERSON##%^%".$contactpersonplit;
$array[] = "##PLACE##%^%".$place;
$array[] = "##ADDRESS##%^%".$address;
$array[] = "##PINCODE##%^%".$pincode;
$array[] = "##STDCODE##%^%".$stdcode;
$array[] = "##PHONE##%^%".$phone;
$array[] = "##CELL##%^%".$cell;
$array[] = "##EMAIL##%^%".$emailid;
$array[] = "##TOTALAMOUNT##%^%".$netamount;
$array[] = "##TABLE##%^%".$grid;
$array[] = "##INVOICENOFORMAT##%^%".$invoicenoformat;
$array[] = "##EMAILID##%^%".$emailid;
$array[] = "##SUBJECT##%^%".$subject;
    
$filearray = array(
array('../images/relyon-logo.jpg','inline','1234567890'),array('../images/relyon-rupee-small.jpg','inline','1234567892'),
array('../filecreated/'.$filebasename.'','attachment','1234567891')
);

//Mail to customer
$toarray = $emailids;

//CC to sales person
$ccarray = $ccemailids;
//Copy of email to Accounts / Vijay Kumar / Bigmails 
if(($_SERVER['HTTP_HOST'] == "localhost") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
{
    $bccarray = array('bhumika' => 'bhumika.p@relyonsoft.com'); 
}
else
{
    $bccarray = array('Accounts' => 'bills@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com'); 
}

                // var_dump($bccarray);
$msg = replacemailvariable($msg,$array);
$textmsg = replacemailvariable($textmsg,$array);
$attachedfilename = explode('.',$filebasename);
$html = $msg;
$text = $textmsg;
$replyto = $ccemailids[$ccemailarray[0]];
//rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
$bcceallemailid = 'webmaster@relyonsoft.com';
inserttologs(imaxgetcookie('dealeruserid'),imaxgetcookie('dealeruserid'),$fromname,$fromemail,$emailid,$dealeremailid,$bcceallemailid,$subject);
fileDelete('../filecreated/',$filebasename);

//check for payment type
if($paymenttype != 'credit/debit')
{
    echo(json_encode('1^Card Attached^'.$onlineinvoiceslno.''));
}
else
{
    echo(json_encode('3^Card Attached^'.$onlineinvoiceslno.''));
}
?>