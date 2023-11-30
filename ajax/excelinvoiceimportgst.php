<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

$flag = $_POST['flag'];

if($flag == '')
{
	$url = '../home/index.php?a_link=importinvoicesgst'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	
  $toinvoicenonew = $_POST['toinvoicenonew'];
  $frominvoicenonew = $_POST['frominvoicenonew'];
  $frominvoicetype = $_POST['frominvoicetype'];
  $toinvoicetype = $_POST['toinvoicetype'];
  $fromstatetype = $_POST['fromstatetype'];
  $tostatetype = $_POST['tostatetype'];
  $userid = imaxgetcookie('userid');
	
	if($frominvoicetype <> $toinvoicetype)
	{
		echo('From Invoice Type and To Invoice Type should be same.');exit;
	}
	else if($fromstatetype <> $tostatetype)
	{
		echo('From State Type and To State Type should be same.');exit;
	}
	else
	{
	
	 $query = "select * from inv_invoicenumbers where inv_invoicenumbers.onlineinvoiceno BETWEEN '".$frominvoicenonew."' AND '".$toinvoicenonew."' and invoice_type ='".$frominvoicetype."' and state_info ='".$fromstatetype."' order by inv_invoicenumbers.slno;";	
		//$query = "select * from inv_invoicenumbers where slno = '43';";	
		$result = runmysqlquery($query);
		
		if(mysqli_num_rows($result) <> 0)
		{
			while($fetch = mysqli_fetch_array($result))
			{
				
				$itemdetail1="";
				$itemdetail3="";
				$itemdetail2="";
				$category=$fetch['category'];
				$invoice_type=$fetch['invoice_type'];
				$state_info=$fetch['state_info'];
				
				$podate=$fetch['podate'];
				$poref=$fetch['poreference'];
				$signature=$fetch['createdby'];
				
				
				$customerid = $fetch['customerid'];
				$customerdetails = customerdetails($customerid);
				$contactarray = contactdetails($customerid);
				$businessname = $fetch['businessname'];
				//str_replace($search, $replace, $subject);
				$line_order   = array("\r\n", "\n", "\r",chr(13),chr(10),"  ");
				$address = trim(str_replace($line_order, "", $fetch['address']));
				
				/*$address_array=explode(" ",$address);
				
				for($i=0;$i<=$add_count;$i++)
				{
					$address_array[$i]=trim($address_array[$i]);
				}*/
				
				$contactperson = $fetch['contactperson'];
				$emailid = $fetch['emailid'];
				$phone = $fetch['stdcode'].$fetch['phone'];
				$cell = $fetch['cell'];
				$customertype = $fetch['customertype'];
				$customercategory = $fetch['customercategory'];
				$invoiceno = $fetch['invoiceno'];
				$createddate = substr($fetch['createddate'],0,10);
				$createdtime = substr($fetch['createddate'],11);
				$dealername = $fetch['dealername'];
				$regionbranch = $fetch['region'].'/'.$fetch['branch'];
				$status = $fetch['status'];
				$onlineinvoiceno=$fetch['onlineinvoiceno'];
				if($status == 'EDITED')
				{
					$query011 = "select * from inv_mas_users where slno = '".$fetch['editedby']."';";
					$resultfetch011 = runmysqlqueryfetch($query011);
					$changedby = $resultfetch011['fullname'];
					$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch['editeddate']).' Remarks: '.$fetch['editedremarks'];
				}
				elseif($status == 'CANCELLED')
				{
					$query011 = "select * from inv_mas_users where slno = '".$fetch['cancelledby']."';";
					$resultfetch011 = runmysqlqueryfetch($query011);
					$changedby = $resultfetch011['fullname'];
					$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch['cancelleddate']).'  Remarks: '.$fetch['cancelledremarks'];
		
				}
				else
					$statusremarks = '';
				$remarks = $statusremarks;
				$servicetaxdesc = $fetch['servicetaxdesc'];
				$amount = $fetch['amount'];
				$netamount = $fetch['netamount'];
				
				$invoicedatedisplay = substr($fetch['createddate'],0,10);
				$invoicedate =  strtotime($invoicedatedisplay);
				$expirydate = strtotime('2012-04-01');
				$expirydate1 = strtotime('2015-06-01');
				$expirydate2 = strtotime('2015-11-15');

				$kk_cess_date = strtotime('2016-05-31');
				
				
				/*---------GST Chnages--------------*/
				
				$gst_date = date('Y-m-d');
        		$gst_tax_date = strtotime('2017-06-08');
        		
        
        		$gst_tax_query= "select igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '$gst_date' AND to_date >= '$gst_date'";
        		$gst_tax_result = runmysqlqueryfetch($gst_tax_query);
        		$igst_tax_rate = $gst_tax_result['igst_rate'];
        		$cgst_tax_rate = $gst_tax_result['cgst_rate'];
        		$sgst_tax_rate = $gst_tax_result['sgst_rate'];
				
				
				/*-----------./Ends----------------*/
				
				if($fetch['seztaxtype'] == 'yes')
				{
					$servicetax1 = 0;
					$servicetax2 = 0;
					$servicetax3 = 0;
					$kk_cess_tax = 0;
					$igst_tax_amount = 0;
					$cgst_tax_amount = 0;
					$sgst_tax_amount = 0;
					
					$seztype = 'yes';
     			}
				else
				{
					$seztype = 'no';
					if($gst_tax_date < $invoicedate)
					{
						$igst_tax_amount = $fetch['igst'];
					    $cgst_tax_amount = $fetch['cgst'];
					    $sgst_tax_amount = $fetch['sgst'];
					    
					    $servicetax1 = 0;
    					$servicetax2 = 0;
    					$servicetax3 = 0;
    					$kk_cess_tax = 0;
    					
    					$servicetaxname = '14%';
                		$servicetaxname1 = '0.5%';
                		$kk_cess_name = '0.5%';

					    $seztaxtype_inv = $fetch['seztaxtype'];
					    $gst_type = '';
					    if($igst_tax_amount != 0)
					    {
					        $gst_type = 'IGST';
							$gst_display = 'IMAX IGST';
							//$cgst_tax_rate = '';
							//$sgst_tax_rate = '';
					    }
					    else
					    {
					        $gst_type = 'CSGST';
							$gst_display = 'IMAX CGST&SGST';
							//$igst_tax_rate = '';
					    }
					}
					else
					{
						  
                						  
                				    if($expirydate >= $invoicedate)
                					{
                						$servicetax1 = roundnearestvalue($fetch['amount'] * 0.1);
                						$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
                						$servicetaxname = '10%';
                						$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
                					}
                					else if($expirydate1 > $invoicedate)
                					{
                						$servicetax1 = roundnearestvalue($fetch['amount'] * 0.12);
                						$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
                						$servicetaxname = '12%';
                						$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
                					}
                					else if($expirydate2 > $invoicedate)
                			        {
                						$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
                						$servicetaxname = '14%';
                					}
                					else
                					{
                						$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
                						$servicetaxname = '14%';
                						$servicetaxname1 = '0.5%';
                						$servicetax2 = roundnearestvalue($fetch['amount'] * 0.005);
                						if($kk_cess_date < $invoicedate)
                						{
                						   $kk_cess_tax = roundnearestvalue($fetch['amount'] * 0.005);
                						   $kk_cess_name = '0.5%';
                						}
                					}
						    
					}
					
					/*if($expirydate > $invoicedate)
					{
						$servicetax1 = roundnearestvalue($fetch['amount'] * 0.1);
						$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
						$servicetaxname = '10%';
						$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
					}
					else
					{
						$servicetax1 = roundnearestvalue($fetch['amount'] * 0.12);
						$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
						$servicetaxname = '12%';
						$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
					}*/
					$sezremarks = '';
					$billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
				}
				$productcode="";
				$productcode = $fetch['products'];
				$description="";
				$description = $fetch['description'];
				$servicedescription = $fetch['servicedescription'];
				$offerdescription = $fetch['offerdescription'];
				if($description <> '')
				{
					$var1 = '^';
				}
				if($servicedescription <> '')
				{
					$var2 = '^';
				}
				if($offerdescription <> '')
				{
					$var3 = '^';
				}
				
				$productbriefdescription ="";
				$productbriefdescriptionsplit="";
				
				$productbriefdescription = $fetch['productbriefdescription'];
				$productbriefdescriptionsplit = explode('#',$productbriefdescription);
				
				$descriptionsplit="";
				$descriptionsplit = explode('*',$description);
				
				$count0 = 1;$count1 = 1;$count2 = 1;
				for($i=0;$i<count($descriptionsplit);$i++)
				{
					$productcodesplit="";
				    $productcodesplit = explode('#',$productcode);
					//echo $productcodesplit[$i];
					
					$productdesvalue = '';
					$descriptionline="";
					$descriptionline = explode('$',$descriptionsplit[$i]);
					if($description <> '')
					{
						/*if($count0 > 1)
							$append = '^';
						else
							$append = '';*/
							
							
							if($productbriefdescription <> '')
								$productdesvalue = $productbriefdescriptionsplit[$i];
							else
								$productdesvalue = 'Not Avaliable';
							
							
							$append='ItemDetail'.'^'.$onlineinvoiceno.'^';
							//echo $descriptionline[1]."<br>";
						//echo $producname=substr($descriptionline[1],0,-11);
						
						$query_group = "select  inv_mas_product.subgroup from inv_mas_product where productcode = '". 
						$productcodesplit[$i]."';";
						
					$resultfetch_group_result = runmysqlquery($query_group);
					$resultfetch_group=mysqli_fetch_array($resultfetch_group_result);
					if($resultfetch_group['subgroup']!="")
					{
						$group=$resultfetch_group['subgroup'];	
					}
					else
					{
						$group="";
						}
							
						$itemdetail1 .= $append.$descriptionline[0].'^'.$descriptionline[1].'^'.'1'.'^'.$descriptionline[6].'^'.$descriptionline[6].'^'.$descriptionline[2].'^'.$descriptionline[3].'^'.$descriptionline[4].'^'.$descriptionline[5] . "^". $productdesvalue . "^" . $group . chr(13);
						$count0++;
				
					}
				}
				$itembriefdescriptionsplit="";
				$itembriefdescription="";
				$itembriefdescription = $fetch['itembriefdescription'];
		$itembriefdescriptionsplit = explode('#',$itembriefdescription);
				$servicedescriptionsplit="";
				$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
				$servicedescriptioncount = count($servicedescriptionsplit);
				if($fetch['servicedescription'] <> '')
				{
					for($i=0; $i<$servicedescriptioncount; $i++)
					{
						$servicedescriptionline="";
						$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
						
						if($itembriefdescription <> '')
							$itemdesvalue = $itembriefdescriptionsplit[$i];
						else
							$itemdesvalue = 'Not Avaliable';
						/*if($count1 > 1)
							$append1 = 'ItemDetail'.'^'.$invoiceno.'^';
						else
							$append1 = '';*/
							
							$append1 = 'ItemDetail'.'^'.$onlineinvoiceno.'^';
						$itemdetail2 .= $append1.$servicedescriptionline[0].'^'.$servicedescriptionline[1].'^'.'1'.'^'.$servicedescriptionline[2].'^'.$servicedescriptionline[2].'^'.''.'^'.''.'^'.''.'^'.''.'^'. $itemdesvalue .'^'.''. chr(13);	
						$count1++;
						
					}
				}
				$offerdescriptionsplit="";
				$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
				$offerdescriptioncount = count($offerdescriptionsplit);
				if($fetch['offerdescription'] <> '')
				{
					for($i=0; $i<$offerdescriptioncount; $i++)
					{
						$offerdescriptionline="";
						$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
						/*if($count2 > 1)
							$append2 = 'ItemDetail'.'^'.$invoiceno.'^';
						else
							$append2 = '';	*/
							$append2 = '';
						//$append2 = 'ItemDetail'.'^'.$invoiceno.'^';	
						$itemdetail3 .= $append2.'^'.$offerdescriptionline[0].'^'.$offerdescriptionline[2].'^'.'1'.'^'.$offerdescriptionline[1].''. chr(13);
						//$itemdetail3 .= $append2.$offerdescriptionline[0].'^'.$offerdescriptionline[2].'^'.$offerdescriptionline[1];
						$count2++;
						
					}
				}
				if($itemdetail2!="")
				{
				$itemdetails = $itemdetail1.$itemdetail2;
				}
				else
				{
					$itemdetails = $itemdetail1;
					}
				##Start Of edited by bhavesh ##
				
				#echo $contactarray;
					
				
				##End Of edited by bhavesh ##
				
/*-----------------Round Off ----------------------*/
  $roundoff = 'false';
  $roundoff_value = '';
  $addition_amount = $fetch['amount']+$fetch['igst']+$fetch['cgst']+$fetch['sgst']+$fetch['kktax']+$fetch['sbtax']+$fetch['servicetax'];

$roundoff_value = ($fetch['netamount'])- ($addition_amount);

if($roundoff_value != 0 || $roundoff_value != 0.00)
{
  $roundoff = 'true';
}
/* if($addition_amount > $fetch['netamount'])
 {
   $roundoff_value = ($addition_amount)- ($fetch['netamount']);
   $roundoff = 'true';
 }
 else if( $addition_amount < $fetch['netamount'])
 {
    $roundoff_value = ($fetch['netamount']) - ($addition_amount);
    $roundoff = 'true';
 }
 else
 { 
     $roundoff_value = '';
	 $roundoff = 'false';
 }*/

/*----Round Off Done ---------------------------*/			
				
				//$invoiceheader = 'InvHeader'.'^'.$category.'^'.$customerid.'^'.$businessname.'^'.$address.'^'.$contactperson.'^'.$emailid.'^'.$phone.'^'.$cell.'^'.$customertype.'^'.$customercategory.'^'.$onlineinvoiceno.'^'.$createddate.'^'.$createdtime.'^'.$dealername.'^'.$regionbranch.'^'.$status.'^'.$remarks.'^'.$podate.'^'.$poref;
				
				
				if($gst_tax_date < $invoicedate)
				{
				    $invoiceheader = 'InvHeader'.$gst_display.'^'.$customerid.'^'.$businessname.'^'.$address.'^'.$contactperson.'^'.$emailid.'^'.$phone.'^'.$cell.'^'.$customertype.'^'.$customercategory.'^'.$onlineinvoiceno.'^'.$createddate.'^'.$createdtime.'^'.$dealername.'^'.$regionbranch.'^'.$status.'^'.$remarks.'^'.$podate.'^'.$poref.'^'.$gst_type;
				    if($roundoff == 'true')
					{
						$roundoff_value = number_format($roundoff_value,2);	
						
						$invoicefooter = 'InvFooter'.'^'.$onlineinvoiceno.'^'.$servicetaxdesc.'^'.$amount.'^'.$seztype.'^'.$igst_tax_rate.'^'.$igst_tax_amount.'^'.$cgst_tax_rate.'^'.$cgst_tax_amount.'^'.$sgst_tax_rate.'^'.$sgst_tax_amount.'^'.$netamount .'^'.$roundoff_value ."^" . $signature.$itemdetail3;					
					}
					else
					{
					$invoicefooter = 'InvFooter'.'^'.$onlineinvoiceno.'^'.$servicetaxdesc.'^'.$amount.'^'.$seztype.'^'.$igst_tax_rate.'^'.$igst_tax_amount.'^'.$cgst_tax_rate.'^'.$cgst_tax_amount.'^'.$sgst_tax_rate.'^'.$sgst_tax_amount.'^'.$netamount ."^" .$signature.$itemdetail3;	
					}

				}
				else
				{
				   $invoiceheader = 'InvHeader'.'^'.$category.'^'.$customerid.'^'.$businessname.'^'.$address.'^'.$contactperson.'^'.$emailid.'^'.$phone.'^'.$cell.'^'.$customertype.'^'.$customercategory.'^'.$onlineinvoiceno.'^'.$createddate.'^'.$createdtime.'^'.$dealername.'^'.$regionbranch.'^'.$status.'^'.$remarks.'^'.$podate.'^'.$poref;
				   //Without GST Calculation
                				if($expirydate >= $invoicedate || $expirydate1 > $invoicedate)
                				{
                				  $invoicefooter = 'InvFooter'.'^'.$onlineinvoiceno.'^'.$servicetaxdesc.'^'.$amount.'^'.$seztype.'^'.$servicetaxname.'^'.$servicetax1.'^'.'2' .'^'. $servicetax2.'^'. '1' . '^'. $servicetax3.'^'.$netamount ."^" . $signature.$itemdetail3;
                				}
                				else if($expirydate2 > $invoicedate)
                				{
                					$invoicefooter = 'InvFooter'.'^'.$onlineinvoiceno.'^'.$servicetaxdesc.'^'.$amount.'^'.$seztype.'^'.$servicetaxname.'^'.$servicetax1.'^'.''.'^'.''.'^'.''.'^'.''.'^'.$netamount ."^" . $signature.$itemdetail3;
                				}
                				else
                				{
                					if($kk_cess_date < $invoicedate)
                					{
                					   $invoicefooter = 'InvFooter'.'^'.$onlineinvoiceno.'^'.$servicetaxdesc.'^'.$amount.'^'.$seztype.'^'.$servicetaxname.'^'.$servicetax1.'^'.$servicetaxname1 .'^'. $servicetax2.'^'.$kk_cess_name.'^'.$kk_cess_tax.'^'.$netamount ."^" . $signature.$itemdetail3;
                					}
                					else
                					{
                						$invoicefooter = 'InvFooter'.'^'.$onlineinvoiceno.'^'.$servicetaxdesc.'^'.$amount.'^'.$seztype.'^'.$servicetaxname.'^'.$servicetax1.'^'.$servicetaxname1 .'^'. $servicetax2.'^'.''.'^'.''.'^'.$netamount ."^" . $signature.$itemdetail3;
                					}
                					
                				}
				   
				   //Ends
				}
												
				$overallcontent .= $customerdetails.chr(13).$contactarray.chr(13).$invoiceheader.chr(13).$itemdetails.$invoicefooter.chr(13);
				
				
				//$overallcontent .=  "DESC" . $description;
				
				
				
			}//while loop end
		}//if loop end
		 
		 $values = $overallcontent;
		
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "bhumika" || $_SERVER['HTTP_HOST'] == "192.168.2.79")
			$addstring = "/rwm/saralimax-user";
		$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
		$fetchres = runmysqlqueryfetch($query);
		$localdate = datetimelocal('Ymd');
		$localtime = datetimelocal('His');
		$filebasename = "Invoiceimport".$localdate."-".$localtime."-".strtolower($fetchres['username']).".txt";
		
		$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_invoiceimport_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
		$result = runmysqlquery($query1);
		
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
		
		$fp = fopen($filepath, "w") ; 
		$numBytes = fwrite($fp, $values); 
		if($fp)
		{
			$line = fgets($fp);
			downloadfile($filepath);
			fclose($fp);
		} 
	}
	
		
}
function contactdetails($customerid)
{
	$contactdetails ="";
	$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".substr($customerid,15,5)."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		if($valuecount > 0)
		{
			$contactarray .= ''.chr(13);
		}
		$selectiontype = $fetchres['selectiontype'];
		$contactperson = $fetchres['contactperson'];
		$phone = $fetchres['phone'];
		$cell = $fetchres['cell'];
		$emailid = $fetchres['emailid'];
		$slno = $fetchres['slno'];
		$custid = $fetchres['customerid'];
		$contactarray .= 'ContactDetails'.'^'.$custid.'^'.$selectiontype.'^'.$contactperson.'^'.$phone.'^'.$cell.'^'.$emailid.'';
		$valuecount++;
	}
	$contactdetails = $contactarray;
	return $contactdetails;
}

function customerdetails($customerid)
{
	$customerdetails ="";
	
	$query12 ="SELECT inv_mas_customer.slno, inv_mas_customer.customerid as customerid,inv_mas_customer.gst_no as gst_no, inv_mas_customer.businessname as businessname,inv_mas_customer.address as address, inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_customer.website as website,inv_mas_district.districtname as district,inv_mas_state.statename as state FROM inv_mas_customer Left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno ='".substr($customerid,15,5)."'";
	$fetchresult12 = runmysqlqueryfetch($query12);
	$addr = $fetchresult12['address'];
	$addre = str_replace('"','', $addr);
	$split=explode(" ",$addre);
	$counter=count($split)-1;
	$char_counter=0;
	$split_addr="";
	$y=0;
	for($i=0; $i<=$counter;$i++)
	{
		$char_counter= $char_counter + strlen($split[$i])+1;
		if($char_counter>50)
		{
			$char_counter=strlen($split[$i]) +1 ;
			$y++;
			$split_addr[$y] = $split_addr[$y] . " " .$split[$i];
		}
		else
		{
			$split_addr[$y] = $split_addr[$y] . " " .$split[$i]; 
		}
	}
	if($split_addr[0]<> "" || $split_addr[0] <> NULL)
	{ $adds = $split_addr[0]; }				
	
	if($split_addr[1]!= "" || $split_addr[1] != NULL)
	{ $adds1 =  $split_addr[1]; }else{ $adds1 = ""; }
	
	if($split_addr[2]!= "" || $split_addr[2] != NULL)
	{ $adds2 = $split_addr[2]; }else{ $adds2 =""; }
	
	$custdetails = 'CutomerDetails'.'^'.$customerid.'^'.$fetchresult12['businessname'].'^'.$adds.'^'.$adds1.'^'.$adds2.'^'.$fetchresult12['place'].'^'.$fetchresult12['pincode'].'^'.$fetchresult12['state'].'^'.'India'.'^'.$fetchresult12['website'].'^'.$fetchresult12['gst_no'];
	$customerdetails = $custdetails;
	
	return $customerdetails;
}

?>