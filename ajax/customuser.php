<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];


if(imaxgetcookie('dealeruserid') <> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

$query = "select * from inv_mas_dealer where slno = '".$userid."';";
$resultfetch = runmysqlqueryfetch($query);
$loggedindealername = $resultfetch['businessname'];
$dealer_region = $resultfetch['businessname'];

switch($switchtype)
{
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$userid."';";

		$fetch = runmysqlqueryfetch($query);
		$relyonexecutive = $fetch['relyonexecutive'];
		$telecaller = $fetch['telecaller'];
		$branch = $fetch['branch'];
		$district = $fetch['district'];
		$state = $fetch['statecode'];
		$region = $fetch['region'];
		if($telecaller == 'yes')
		{

			$query = "select DISTINCT isac.customerid as customerid, imc.slno as slno,imc.businessname as businessname from inv_spp_amc_customers isac inner join inv_mas_customer imc on isac.customerid = imc.customerid where isac.branchid in (select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') ORDER BY isac.businessname LIMIT ".$startindex.",".$limit.";";

			$result = runmysqlquery($query);

			if(mysqli_num_rows($result) == 0)
			{

			$query = "select DISTINCT isac.customerid as customerid, imc.slno as slno,imc.businessname as businessname from inv_spp_amc_customers isac inner join inv_mas_customer imc on isac.customerid = imc.customerid where isac.regionid = '2' AND isac.dealerid = '".$userid."' order by isac.businessname LIMIT ".$startindex.",".$limit.";";

				/* $query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '2' order by businessname LIMIT ".$startindex.",".$limit.";"; */

				$result = runmysqlquery($query);
			}
		}
		else
		{
			if($relyonexecutive == 'no')
			{

			$query = "select DISTINCT isac.customerid as customerid, imc.slno as slno,imc.businessname as businessname from inv_spp_amc_customers isac inner join inv_mas_customer imc on isac.customerid = imc.customerid where isac.dealerid = '".$userid."' order by isac.businessname LIMIT ".$startindex.",".$limit.";";

				/* $query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname LIMIT ".$startindex.",".$limit.";"; */

				$result = runmysqlquery($query);
				
//Adding query

    if(mysqli_num_rows($result) == 0)
    {

      $query = "select DISTINCT isac.customerid as customerid, imc.slno as slno,imc.businessname as businessname from inv_spp_amc_customers isac inner join inv_mas_customer imc on isac.customerid = imc.customerid where isac.regionid = '".$region."' order by isac.businessname LIMIT ".$startindex.",".$limit.";";
      
        $result = runmysqlquery($query);
    }
   
//Query ends
				
			}
			else
			{
				if(($region == '1') || ($region == '3'))
				{

						$query = "select DISTINCT isac.customerid as customerid, imc.slno as slno,imc.businessname as businessname from inv_spp_amc_customers isac inner join inv_mas_customer imc on isac.customerid = imc.customerid where (imc.region = '1' or imc.region = '3') AND  isac.dealerid = '".$userid."' order by isac.businessname LIMIT ".$startindex.",".$limit.";";

					/* $query = "select slno as slno, businessname as businessname from inv_mas_customer where (inv_mas_customer.region = '1' or inv_mas_customer.region = '3') order by businessname LIMIT ".$startindex.",".$limit.";"; */
					$result = runmysqlquery($query);
				}
				else
				{					
				
					//Added query

                    $query ="SELECT DISTINCT isac.customerid as customerid, imc.slno as slno,imc.businessname as businessname from inv_spp_amc_customers isac inner join inv_mas_customer imc on isac.customerid = imc.customerid where isac.dealerid='".$userid."' LIMIT ".$startindex.",".$limit.";";
                    $result = runmysqlquery($query);
                                  
                      if(mysqli_num_rows($result) == 0)
                      {

                                  //Ends Query
				
/*$query = "select DISTINCT isac.customerid as customerid, isac.businessname as businessname,isac.slno as slno from inv_spp_amc_customers isac where isac.dealerid = '".$userid."' order by isac.businessname LIMIT ".$startindex.",".$limit.";";*/


    $query = "select DISTINCT isac.customerid as customerid, imc.slno as slno,imc.businessname as businessname from inv_spp_amc_customers isac inner join inv_mas_customer imc on isac.customerid = imc.customerid where isac.regionid = '".$region."' order by isac.businessname LIMIT ".$startindex.",".$limit.";";

					/* $query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname LIMIT ".$startindex.",".$limit.";"; */

					$result = runmysqlquery($query);
                                
                    }
					
				}
			}
		}
		//echo $query;
		$customerarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($customerarray));
		
	}
	break;
	case 'save':
	{
		//$uamcamt = $_POST['uamcamt'];
		 $amc_slno = $_POST['amc_slno'];
                 $dealid = $_POST['dealerid'];
		 $invoicelastslno = $_POST['invoicelastslno'];
                  
		 $invoiceremarks = $_POST['invoiceremarks'];
                 $updationprice = $_POST['updationprice'];
                 $briefdescription = $_POST['briefdescription'];
                 $amcemailid = $_POST['amcemailid'];

                 $itembriefdescription = $_POST['itembriefdescription'];
                 $servicetype = $_POST['servicetype'];
                  
                     $productnametypes = $_POST['productnametypes'];
                     $usagetypes = $_POST['usagetypes'];
                     $updationprice_new = $_POST['updationprice_new'];
                     $briefdescription_new = $_POST['briefdescription_new'];
                     $servicenametypes = $_POST['servicenametypes'];
                     $servicetype_new = $_POST['servicetype_new'];
                     $itembriefdescription_new = $_POST['itembriefdescription_new'];
                    
                     $mobile=$_POST['mobile'];
                     $desktop=$_POST['desktop'];
//Fetching dealer name 

              $query_dealerdetail= "SELECT businessname FROM `inv_mas_dealer` WHERE slno = '".$dealid."'";
              $result_dealerdetails = runmysqlquery($query_dealerdetail);
              $fetchdealerdet = mysqli_fetch_array($result_dealerdetails);
              $dealerval = $fetchdealerdet['businessname'];

//End of fetching dealer name

//updating moblie/desktop in amc_customer table

$amc_query="UPDATE inv_spp_amc_customers SET m_publishpinv=$mobile,d_publishpinv=$desktop,emailid ='".$amcemailid."',dealerid ='".$dealid."' WHERE slno='$amc_slno'";

runmysqlquery($amc_query);




//updating pinv table

$select = "select description,customerid,seztaxtype,servicedescription,servicetype,serviceamount,products,productquantity,itembriefdescription,productbriefdescription from inv_spp_amc_pinv where slno =".$invoicelastslno;
$result_select = runmysqlqueryfetch($select);

$totalamount_pinv = 0;

                     $res_sel_des = $result_select['description'];
                     $res_sel_servdes = $result_select['servicedescription'];
                     $res_sel_servtype = $result_select['servicetype'];
                     $res_sel_products = $result_select['products'];
                     $res_sel_productquantity = $result_select['productquantity'];
                     $res_sel_servamount = $result_select['serviceamount'];
                     $res_sel_itembriefdescription = $result_select['itembriefdescription'];
                     $res_sel_productbriefdescription = $result_select['productbriefdescription'];

					 //for GST
                     $res_sel_customerid = $result_select['customerid'];
                     $res_sel_seztaxtype = $result_select['seztaxtype'];
                     //Ends GST
					 
                     $res_sel_totalproductpricearray = $result_select['totalproductpricearray'];

$string_add_desc = $string_add_descriptions = $string_add_amount = $pro_briefdescription = $item_briefdescription = $item_briefdescription_new = '';

$res_sel_servamounts_split = explode('~',$res_sel_servamount);
$res_sel_servdes_split = explode('*',$res_sel_servdes);

$res_sel_itembriefdescription_split = explode('*',$res_sel_itembriefdescription);
$res_sel_productbriefdescription_split = explode('*',$res_sel_productbriefdescription);

$servicetype = explode(',',$servicetype);
$updationprice = explode(',',$updationprice);
$briefdescription = explode(',',$briefdescription);
$itemsbriefdescription = explode(',',$itembriefdescription);

$productnametypes = explode(',',$productnametypes);
$usagetypes = explode(',',$usagetypes);
$updationprice_new = explode(',',$updationprice_new);
$briefdescription_new = explode(',',$briefdescription_new);
$servicenametypes = explode(',',$servicenametypes);
$servicetype_new = explode(',',$servicetype_new);
$itembriefdescription_new = explode(',',$itembriefdescription_new);


foreach ($briefdescription as $value) {
    $pro_briefdescription .= $value."#";
}

foreach ($itemsbriefdescription as $values) {
    $item_briefdescription .= $values."#";
}

foreach ($itembriefdescription_new as $vals) {
  $item_briefdescription_new .=$vals."#";
}


$maxloops = count($res_sel_servamounts_split);
$new_serv_counter = 1;
for($counters=0;$counters<$maxloops;$counters++) {   
 
$res_sel_servdes_splits = explode('$',$res_sel_servdes_split[$counters]);
$res_sel_servdes_splits[2] = $servicetype[$counters];

$totalamount_pinv = round($totalamount_pinv + $servicetype[$counters]);

$string_add_desc .= $res_sel_servdes_splits[0]."$".$res_sel_servdes_splits[1]."$".$res_sel_servdes_splits[2]."*";
$string_add_amount .= $res_sel_servdes_splits[2]."~";

$update_isacs = "UPDATE inv_spp_amc_customers_service SET new_service_amount ='".$servicetype[$counters]."' 
WHERE ispac_id= '".$amc_slno."' AND servicetype = '".$res_sel_servdes_splits[1]."'";
              $result_update_isacs = runmysqlquery($update_isacs);

$new_serv_counter++;
}

 

$descriptionsplit = explode('*',$res_sel_des);
$maxloop = count($descriptionsplit);

$count_servdes = count($res_sel_servdes_split)+$maxloop+1;

$new_pro_counter = 1;
	for($counter=0;$counter<$maxloop;$counter++)
	{
               $descriptionline_splits = explode('$',$descriptionsplit[$counter]);
               $descriptionline_splits[6] = $updationprice[$counter];

$totalamount_pinv = round($totalamount_pinv + $updationprice[$counter]);

$string_add_descriptions .= $descriptionline_splits[0]."$".$descriptionline_splits[1]."$".$descriptionline_splits[2]."$".$descriptionline_splits[3]."$".$descriptionline_splits[4]."$".$descriptionline_splits[5]."$".$descriptionline_splits[6]."*";


$update_isacp = "UPDATE inv_spp_amc_customers_purchase SET new_amount ='".$descriptionline_splits[6]."' 
WHERE slno = '".$amc_slno."' AND pinno = '".$descriptionline_splits[5]."'";
              $result_update_isacp = runmysqlquery($update_isacp);
$new_pro_counter++;

        }


$maxloops_products = count($productnametypes);

for($maxloops_products_counter=0;$maxloops_products_counter<$maxloops_products;$maxloops_products_counter++) { 
    if($productnametypes[$maxloops_products_counter] != '' && $updationprice_new[$maxloops_products_counter] != '')
    {

$products_part = "select productname, year from inv_mas_product where productcode = '".$productnametypes[$maxloops_products_counter]."';";
$result_products_part = runmysqlqueryfetch($products_part);

                     $res_sel_products_part = $result_products_part['productname'];
                     $res_sel_products_year = $result_products_part['year'];

                     $products_dat = $res_sel_products_part." (".$res_sel_products_year." ) ";


$pro_briefdescription .= $briefdescription_new[$maxloops_products_counter]."#";

if($res_sel_productbriefdescription !== '')
{
  $res_sel_productbriefdescription .= $briefdescription_new[$maxloops_products_counter]."#";
}
else
{
  $res_sel_productbriefdescription .= "#".$briefdescription_new[$maxloops_products_counter];
}

$res_sel_productquantity =  $res_sel_productquantity+1;
$res_sel_products .= "#".$productnametypes[$maxloops_products_counter]; 
$res_sel_totalproductpricearray +=  $updationprice_new[$maxloops_products_counter];
$totalamount_pinv += $updationprice_new[$maxloops_products_counter];   

$string_add_descriptions .= 
$new_pro_counter."$".$products_dat."\$Updation\$".$usagetypes[$maxloops_products_counter]."$ $ $".$updationprice_new[$maxloops_products_counter]."*";

$new_pro_counter++;

    }
}

$maxloops_service = count($servicenametypes);

for($maxloops_service_counter=0;$maxloops_service_counter<$maxloops_service;$maxloops_service_counter++) 
{ 

    if($servicenametypes[$maxloops_service_counter] != '' && $servicetype_new[$maxloops_service_counter] != '')
    {

$services_part = "select servicename from inv_mas_service where slno = '".$servicenametypes[$maxloops_service_counter]."';";
$result_services_part = runmysqlqueryfetch($services_part);

        $res_sel_services_part = $result_services_part['servicename'];
        $string_add_desc .= $count_servdes."$".$res_sel_services_part."$".$servicetype_new[$maxloops_service_counter]."*";
        $string_add_amount .= $servicetype_new[$maxloops_service_counter]."~"; 

        $res_sel_servtype .= "#".$res_sel_services_part;
        $totalamount_pinv += $servicetype_new[$maxloops_service_counter];  
        $new_pro_counter++;$count_servdes++;

    }
}

if($res_sel_itembriefdescription != '')
{
        $res_sel_itembriefdescription .= $itembriefdescription_new."#";
}
else
{
        $res_sel_itembriefdescription .= "#".$itembriefdescription_new;
}

$string_add_desc = substr($string_add_desc,0,-1);  
$string_add_amount  = substr($string_add_amount, 0,-1);  
$string_add_descriptions =  substr($string_add_descriptions, 0,-1);  
$pro_briefdescription = substr($pro_briefdescription, 0,-1); 
//$item_briefdescription = substr($item_briefdescription, 0,-1); 
//$item_briefdescription_new = substr($item_briefdescription_new, 0,-1); 
$item_briefdescription.= $item_briefdescription_new;

if($res_sel_productbriefdescription != '')
{
  $res_sel_productbriefdescription = substr($res_sel_productbriefdescription, 0,-1); 
}
 if($res_sel_itembriefdescription != '')
{
  $res_sel_itembriefdescription = substr($res_sel_itembriefdescription, 0,-1); 
}


$amount = $totalamount_pinv;
/*
$kktax_pinv = round($amount * 0.005); 
$sbtax_pinv = round($amount * 0.005); 
$actualtax_pinv = round($amount * 0.14); 

$amountinwords = convert_number($amount);

$pinv_tax_amount = $kktax_pinv + $sbtax_pinv + $actualtax_pinv;
$netamount_pinv = round($amount + $pinv_tax_amount); 
*/


//added for GST Calculation 

		$gst_date = date('Y-m-d');
		$gst_tax_date = strtotime('2017-07-01');
		
		
		//gst rate fetching
		
		$gst_tax_query= "select igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '$gst_date' AND to_date >= '$gst_date'";
		$gst_tax_result = runmysqlqueryfetch($gst_tax_query);
		$igst_tax_rate = $gst_tax_result['igst_rate'];
		$cgst_tax_rate = $gst_tax_result['cgst_rate'];
		$sgst_tax_rate = $gst_tax_result['sgst_rate'];
		
		//gst rate fetching ends
		/*----------------------------*/
       
        $search_customer =  str_replace("-","",$res_sel_customerid);
        $customer_details = "select inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,
        inv_mas_district.statecode as state_code,inv_mas_state.statename as statename
        ,inv_mas_state.state_gst_code as state_gst_code from inv_mas_customer 
        left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
        left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
        where inv_mas_customer.customerid like '%".$search_customer."%'";

        $fetch_customer_details = runmysqlqueryfetch($customer_details);		
			
		if($res_sel_seztaxtype == 'yes' || $fetch_customer_details['sez_enabled'] == 'yes')
		{
			//$sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
			
			$igst_tax_amount = '0.00';
			$cgst_tax_amount = '0.00';
			$sgst_tax_amount = '0.00';

		}
		else
		{
            if($fetch_customer_details['state_gst_code'] == '29')
			{
			    $sgst_tax_amount = roundnearestvalue($amount * ($sgst_tax_rate/100));
			    $cgst_tax_amount = roundnearestvalue($amount * ($cgst_tax_rate/100));
			    
			    $sgst_tax_amount = sprintf('%0.2f', $sgst_tax_amount);
			    $cgst_tax_amount = sprintf('%0.2f', $cgst_tax_amount);
			    $igst_tax_amount = '0.00';
			    
			}
			else
			{
			    $igst_tax_amount = roundnearestvalue($amount * ($igst_tax_rate/100));
			    $igst_tax_amount = sprintf('%0.2f', $igst_tax_amount);
			    
			    $sgst_tax_amount = '0.00';
			    $cgst_tax_amount = '0.00';
			}
			
		}


$kktax_pinv = '0.00'; 
$sbtax_pinv = '0.00'; 
$actualtax_pinv = '0.00';

$amountinwords = convert_number($amount);

//$pinv_tax_amount = $kktax_pinv + $sbtax_pinv + $actualtax_pinv;
$pinv_tax_amount = $igst_tax_amount + $cgst_tax_amount + $sgst_tax_amount;
$netamount_pinv = round($amount + $pinv_tax_amount); 

//GST Calculation Ends

if($string_add_descriptions == '$$$$$$')
{
  $string_add_descriptions = '';
}

if($dealid == '')
{
  $update_inv = "update inv_spp_amc_pinv set serviceamount = '".$string_add_amount."', servicedescription = 
'".$string_add_desc."', emailid = '".$amcemailid."', amount= '".$amount."',servicetype = '".$res_sel_servtype."',productquantity = '".$res_sel_productquantity."',products = '".$res_sel_products."', servicetax = '".$actualtax_pinv."', amountinwords = '".$amountinwords."', totalproductpricearray = '".$res_sel_totalproductpricearray."',actualproductpricearray = '".$res_sel_totalproductpricearray."', igst = '".$igst_tax_amount."',cgst = '".$cgst_tax_amount."',sgst = '".$sgst_tax_amount."', kktax = '".$kktax_pinv."', netamount = '".$netamount_pinv."', sbtax = '".$sbtax_pinv."' , description = '".$string_add_descriptions."', invoiceremarks = '".$invoiceremarks."', productbriefdescription  = '".$res_sel_productbriefdescription."', itembriefdescription  = '".$item_briefdescription."', createddate  = NOW() where slno ='".$invoicelastslno."'";
 
}
else
{
  $update_inv = "update inv_spp_amc_pinv set serviceamount = '".$string_add_amount."', servicedescription = 
'".$string_add_desc."', emailid = '".$amcemailid."', amount= '".$amount."', servicetype = '".$res_sel_servtype."', amountinwords = '".$amountinwords."', totalproductpricearray = '".$res_sel_totalproductpricearray."',actualproductpricearray = '".$res_sel_totalproductpricearray."', productquantity = '".$res_sel_productquantity."',products = '".$res_sel_products."', servicetax = '".$actualtax_pinv."', igst = '".$igst_tax_amount."',cgst = '".$cgst_tax_amount."',sgst = '".$sgst_tax_amount."', kktax = '".$kktax_pinv."', netamount = '".$netamount_pinv."', sbtax = '".$sbtax_pinv."', description = '".$string_add_descriptions."', invoiceremarks = '".$invoiceremarks."', productbriefdescription  = '".$pro_briefdescription."', itembriefdescription  = '".$item_briefdescription."',dealername='".$dealerval."', dealerid ='".$dealid."', createddate  = NOW() where slno ='".$invoicelastslno."'";


}

if($res_sel_itembriefdescription !='' && $dealid != '')
{
  $update_invce = "update inv_spp_amc_pinv set itembriefdescription  = '".$res_sel_itembriefdescription."' where slno ='".$invoicelastslno."'";    
  $result_updateinvce = runmysqlquery($update_invce);//ends  
		
}
$filterservice = array_filter($servicenametypes);
$countservicetypes = count($filterservice);

 for($s = 0 ; $s< $countservicetypes;$s++)
 {
    
    $queryservicename = "SELECT servicename from inv_mas_service where slno ='".$filterservice[$s]."'";
    $fetchservname = runmysqlqueryfetch($queryservicename);
    $servicename = $fetchservname['servicename'];
    switch($servicename)
           {

             case 'AMC Charges':$filed="amccharges";break;
             case 'SPP Customization Updation':$filed="spp_customization_up";break;
             case 'Employee Information Portal Updation':$filed="eipupdation";break;
             case 'Employee Information Portal-AMC':$filed="eipamc";break;
             case 'SPP Customization':$filed="spp_customization";break;
             case 'Employee Information Portal(EIP-SPP)':$filed="eipspp";break;
             case 'AMC Charges-Add/on Module(ARE/AI/AM)':$filed="amcaddonmodule";break;
             case 'Web Hosting':$filed="webhosting";break;
             case 'Web Hosting Updation':$filed="webhostingupdation";break;
             case 'Employee Information Portal Mobile':$filed="eipmobile";break;
             default:$flg=1;


           }
          if($flg!=1)
         {
          $update_amc_service_flag_query="UPDATE inv_spp_amc_customers SET $filed=1 where slno='$amc_slno' ";
          runmysqlquery($update_amc_service_flag_query);
          //echo $update_amc_service_flag_query;
         }
   $insert_servicequery = "INSERT INTO inv_spp_amc_customers_service(`ispac_id`, `old_service_amount`, `new_service_amount`, `servicetype`) VALUES ('$amc_slno','$servicetype_new[$s]','$servicetype_new[$s]','$servicename')";
  $resultinsert = runmysqlquery($insert_servicequery);
}

		$result_update = runmysqlquery($update_inv);//ends  
		//$result = runmysqlquery($query);



                        $responsearray1['errorcode'] = "1";
			$responsearray1['errormessage'] = "Amc Updated Successfully";
			echo(json_encode($responsearray1));
	}
	break;
	case 'getcustomercount':
	{
		$responsearray3 = array();

		/*$query = "SELECT slno,businessname,customerid 
                          FROM inv_spp_amc_customers 
                          WHERE  dealerid = '".$userid."' ORDER BY businessname";*/
						  
		//Added query for total count

             $query ="SELECT DISTINCT customerid from inv_spp_amc_customers where dealerid='".$userid."'";
               
             $result = runmysqlquery($query);
                                  
             if(mysqli_num_rows($result) == 0)
             {
               $query = "SELECT DISTINCT isac.customerid as customerid from inv_spp_amc_customers isac inner join inv_mas_dealer imd on isac.regionid = imd.region where imd.businessname = '".$dealer_region."' order by isac.businessname";

        //Query ends for total count

 //$query = "SELECT slno,businessname,customerid FROM inv_spp_amc_customers WHERE regionid = '".$dealer_region."' ORDER BY businessname";
             }				  

		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;

case 'customerdetailstoform':
	{
		$customerdetailstoformarray = array();

/*
$query = "SELECT slno,customerid,businessname FROM inv_mas_customer where slno = '".$lastslno."' OR customerid = '".$lastslno."';";

$fetch = runmysqlqueryfetch($query);
$ncustomerid = $fetch['customerid'];              
  */        
	     
		// Fetch Contact Details
		
		$querycontactdetails = "select slno,customerid,businessname,emailid,dealerid,branchid,regionid,invoiceno,invoice_date,renewal_date,m_publishpinv,d_publishpinv from inv_spp_amc_customers where customerid like '%".$lastslno."'";
		$resultcontactdetails = runmysqlquery($querycontactdetails);

$invoices_list = '';
				
		while($fetchcontactdetails = mysqli_fetch_array($resultcontactdetails))
		{
			$customerid = $fetchcontactdetails['customerid'];
			$dealerid = $fetchcontactdetails['dealerid'];
			$branchid = $fetchcontactdetails['branchid'];
			$regionid = $fetchcontactdetails['regionid'];
			$invoiceno = $fetchcontactdetails['invoiceno'];
                        $invnumber = $fetchcontactdetails['invoiceno'];
			$pinv_slno = $fetchcontactdetails['slno'];
                        $businessname = $fetchcontactdetails['businessname'];
                        $amcemailid = $fetchcontactdetails['emailid'];
                        $invoicedate = changedateformat($fetchcontactdetails['invoice_date']);
                        $renewaldate = changedateformat($fetchcontactdetails['renewal_date']);	
                 
                     $invoices_list .= $invoiceno.',';	
$enable_mobile=$fetchcontactdetails['m_publishpinv'];
                $enable_desktop=$fetchcontactdetails['d_publishpinv'];	
		}


//Fetch mail details
                    $grid1 = "<table width='100%' cellpadding='3' cellspacing='0' class='table-border-grid'  style='border-bottom:none;'>
                                               <tr class='tr-grid-header'>
                                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>Slno</td>
                                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>Sent By</td>
                                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>Sent Date</td>
                                                 
                                               </tr>";
                    $mailcount = 1;
                     $mail_query ="SELECT isam.maildate,isam.sentby FROM inv_spp_amc_mailer isam inner join inv_spp_amc_pinv isap ON isam.isap_id = isap.slno WHERE RIGHT(isap.customerid,5) = '".$lastslno."'";
                     
                      $resultmailquery = runmysqlquery($mail_query);
                      
                     while ($fetchmaildata = mysqli_fetch_assoc($resultmailquery))
                     {
	             $grid1.= "<tr >
                                 
                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>".$mailcount."</td>
                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>".$fetchmaildata['sentby']."</td>
                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>".$fetchmaildata['maildate']."</td></tr>";  
                            $mailcount++;
                     
                       }
	             $grid1.= "</table>";
                     
// fetch mail ends


           //Fetching dealer name 

              $dealerdetail= "SELECT businessname FROM `inv_mas_dealer` WHERE slno = '".$dealerid."'";
              $resultdealerdetail = runmysqlquery($dealerdetail);
              $fetchdealerdetail = mysqli_fetch_array($resultdealerdetail);
              $dealername = $fetchdealerdetail['businessname'];

          //End of fetching dealer name

		$queryinvserial = "select inv_invoicenumbers.slno as slno from inv_invoicenumbers where invoiceno ='".$invoiceno."'";
	        $resultinvserial = runmysqlquery($queryinvserial);

		while($fetchinvserial = mysqli_fetch_array($resultinvserial))
		{
			$invserial = $fetchinvserial ['slno'];
                }

                  //$newstring = substr($ncustomerid, -5);                  

		//$querypinvserial = "select inv_spp_amc_pinv.slno as slno from inv_spp_amc_pinv where invoiceno ='".$invoiceno."'";

 $querypinvserial = "select inv_spp_amc_pinv.slno as slno, description, servicedescription, products, servicetype,serviceamount,productbriefdescription,itembriefdescription from inv_spp_amc_pinv where RIGHT(customerid,5) ='".$lastslno."'";

	        $resultpinvserial = runmysqlquery($querypinvserial);

		while($fetchpinvserial = mysqli_fetch_array($resultpinvserial))
		{
			$invpserial = $fetchpinvserial ['slno'];
			$description = $fetchpinvserial['description'];
			$servicedescription = $fetchpinvserial['servicedescription'];
			$products = $fetchpinvserial['products'];
			$servicetype = $fetchpinvserial['servicetype'];
			$serviceamount = $fetchpinvserial['serviceamount'];
			$productbriefdescription = $fetchpinvserial['productbriefdescription'];
			$itembriefdescription = $fetchpinvserial['itembriefdescription'];
                }

                $customerid = cusidcombine($customerid);
		$queryinvserial_remarks = "select invoiceremarks from inv_spp_amc_pinv where slno ='".$invpserial."'";
	        $resultinvserial_remarks = runmysqlquery($queryinvserial_remarks);

		while($fetchinvserial_remarks = mysqli_fetch_array($resultinvserial_remarks))
		{
		 $invoiceremarks = $fetchinvserial_remarks['invoiceremarks'];
                }
		//mobile and desktop data fetch
               
                
                //echo json_encode($enable_mobile);
                $invoices_list = substr($invoices_list, 0,-1); 

		$customerdetailstoformarray['errorcode'] = '1';	
		$customerdetailstoformarray['slno'] = $fetch['slno'];
		$customerdetailstoformarray['customerid'] = $customerid;
		$customerdetailstoformarray['companyname'] = $businessname;

		$customerdetailstoformarray['invserial'] = $invserial;
		$customerdetailstoformarray['invpserial'] = $invpserial;
		$customerdetailstoformarray['description'] = $description;
		$customerdetailstoformarray['servicedescription'] = $servicedescription;
		$customerdetailstoformarray['products'] = $products;
		$customerdetailstoformarray['servicetype'] = $servicetype;
		$customerdetailstoformarray['serviceamount'] = $serviceamount;
		$customerdetailstoformarray['invoiceremarks'] = $invoiceremarks;
		$customerdetailstoformarray['productbriefdescription'] = $productbriefdescription;
		$customerdetailstoformarray['itembriefdescription'] = $itembriefdescription;

		$customerdetailstoformarray['invoiceno'] = $invoiceno;
		$customerdetailstoformarray['dealerid'] = $dealerid;
                $customerdetailstoformarray['dealername'] = $dealername;
		$customerdetailstoformarray['branchid'] = $branchid;
		$customerdetailstoformarray['regionid'] = $regionid;
		$customerdetailstoformarray['invoiceno'] = $invoiceno;
		$customerdetailstoformarray['inv_total_amount'] = $inv_total_amount;
		$customerdetailstoformarray['p_amc_amount'] = $p_amc_amount;
		$customerdetailstoformarray['edit_amc_amount'] = $edit_amc_amount;
		$customerdetailstoformarray['amc_slno'] = $pinv_slno;
                $customerdetailstoformarray['amount'] = $amount;
                $customerdetailstoformarray['f_amount'] = $f_amount;
                $customerdetailstoformarray['amc_edited'] = $amc_edited;
//$customerdetailstoformarray['mail_count'] = $mailscounter;
                $customerdetailstoformarray['invoicedate'] = $invoicedate;
                $customerdetailstoformarray['renewaldate'] = $renewaldate;
                $customerdetailstoformarray['amcemailid'] = $amcemailid;
                $customerdetailstoformarray['invoices_list'] = $invoices_list;
                $customerdetailstoformarray['e_mobile'] = $enable_mobile;
                $customerdetailstoformarray['invnumber'] = $invnumber;
                $customerdetailstoformarray['e_desktop'] = $enable_desktop;
                $customerdetailstoformarray['mail'] = $grid1;
                $customerdetailstoformarray['invoice'] = $grid2;
		echo(json_encode($customerdetailstoformarray));
        }	

	break;

//customerdetails to form invoice



case 'customerdetailstoforminvoice':
	{
		$customerdetailstoforminvoice = array();

		$querycontactdetail = "select slno,customerid from inv_spp_amc_customers where invoiceno ='".$lastslno."'";
            $resultcontactdetail = runmysqlquery($querycontactdetail);
            $fetchcontactdetail = mysqli_fetch_array($resultcontactdetail);
            $customerdetailid = $fetchcontactdetail['customerid'];
            $customerdetailslno = $fetchcontactdetail['slno'];

$querycontactdetails = "select slno,customerid,businessname,emailid,dealerid,branchid,regionid,invoiceno,invoice_date,renewal_date,m_publishpinv,d_publishpinv from inv_spp_amc_customers where customerid ='".$customerdetailid."'";

		$resultcontactdetails = runmysqlquery($querycontactdetails);

$invoices_list = '';
				
		while($fetchcontactdetails = mysqli_fetch_array($resultcontactdetails))
		{
			$customerid = $fetchcontactdetails['customerid'];
$businessname = $fetchcontactdetails['businessname'];
			$dealerid = $fetchcontactdetails['dealerid'];
			$branchid = $fetchcontactdetails['branchid'];
			$regionid = $fetchcontactdetails['regionid'];
			$invoiceno = $fetchcontactdetails['invoiceno'];
			$pinv_slno = $fetchcontactdetails['slno'];
                        $amcemailid = $fetchcontactdetails['emailid'];
                        $invoicedate = changedateformat($fetchcontactdetails['invoice_date']);
                        $renewaldate = changedateformat($fetchcontactdetails['renewal_date']);	

                     $invoices_list .= $invoiceno.',';		
		} 


/*
$query = "SELECT slno,customerid,businessname FROM inv_mas_customer where customerid = '".$customerid."';";
$fetch = runmysqlqueryfetch($query);
$ncustomerid = $fetch['customerid'];
 */            
		// Fetch Contact Details

//Fetch mail details
                    $grid1 = "<table width='100%' cellpadding='3' cellspacing='0' class='table-border-grid'  style='border-bottom:none;'>
                                               <tr class='tr-grid-header'>
                                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>Slno</td>
                                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>Sent By</td>
                                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>Sent Date</td>
                                                 
                                               </tr>";
                    $mailcount = 1;
                     $mail_query ="SELECT isam.maildate,isam.sentby FROM inv_spp_amc_mailer isam inner join inv_spp_amc_pinv isap ON isam.isap_id = isap.slno WHERE isap.invoiceno = '".$lastslno."'";
                     
                      $resultmailquery = runmysqlquery($mail_query);
                      
                     while ($fetchmaildata = mysqli_fetch_assoc($resultmailquery))
                     {
	             $grid1.= "<tr >
                                 
                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>".$mailcount."</td>
                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>".$fetchmaildata['sentby']."</td>
                                 <td nowrap = 'nowrap' class='td-border-grid' align='left'>".$fetchmaildata['maildate']."</td></tr>";  
                            $mailcount++;
                     
                       }
	             $grid1.= "</table>";
                     
                         
// fetch mail ends
		

           //Fetching dealer name 

              $dealerdetail= "SELECT businessname FROM `inv_mas_dealer` WHERE slno = '".$dealerid."'";
              $resultdealerdetail = runmysqlquery($dealerdetail);
              $fetchdealerdetail = mysqli_fetch_array($resultdealerdetail);
              $dealername = $fetchdealerdetail['businessname'];

          //End of fetching dealer name

		$queryinvserial = "select inv_invoicenumbers.slno as slno from inv_invoicenumbers where invoiceno ='".$lastslno."'";
	        $resultinvserial = runmysqlquery($queryinvserial);

		while($fetchinvserial = mysqli_fetch_array($resultinvserial))
		{
			$invserial = $fetchinvserial ['slno'];
                }

                  $newstring = substr($ncustomerid, -5);                  

$querypinvserial = "select inv_spp_amc_pinv.slno as slno, description, servicedescription, products, servicetype,serviceamount,productbriefdescription,itembriefdescription from inv_spp_amc_pinv where invoiceno ='".$lastslno."'";

	        $resultpinvserial = runmysqlquery($querypinvserial);

		while($fetchpinvserial = mysqli_fetch_array($resultpinvserial))
		{
			$invpserial = $fetchpinvserial ['slno'];
			$description = $fetchpinvserial['description'];
			$servicedescription = $fetchpinvserial['servicedescription'];
			$products = $fetchpinvserial['products'];
			$servicetype = $fetchpinvserial['servicetype'];
			$serviceamount = $fetchpinvserial['serviceamount'];
			$productbriefdescription = $fetchpinvserial['productbriefdescription'];
			$itembriefdescription = $fetchpinvserial['itembriefdescription'];
                }

                $customerid = cusidcombine($customerid);
		$queryinvserial_remarks = "select invoiceremarks from inv_spp_amc_pinv where slno ='".$invpserial."'";
	        $resultinvserial_remarks = runmysqlquery($queryinvserial_remarks);

		while($fetchinvserial_remarks = mysqli_fetch_array($resultinvserial_remarks))
		{
		 $invoiceremarks = $fetchinvserial_remarks['invoiceremarks'];
                }
		

                $invoices_list = substr($invoices_list, 0,-1); 
                $invnumber = $lastslno;

		$customerdetailstoforminvoicearray['errorcode'] = '1';	
		$customerdetailstoforminvoicearray['slno'] = $fetch['slno'];
		$customerdetailstoforminvoicearray['customerid'] = $customerid;
		$customerdetailstoforminvoicearray['companyname'] = $businessname;

		$customerdetailstoforminvoicearray['invserial'] = $invserial;
		$customerdetailstoforminvoicearray['invpserial'] = $invpserial;
		$customerdetailstoforminvoicearray['description'] = $description;
		$customerdetailstoforminvoicearray['servicedescription'] = $servicedescription;
		$customerdetailstoforminvoicearray['products'] = $products;
		$customerdetailstoforminvoicearray['servicetype'] = $servicetype;
		$customerdetailstoforminvoicearray['serviceamount'] = $serviceamount;
		$customerdetailstoforminvoicearray['invoiceremarks'] = $invoiceremarks;
		$customerdetailstoforminvoicearray['productbriefdescription'] = $productbriefdescription;
		$customerdetailstoforminvoicearray['itembriefdescription'] = $itembriefdescription;

		$customerdetailstoforminvoicearray['invoiceno'] = $invoiceno;
		$customerdetailstoforminvoicearray['dealerid'] = $dealerid;
                $customerdetailstoforminvoicearray['dealername'] = $dealername;
		$customerdetailstoforminvoicearray['branchid'] = $branchid;
		$customerdetailstoforminvoicearray['regionid'] = $regionid;
		$customerdetailstoforminvoicearray['invoiceno'] = $invoiceno;
		$customerdetailstoforminvoicearray['inv_total_amount'] = $inv_total_amount;
		$customerdetailstoforminvoicearray['p_amc_amount'] = $p_amc_amount;
		$customerdetailstoforminvoicearray['edit_amc_amount'] = $edit_amc_amount;
		$customerdetailstoforminvoicearray['amc_slno'] = $pinv_slno;
                $customerdetailstoforminvoicearray['amount'] = $amount;
                $customerdetailstoforminvoicearray['f_amount'] = $f_amount;
                $customerdetailstoforminvoicearray['amc_edited'] = $amc_edited;
//$customerdetailstoforminvoicearray['mail_count'] = $mailscounter;
                $customerdetailstoforminvoicearray['invoicedate'] = $invoicedate;
                $customerdetailstoforminvoicearray['invnumber'] = $invnumber;
                $customerdetailstoforminvoicearray['invoices_list'] = $invoices_list;
                $customerdetailstoforminvoicearray['mail'] = $grid1;
                $customerdetailstoforminvoicearray['invoice'] = $grid2;
                $customerdetailstoforminvoicearray['renewaldate'] = $renewaldate;
                $customerdetailstoforminvoicearray['amcemailid'] = $amcemailid;
		echo(json_encode($customerdetailstoforminvoicearray));
        }	

	break;



//customer details to form invoice ends

	case 'newupdationchange':
	{
		$customerlicenseid = $_POST['customerid'];
		//$productlicenceid = $_POST['productlicenceid'];
		$productname = $_POST['productname'];
		
		//$newString = preg_replace("/\d+$/","",$productname);
		//$newString = preg_replace("/[^A-Za-z- ]/",'',$productname);
		//echo $newString;
		
		//no need to change
		//$previousyear = "2014-15";
		
		$currentyearquery = "select year,subgroup from inv_mas_product where productname = '".$productname."' 
		order by year desc limit 1;";
		$currentyearfetch = runmysqlqueryfetch($currentyearquery);
		$currentyear = $currentyearfetch['year'];
		$subgroup = $currentyearfetch['subgroup'];
			//$currentyear = "2015-16";
		
		
		//query for taking 	current year updation card count	
		$newquery1 = "select count(inv_dealercard.purchasetype)as purchasetype from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$currentyear."' 
		and inv_dealercard.purchasetype = 'updation' order by year desc";
		$newfetch1 = runmysqlqueryfetch($newquery1);
		//$newfetch1 = mysqli_fetch_array($newresult1);
		$currentyearcard = $newfetch1['purchasetype'];
		
		//query for taking last two year 
		$yearquery = "select distinct(year) from inv_mas_product where year!= '".$currentyear."' 
		order by year desc limit 2;";
		$yearresult = runmysqlquery($yearquery);
		while($yearfetch = mysqli_fetch_array($yearresult))
		{
			 $yearcount[] = $yearfetch['year'];
		}
		
		//query for taking last two year count
		$totalcards = "";
	    $querychange1 = "select inv_mas_product.year from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year in 
		('".$yearcount[0]."','".$yearcount[1]."') order by inv_mas_product.year desc limit 1";
		$resultchange1 = runmysqlquery($querychange1);
		$count = mysqli_num_rows($resultchange1);
		
		
		if($count == 1)
		{
			$fetchchange1 = mysqli_fetch_array($resultchange1);
			$lasttwoyear = $fetchchange1['year'];

			//query for taking  card count based on last two year count
			$querychange2 = "select count(inv_dealercard.purchasetype) as purchasetype from inv_dealercard
			left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
			where inv_dealercard.customerreference = ".$customerlicenseid."
			and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$lasttwoyear."'";
			$fetchchange2 = runmysqlqueryfetch($querychange2);
			//$count2 = mysqli_num_rows($resultchange2);
			$totalcards = $fetchchange2['purchasetype'];
			
		}
			
		$custprodetails['totalcards'] = $totalcards;
		$custprodetails['lasttwoyearcount'] = $count;
		$custprodetails['currentyearcard'] = $currentyearcard;
		
		echo(json_encode($custprodetails));
	}
	break;
	case 'searchbycustomerid':
	{
		$searchbycustomeridarray = array();
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		$customerid = ($_POST['customerid'] == 5)?($_POST['customerid']):(substr($customerid, $customeridlen - 5));
		if($customeridlen == 5)
		{
		  $query1 = "SELECT count(*) as count from inv_mas_customer where slno = '".$customerid."'";
		  $fetch1 = runmysqlqueryfetch($query1);
		  
		  if($fetch1['count'] > 0)
		  {
			$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname,  inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region, inv_mas_customer.stdcode,  inv_mas_customer.activecustomer, inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.remarks, inv_mas_customer.currentdealer,  inv_mas_customer.initialpassword as password, inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder, inv_mas_customer.createddate,inv_mas_users.fullname, inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename  FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN  inv_mas_users ON  inv_mas_users.slno = inv_mas_customer.createdby left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode  left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$customerid."'";
			$fetch = runmysqlqueryfetch($query);
			
			// Fetch Contact Details 
			$querycontactdetails = "select phone,cell,emailid,contactperson from inv_contactdetails where customerid = '".$customerid."'";
			$resultcontactdetails = runmysqlquery($querycontactdetails);
			
			// contact Details
			$contactvalues = '';
			$phoneres = '';
			$cellres = '';
			$emailidres = '';
					
			while($fetchcontactdetails = mysqli_fetch_array($resultcontactdetails))
			{
				$contactperson = $fetchcontactdetails['contactperson'];
				$phone = $fetchcontactdetails['phone'];
				$cell = $fetchcontactdetails['cell'];
				$emailid = $fetchcontactdetails['emailid'];
				
				$contactvalues .= $contactperson;
				$contactvalues .= appendcomma($contactperson);
				$phoneres .= $phone;
				$phoneres .= appendcomma($phone);
				$cellres .= $cell;
				$cellres .= appendcomma($cell);
				$emailidres .= $emailid;
				$emailidres .= appendcomma($emailid);
			}
			$phonenumber = explode(',', trim($phoneres,','));
			$phone = $phonenumber[0];
			$cellnumber = explode(',', trim($cellres,','));
			$cell = $cellnumber[0];
			$emailid = explode(',', trim($emailidres,','));
			$emailidplit = $emailid[0];
			
			$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	
			if($fetch['currentdealer'] == '')
				$currentdealer = '';
			else
			{
				$query = "select * from inv_mas_dealer where slno = '".$fetch['currentdealer']."'";
				$resultfetch = runmysqlqueryfetch($query);
				$currentdealer = $resultfetch['businessname'];
			}
			
			$searchbycustomeridarray['errorcode'] = '1';
			$searchbycustomeridarray['slno'] = $fetch['slno'];
			$searchbycustomeridarray['customerid'] =cusidcombine( $fetch['customerid']);
			$searchbycustomeridarray['businessname'] = $fetch['businessname'];
			$searchbycustomeridarray['contactvalues'] = $contactvalues;
			$searchbycustomeridarray['address'] = $fetch['address'];
			$searchbycustomeridarray['district'] = $fetch['district'];
			$searchbycustomeridarray['state'] = $fetch['state'];
			$searchbycustomeridarray['pincode'] = $fetch['pincode'];
			$searchbycustomeridarray['stdcode'] = $fetch['stdcode'];
			$searchbycustomeridarray['phone'] = $phone;
			$searchbycustomeridarray['cell'] = $cell;
			$searchbycustomeridarray['emailidplit'] = $emailidplit;
			$searchbycustomeridarray['website'] = $fetch['website'];
			$searchbycustomeridarray['category'] = $fetch['category'];
			$searchbycustomeridarray['type'] = $fetch['type'];
			$searchbycustomeridarray['remarks'] = $fetch['remarks'];
			$searchbycustomeridarray['currentdealer'] = $currentdealer;
			$searchbycustomeridarray['disablelogin'] = $fetch['disablelogin'];
			$searchbycustomeridarray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$searchbycustomeridarray['corporateorder'] = strtolower($fetch['corporateorder']);			
			$searchbycustomeridarray['fax'] = $fetch['fax'];				
			$searchbycustomeridarray['userid'] = $userid;				
			$searchbycustomeridarray['activecustomer'] = $fetch['activecustomer'];
			$searchbycustomeridarray['districtname'] = $fetch['districtname'];
			$searchbycustomeridarray['statename'] = $fetch['statename'];
			$searchbycustomeridarray['password'] = $fetch['password'];
			$searchbycustomeridarray['passwordchanged'] = strtolower($fetch['passwordchanged']);
		  }
		  else
		  {
			  $searchbycustomeridarray['customerid'] = '';
		  }
			//	echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['businessname'].'^'.trim($contactvalues,',').'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$phone.'^'.$cell.'^'.$emailidplit.'^'.$fetch['website'].'^'.$fetch['category'].'^'.$fetch['type'].'^'.$fetch['remarks'].'^'.$currentdealer.'^'.$fetch['disablelogin'].'^'.changedateformatwithtime($fetch['createddate']).'^'.strtolower($fetch['corporateorder']).'^'.$fetch['fax'].'^'.$userid.'^'.''.'^'.$fetch['activecustomer'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['password'].'^'.strtolower($fetch['passwordchanged']));
		}

			echo(json_encode($searchbycustomeridarray));

	}
	break;

//Showing for advance search
        case 'advancesearchcustomerlist':
	{
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$district = $_POST['district'];
		$productlist = $_POST['product'];
		$branch = $_POST['branch2'];
		$region = $_POST['region'];
                $invoicefromdate = changedateformat($_POST['invoicefromdate']);
                $invoicetodate = changedateformat($_POST['invoicetodate']);
                $servicetypelist = $_POST['serviceflag'];
                $operatorr=$_POST['operatorr'];
                $mailcountsearch=$_POST['mailcountsearch'];
                
			//Added query
               
               $query_region = "SELECT region from inv_mas_dealer where slno = '".$userid."'";
               $result_region = runmysqlquery($query_region); 
               $fetch_region = mysqli_fetch_assoc($result_region);
               $dealer_region = $fetch_region['region'];
 
             //Query ends
				
				
 $query="SELECT pinv.customerid as cusidd,pinv.slno as slno,pinv.businessname as businessname,pinv.dealername as dealername,pinv.netamount as netamount,pinv.invoiceno as invoiceno ";

if($operatorr!='' && $mailcountsearch!='')
{
$query.=",count(isam.slno) as isam_slno";
}

$query.=" FROM inv_spp_amc_pinv pinv INNER JOIN inv_spp_amc_customers cus ON pinv.invoiceno=cus.invoiceno ";
     

   if($operatorr!='' && $mailcountsearch!='')
         {
    $query.=" left join `inv_spp_amc_mailer` isam on pinv.slno = isam.isap_id ";
         }


//Added query

    $query.= " where  pinv.products IN ($productlist)";

             $query_select ="SELECT slno from inv_spp_amc_customers where dealerid='".$userid."'";
               
             $result_select = runmysqlquery($query_select);
                                  
             if(mysqli_num_rows($result_select) == 0)
             {
                $query.= " AND cus.regionid='".$dealer_region."'";
             }
             else
             {
                $query.= " AND cus.dealerid = '".$userid."'";
             }

 //Query ends

//echo json_encode($query);
if($region!=""){$query.=" AND pinv.region='$region'";}
if($branch!=""){$query.=" AND pinv.branch='$branch'";}
if($dealer!=""){$query.=" AND pinv.dealername='$dealer'";}

if($invoicefromdate != "--" && $invoicetodate != "--"){$query.=" AND (cus.renewal_date >= '$invoicefromdate' AND cus.renewal_date <= '$invoicetodate')";}
             
        
        switch($databasefield)
        {
            case "slno":
                $customeridlen = strlen($textfield);
                $lastcustomerid = cusidsplit($textfield);

                if($customeridlen == 5)
                {
                    $query.= " AND pinv.customerid like '%$textfield'";
                }
                elseif($customeridlen > 5)
                {
                    $query.= " AND pinv.customerid = '$textfield'";
                }
                break;

            case "businessname":

                $query.= " AND pinv.businessname like '%$textfield%'";
                break;

                        case "emailid":

                $query.= " AND pinv.emailid like '%$textfield%'";
                break;

            default:

                $query.="";
                break;
        }




$servicetypelist.= ",0";      
$serviceexp = explode(',',$servicetypelist);
$serviceexp=array_filter($serviceexp);
$servicecount = count($serviceexp);
//echo $servicecount;
if($servicecount >1){ $query.= " AND(";  }
if($servicecount ==1){ $query.= " AND";  }

for($i=0; $i<$servicecount+1; $i++)
{
   if($i >=1 && $i != $servicecount)
   { $query.= "  OR";}
 
  switch($serviceexp[$i])
  {
    case '1': $query.= "  cus.amccharges = 1";
    break;
    case '2': $query.= "  cus.eipspp = 1";
    break;
    case '3': $query.= "  cus.eipamc = 1";
    break;
    case '4': $query.= "  cus.eipupdation = 1";
    break;
    case '5': $query.= "  cus.spp_customization_up = 1";
    break;
    case '6': $query.= "  cus.spp_customization = 1";
    break;
    case '7': $query.= "  cus.amcaddonmodule = 1";
    break;
    case '8': $query.= "  cus.webhostingupdation = 1";
    break;
    case '9': $query.= "  cus.webhosting = 1";
    break;
    case '10': $query.= "  cus.eipmobile = 1";
    break;  
    default:
      $query;
  }
}
if($servicecount >1){ $query.= " )";  }


  if($operatorr!='' && $mailcountsearch!='')
         {
          $query.="group by pinv.slno having isam_slno $operatorr $mailcountsearch";
         }

                 // echo $query;

			 $searchcustomerlistarray = array();	
		         $count = 0;
			 $result = runmysqlquery($query);
                         while($csdid = mysqli_fetch_assoc($result))
                         {
                          $custid = substr($csdid['cusidd'],-5); 
		          $searchcustomerlistarray[$count] = $csdid['businessname'].'^'.$custid;
		          $count++; 
                        }
		 echo(json_encode($searchcustomerlistarray));
		
	}
	break;
	    

//Advance search ends

        

        break;
	case 'calculateamount':
	{
		$selectedcookievalue = $_POST['selectedcookievalue'];
		$selectedcookievaluesplit = explode('#',$selectedcookievalue);
		$pricingtype = removedoublecomma($_POST['pricingtype']);
		$purchasevalues = removedoublecomma($_POST['purchasevalues']);
		$usagevalues = removedoublecomma($_POST['usagevalues']);
		$productamountvalues = removedoublecomma($_POST['productamountvalues']);
		$descriptiontypevalues = removedoublecomma($_POST['descriptiontypevalues']);
		$descriptionvalues = removedoublecomma($_POST['descriptionvalues']);
		$descriptionamountvalues = removedoublecomma($_POST['descriptionamountvalues']);
		$productquantityvalues = removedoublecomma($_POST['productquantityvalues']);
		$purchasevaluesplit = explode(',',$purchasevalues);
		$usagevaluesplit = explode(',',$usagevalues);
		$productamountsplit = explode(',',$productamountvalues);
		$productquantitysplit = explode(',',$productquantityvalues);
		$descriptionamountvaluesnew = str_replace(',','~',$descriptionamountvalues);
		$descriptiontypevaluesnew = str_replace(',','~',$descriptiontypevalues);
		$descamt = getdescriptionamount($descriptionamountvaluesnew,$descriptiontypevaluesnew);
		$seztaxtype = $_POST['seztaxtype'];
		$invoicedated = $_POST['invoicedated'];
		switch($pricingtype)
		{
			/*case 'normal':
			{
				$productcount = count($productamountsplit);
				$totalamount = 0;
				$servicetax = 0;
				$netamount = 0;*/
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2012-04-04');
				//$expirydate = strtotime('2015-06-01');
				/*if($invoicedated == 'yes')
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalamount * 0.103);
						$servicetaxdetails = roundnearest($totalamount * 0.1236);
					else
						$servicetaxdetails = roundnearest($totalamount * 0.14);
				}
				else
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalamount * 0.103);
						$servicetaxdetails = roundnearest($totalamount * 0.1236);
					else
						$servicetaxdetails = roundnearest($totalamount * 0.14);
				}*/
		        /*$servicetaxdetails = roundnearest($totalamount * 0.14);
				$sbtaxdetails = roundnearest($totalamount * 0.005);
				
				for($i=0;$i<$productcount; $i++)
				{
					$totalamount += $productamountsplit[$i];
				}
				$totalamount = $totalamount + $descamt;
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
				{
					$servicetax = $servicetaxdetails;
					$sbtax = $sbtaxdetails;
				}
				$netamount = $totalamount + $servicetax + $sbtax;
				$amount = '1^'.$totalamount.'^'.$servicetax.'^'.$netamount.'^'.$descamt;
			}
			break;*/
			case 'default':
			{
				$calculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount);
				$calculatedpricesplit = explode('$',$calculatedprice);
				$totalproductprice = $calculatedpricesplit[0];
				$productpricearray = $calculatedpricesplit[1];
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2012-04-04');
				//$expirydate = strtotime('2015-06-01');
				/*if($invoicedated == 'yes')
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalproductprice * 0.14);
				}
				else
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						//$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
						$servicetaxdetails = roundnearest($totalproductprice * 0.14);
				}*/
				/*$servicetaxdetails = roundnearest($totalamount * 0.14);
				$sbtaxdetails = roundnearest($totalamount * 0.005);
		
				$totalproductprice = $totalproductprice + $descamt;
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
				{
					$servicetax = $servicetaxdetails;
					$sbtax = $sbtaxdetails;
				}
				$netamount = $servicetax + $totalproductprice + $sbtax;*/
				/*$amount = '2^'.$productpricearray.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$calculatedprice
				.'^'.$sbtax;*/
				$amount = '2^'.$productpricearray;
			}
			break;
			case 'offer':
			{
				$prdcount = 0;
				$offeramount = $_POST['offeramount'];
				$calculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount);
				$calculatedpricesplit = explode('$',$calculatedprice);
				$totalproductprice = $calculatedpricesplit[0];
				$productpricearray = $calculatedpricesplit[1];
				$productratio = productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit);
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2015-06-01');
				/*if($invoicedated == 'yes')
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						$servicetaxdetails = roundnearest($totalproductprice * 0.14);
				}
				else
				{
					if($expirydate > $currentdate)
						//$servicetaxdetails = roundnearest($totalproductprice * 0.103);
						$servicetaxdetails = roundnearest($totalproductprice * 0.1236);
					else
						$servicetaxdetails = roundnearest($totalproductprice * 0.14);
				}*/
				
				/*$servicetaxdetails = roundnearest($totalamount * 0.14);
				$sbtaxdetails = roundnearest($totalamount * 0.005);
				
				$totalproductprice = $totalproductprice + $descamt;
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
				{
					$servicetax = $servicetaxdetails;
					$sbtax = $sbtaxdetails;
				}
				$netamount = $servicetax + $totalproductprice + $sbtax;
				$amount = '2^'.$productpricearray.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$productratio
				.'^'.$sbtax;*/
				$amount = '2^'.$productpricearray;
				
			}
			break;
			case 'inclusivetax':
			{
				$prdcount = 0;
				$inclusivetaxamount = $_POST['inclusivetaxamount'];
				//$currentdate = strtotime(date('Y-m-d'));
				//$expirydate = strtotime('2012-04-04');
				//$expirydate = strtotime('2015-06-01');
				/*if($invoicedated == 'yes')
				{
					if($expirydate > $currentdate)
						//$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(110.3));
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(112.36));
					else
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(114));
				}
				else
				{
					if($expirydate > $currentdate)
						//$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(110.3));
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(112.36));
					else
						$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(114));
				}*/
				
				//$grossamountdetails = roundnearest(($inclusivetaxamount*100)/(114.5));
				$grossamountdetails = roundnearest($inclusivetaxamount * (100/115));
		
				$grossamount = $grossamountdetails;
				//$grossamount = roundnearest(($inclusivetaxamount*100)/(110.3));
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
					$servicetax = $inclusivetaxamount - $grossamount;
					$calculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$grossamount);
				
				$calculatedpricesplit = explode('$',$calculatedprice);
				$totalproductprice = $calculatedpricesplit[0];
				$productpricearray = $calculatedpricesplit[1];
				/*$totalproductprice = $totalproductprice + $descamt;
				
				$netamount = $servicetax + $totalproductprice;
				$amount = '2^'.$productpricearray.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$grossamount.'^'.$calculatedprice;*/
				$amount = '2^'.$productpricearray;
			}
			break;
		}
		echo(json_encode($amount.'^'.count($selectedcookievaluesplit)));
	}
	break;
	case 'invoicedetailsgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$resultcount = "select inv_invoicenumbers.slno as invoicenumber,receiptamount, inv_invoicenumbers.netamount,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.createdby ,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.sbtax,inv_invoicenumbers.kktax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype ,dealer_online_purchase.slno as invoiceslno,inv_invoicenumbers.seztaxtype as seztaxtype,inv_invoicenumbers.seztaxfilepath as seztaxfilepath from inv_invoicenumbers left join (select sum(receiptamount) as receiptamount,invoiceno as invoiceno from inv_mas_receipt group by invoiceno)inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where right(customerid,5) ='".$lastslno."' order by createddate  desc; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slno = 0;
		}
		else
		{
			$startlimit = $slno ;
			$slno = $slno;
		}
		$query = "select inv_invoicenumbers.slno as invoicenumber ,receiptamount, inv_invoicenumbers.netamount,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.createdby ,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.sbtax,inv_invoicenumbers.kktax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype ,dealer_online_purchase.slno as invoiceslno,inv_invoicenumbers.status,inv_invoicenumbers.seztaxtype as seztaxtype,inv_invoicenumbers.seztaxfilepath as seztaxfilepath from inv_invoicenumbers left join (select sum(receiptamount) as receiptamount,invoiceno as invoiceno from inv_mas_receipt group by invoiceno)inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where right(customerid,5) = '".$lastslno."' order by createddate  desc LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Payment</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action<input type="hidden" name="invoicelastslno" id="invoicelastslno" /><input type="hidden" name="filepathinvoicing" id="filepathinvoicing" /></td><td nowrap = "nowrap" class="td-border-grid" align="left">Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">SEZ Download</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td> ";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";

			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['netamount'].$paynow."</td>";
			if($fetch['receiptamount'] == '' || $fetch['receiptamount'] < $fetch['netamount'])
			{
				if($fetch['status'] == 'CANCELLED')
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center"><span class="redtext">CANCELLED</span></td>';
				}
				else
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center">'.getpaymentstatus($fetch['receiptamount'],$fetch['netamount']).'&nbsp;<span class="resendtext" style "cursor:pointer" onclick = "paynow(\''.$fetch['invoicenumber'].'\');">(Pay Now)</span></td>';
				}
			}
			else
			{
				if($fetch['status'] == 'CANCELLED')
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center"><span class="redtext">CANCELLED</span></td>';
				}
				else
				{
					$grid .= "<td  nowrap='nowrap' class='td-border-grid' align='center'>".getpaymentstatus($fetch['receiptamount'],$fetch['netamount'])."&nbsp;</td>";
				}
			}
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['createdby']."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['invoicenumber'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['invoicenumber'].'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess" style ="display:none;"></div></td>';
			if($fetch['seztaxtype'] == 'yes')
			{
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><img src="../images/sez_download.gif" width="15" height="15" border="0" align="absmiddle" style="cursor:pointer" alt="Download" title="Download" onclick ="viewdownloadpath(\''.$fetch['seztaxfilepath'].'\')"/></td>';
			}
			else
			{
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>Not Avaliable</td>";

			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$invoicenosplit[2];
	}
	break;
	case 'getdealerdetails':
	{
		$dealerid = $_POST['dealerid'];
		$query = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.contactperson,inv_mas_dealer.phone,inv_mas_dealer.cell,inv_mas_dealer.emailid,inv_mas_dealer.place,inv_mas_district.districtname,inv_mas_state.statename from inv_mas_dealer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district
		left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$dealerid."'";
		$fetch = runmysqlqueryfetch($query);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
		$grid .= '<tr>';
        $grid .= '<td colspan="2"><div align="center" style="color:#006699; font-size:17px; font-weight:bold">Contact Details</div></td>';
        $grid .= '</tr>';
		
  		$grid .= '<tr>';
        $grid .= '<td width="45%"><strong>Contact Person: </strong></td>';
        $grid .= '<td width="55%" id="displaydealercontactperson" >'.$fetch['contactperson'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td valign="top"><strong>Phone: </strong></td>';
        $grid .= '<td  id="displaydealerphone" >'.$fetch['phone'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
       	$grid .= '<td><strong>Cell: </strong></td>';
        $grid .= '<td id="displaydealercell" >'.$fetch['cell'].'</td>';
       	$grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td ><strong>Email ID: </strong></td>';
        $grid .= '<td id="displaydealeremailid">'.$fetch['emailid'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= ' <td ><strong>Place:</strong></td>';
        $grid .= ' <td id="displaydealerplace">'.$fetch['place'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td><strong>District:</strong></td>';
        $grid .= '<td id="displaydealerdistrict">'.$fetch['districtname'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td><strong>State:</strong></td>';
        $grid .= '<td id="displaydealerstate">'.$fetch['statename'].'</td>';
        $grid .= '</tr>';
		$grid .= '<tr>';
        $grid .= '<td></td>';
        $grid .= '<td align="right" ><input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>';
        $grid .= '</tr>';
		$grid .= '</table>';
		/*echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$fetch['contactperson'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid'].'^'.$fetch['place'].'^'.$fetch['districtname'].'^'.$fetch['statename']);*/
		
		echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$grid);
	}
	break;
	case 'resendinvoice':
	{
		$invoiceno = $_POST['invoiceno'];
		$sent = resendinvoice($invoiceno);
		echo(json_encode($sent));
		
	}
	break;
	case 'searchbyinvoiceno':
	{
		$invoiceno = strtolower($_POST['invoiceno']);
		$query = "select distinct inv_mas_customer.slno,inv_mas_customer.businessname from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null order  by inv_mas_customer.businessname";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) == 0)
		{
			echo(json_encode('2#@# Invalid Invoice No'));
		}
		else
		{
			$grid = '';
			$count = 1;
			while($fetch = mysqli_fetch_array($result))
			{
				if($count > 1)
					$grid .='^*^';
				$grid .= $fetch['businessname'].'^'.$fetch['slno'];
				$count++;
			}
			echo(json_encode('1#@#'.$grid));
		}
	}
	break;
	case 'proceedforpurchase':
	{
		$lastslno = $_POST['lastslno'];
		$selectedcookievalue = $_POST['selectedcookievalue'];
		$selectedcookievaluesplit = explode('#',$selectedcookievalue);
		$pricingtype = $_POST['pricingtype'];
		$dealerid = $_POST['dealerid'];
		$servicetaxamount = $_POST['servicetaxamount'];
		$sbtaxamount = $_POST['sbtaxamount'];
		$kktaxamount = $_POST['kk_cess'];
		$purchasevalues = removedoublecomma(trim($_POST['purchasevalues'],','));
		$usagevalues = removedoublecomma(trim($_POST['usagevalues'],','));
		$productamountvalues = removedoublecomma(trim($_POST['productamountvalues'],','));
		$descriptiontypevalues = removedoublecomma(trim($_POST['descriptiontypevalues'],','));
		$descriptionvalues = removedoublecomma(trim($_POST['descriptionvalues'],','));
		$descriptionamountvalues = removedoublecomma(trim($_POST['descriptionamountvalues'],','));
		$productquantityvalues = removedoublecomma(trim($_POST['productquantityvalues'],','));
		$invoiceremarks = $_POST['invoiceremarks'];
		$paymentremarks = $_POST['paymentremarks'];
		$offeramount = $_POST['offeramount'];
		$offerremarks = $_POST['offerremarks'];
		$inclusivetaxamount = $_POST['inclusivetaxamount'];
		$paymenttype = $_POST['paymenttype'];
		$servicelist = $_POST['servicelist'];
		$serviceamountvalues = $_POST['serviceamountvalues'];
		$paymenttypeselected = $_POST['paymenttypeselected'];
		$paymentmode = $_POST['paymentmode'];
		$chequedate = changedateformat($_POST['chequedate']);
		$chequeno = $_POST['chequeno'];
		$drawnon = $_POST['drawnon'];
		$duedate = changedateformat($_POST['duedate']);
		$paymentamount = $_POST['paymentamount'];
		$depositdate = changedateformat($_POST['depositdate']);
		$purchasevaluesplit = explode(',',$purchasevalues);
		$usagevaluesplit = explode(',',$usagevalues);
		$productamountsplit = explode(',',$productamountvalues);
		$productquantitysplit = explode(',',$productquantityvalues);
		$servicelistsplit = explode('#',$servicelist);
		$serviceamountvaluessplit = explode('~',$serviceamountvalues);
		$descriptionamountvaluessplit = explode('~',$descriptionamountvalues);
		$descriptionvaluesplit = explode('~',$descriptionvalues);
		$descriptiontypevaluessplit = explode('~',$descriptiontypevalues);
		$cusname = $_POST['cusname'];
		$cuscontactperson = $_POST['cuscontactperson'];
		$cusaddress = $_POST['cusaddress'];
		$cusemail = $_POST['cusemail'];
		$cusphone = $_POST['cusphone'];
		$cuscell = $_POST['cuscell'];
		$customertype = $_POST['custype'];
		$businesstype = $_POST['cuscategory'];
		$invoicedated = $_POST['invoicedated'];
		$servicelistvalues = $_POST['servicelistvalues'];
		$podate = $_POST['podate'];
		$poreference = $_POST['poreference'];
		$privatenote = $_POST['privatenote'];
		$servicelistvaluessplit = explode('#',$servicelistvalues);
		$itemleveldescriptionvalues = $_POST['itemleveldescription'];
		$itemleveldescriptionlistsplit = explode('#',$itemleveldescriptionvalues);
		$productleveldescriptionvalues = $_POST['productleveldescription'];
		$productleveldescriptionlistsplit = explode('#',$productleveldescriptionlist);
		$seztaxtype = $_POST['seztaxtype'];
		$seztaxfilepath = $_POST['seztaxfilepath'];
		if($seztaxtype == 'yes')
		{
			$seztaxtype1 = $seztaxtype;
			$seztaxfilepath1 = $seztaxfilepath;
			$seztaxdate1 = date('Y-m-d').' '.date('H:i:s');
			$seztaxattachedby1 = $userid;
		}
		else
		{
			$seztaxtype1 = $seztaxtype;
			$seztaxfilepath1 = '';
			$seztaxdate1 = '';
			$seztaxattachedby1 = '';
		}
		
		/*//define spp product array 
		$spparray = array('262','265','266','267','268','269','816','876','875','874','237','238','261','872');
			
		//Define array for services
		$queryservice = "select slno from inv_mas_service where disabled  = 'yes';";
		$serviceresultfetch = runmysqlquery($queryservice);
		while($fetchvalue = mysqli_fetch_array($serviceresultfetch))
		{
			$servicearray[] = $fetchvalue['slno'];
		}*/
		
		//server side check for product amount field
		for($i=1;$i<count($productamountsplit);$i++)
		{
			if($productamountsplit[$i] == '')
			{
				echo(json_encode('2^Invalid Entry1^'.$productamountvalues)); exit;
			}
		}
		
		//server side check for Add/Less amount field
		for($i=0;$i<count($descriptiontypevaluessplit);$i++)
		{
			if($descriptiontypevaluessplit[$i] <> '')
			{
				if($descriptionvaluesplit[$i] == '')
				{
					echo(json_encode('2^Invalid Entry2^'.$descriptiontypevalues)); exit;
				}
				if($descriptionamountvaluessplit[$i] == '')
				{
					echo(json_encode('2^Invalid Entry3^'.$descriptionamountvalues)); exit;
				}
			}
		}
		
		//Check for Dealer region if CSD/BKG SPP invoice should not be generated
		$dealerquery = "select * from inv_mas_dealer where slno = '".$dealerid."';";
		$dealerresultfetch = runmysqlqueryfetch($dealerquery);
		$dealerregion = $dealerresultfetch['region'];
		
		/*if($dealerregion == '1' || $dealerregion == '2')
		{
			if(matcharray($spparray,$selectedcookievaluesplit))
			{
				echo('2^SPP Invoice cannot be generated^'); exit;
			}
			elseif(matcharray($servicearray,$servicelistvaluessplit))
			{
				echo('2^SPP Invoice cannot be generated^'); exit;
			}
		}*/
		
		//Calculate Add/Less amount
		$descamt = getdescriptionamount($descriptionamountvalues,$descriptiontypevalues);
		
		//Calculate total Service amount 
		$serviceamountvaluessplit = explode('~',$serviceamountvalues);
		for($i=0;$i<count($serviceamountvaluessplit);$i++)
		{
			$totalserviceamount +=  $serviceamountvaluessplit[$i];
		}
		
	
		//Assign service tax amount
		$servicetax = $servicetaxamount;
		
		//Assign sb cess amount
		$sbtax = $sbtaxamount;
		$kktax = $kktaxamount;
		$pricingamount = 0;
		if($selectedcookievalue <> '')
		{
			//Recalculation of amount
			$calculatedprice = calculatenormalprice($productamountsplit,$productquantitysplit);
			$calculatedpricesplit = explode('$',$calculatedprice);
			
			//total product price with quantity multiplied
			$totalproductprice = $calculatedpricesplit[0];
			
			//Product price without quantity
			$productpricearray = $calculatedpricesplit[1];
			
			//Product prices in array
			$totalproductpricearray = $calculatedpricesplit[2];
			
			//Calculate actual product price to insert in dealer online purchase table
			$actualcalculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount);
			$actualcalculatedpricesplit = explode('$',$actualcalculatedprice);
			$actualproductprice = $actualcalculatedpricesplit[2];
				
			//Calculate Total amount
			$totalproductprice = $totalproductprice + $descamt + $totalserviceamount;
		}
		else
		{
			$totalproductprice = '';
			$productpricearray = '';
			$totalproductpricearray = '';
			//Calculate Total amount
			$totalproductprice =  $descamt + $totalserviceamount;
		}
		if($servicetax!= 0)
		{
			$expirydate = strtotime('2015-06-01');
			$currentdate = strtotime(date('Y-m-d'));
			$kk_cessdate = strtotime('2016-06-01');
			
			if($expirydate > $currentdate)
			{
				/*$servicetax1 = roundnearestvalue($totalproductprice * 0.1);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetax3 = roundnearestvalue(($totalproductprice * 0.103) - (($servicetax1) + ($servicetax2)));*/
				
				$servicetax1 = roundnearestvalue($totalproductprice * 0.12);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetax3 = roundnearestvalue(($totalproductprice * 0.1236) - (($servicetax1) + ($servicetax2)));
			}
			else
			{
				/*$servicetax1 = roundnearestvalue($totalproductprice * 0.12);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetax3 = roundnearestvalue(($totalproductprice * 0.1236) - (($servicetax1) + ($servicetax2)));*/
				
				$servicetax1 = roundnearestvalue($totalproductprice * 0.14);
				$servicetax2 = 0;
				$servicetax3 = 0;
				$sbtax1 = roundnearestvalue($totalproductprice * 0.005);
				if($kk_cessdate <= $currentdate)
				{
					$kktax1 = roundnearestvalue($totalproductprice * 0.005);
				}
				else
				{
					$kktax1=0;
				}

			}
			$servicetax = $servicetax1 + $servicetax2 + $servicetax3;
			$sbtax = $sbtax1;
			$kktax = $kktax1;
		}

		$netamount = $servicetax + $totalproductprice + $sbtax + $kktax ;
		//Get the customer details
		$query1 = "select * from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode =inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode =inv_mas_district.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  where inv_mas_customer.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query1);
	
	
		// Fetch Contact Details
		$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$lastslno."'  group by customerid ";
		$resultcontact = runmysqlquery($querycontactdetails);
		$resultcontactdetails = mysqli_fetch_array($resultcontact);
		//$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
		
		$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
		$phoneres = removedoublecomma($resultcontactdetails['phone']);
		$cellres = removedoublecomma($resultcontactdetails['cell']);
		$emailidres = removedoublecomma($resultcontactdetails['emailid']);
		
		//fetch the details from customer pending table
		$query22 = "SELECT count(*) as count from inv_contactreqpending where customerid = '".$lastslno."' and customerstatus = 'pending' and editedtype = 'edit_type'";
		$result22 = runmysqlqueryfetch($query22);
		if($result22['count'] == 0)
		{
			$resultantemailid = $emailidres;
		}
		else
		{
			// Fetch of contact details, from pending request table if any
			$querycontactpending = "select GROUP_CONCAT(emailid) as pendemailid from inv_contactreqpending where customerid = '".$lastslno."' and customerstatus = 'pending' and editedtype = 'edit_type' group by customerid ";
			$resultcontactpending = runmysqlqueryfetch($querycontactpending);
			
			$emailidpending = removedoublecomma($resultcontactpending['pendemailid']);
			
			$finalemailid = $emailidres.','.$emailidpending;
			$resultantemailid = remove_duplicates($finalemailid);
		}
		$finalemailids = $resultantemailid.','.$cusemail;
		$resultantemailid = remove_duplicates($finalemailids);
		
		//Fetched customer contact details
		$generatedcustomerid = $fetch['customerid'];
		$phonenumber = explode(',', $cusphone);
		$phone = $phonenumber[0];
		$cellnumber = explode(',',$cuscell);
		$cell = $cellnumber[0];
		$businessname = $cusname;
		$address = addslashes($cusaddress);
		$place = $fetch['place'];
		$district = $fetch['districtcode'];
		$state = $fetch['statename'];
		$pincode = $fetch['pincode'];
		$contactperson = trim($cuscontactperson,',');
		$stdcode = $fetch['stdcode'];
		$phone = $phonenumber[0];
		$fax = $fetch['fax'];
		$cell = $cellnumber[0];
		$branchname = $fetch['branchname'];
		$emailid = trim($cusemail,',');
		//$category = $fetch['category'];
		//$type = $fetch['type'];
		$currentdealer = $dealerid;
		$customerid17digit = $fetch['customerid'];
		
		$category = $businesstype;
		$type = $customertype;
		
		
		//Check for payment type to update payment remarks 
		if($paymenttype == 'credit/debit')
		{
			$paymentremarksnew = 'Selected for Credit/Debit Card Payment. This is subject to successful transaction';
			$paymenttypeselected = 'paymentmadelater';
			$paymentmode = 'credit/debit';
		}
		
		//Define payment remarks for the invoice
		if($paymenttypeselected == 'paymentmadelater')
			$paymentremarksnew = 'Payment Due!! (Due Date: '.changedateformat($duedate).') '.$paymentremarks;
		else
		{
			if($paymentmode == 'chequeordd')
				$paymentremarksnew = 'Received Cheque No: '.$chequeno.', dated '.changedateformat($chequedate).', drawn on '.$drawnon.', for amount '.$paymentamount.'. Cheques received are subject to realization.';
			else if($paymentmode == 'cash')
				$paymentremarksnew = $paymentremarks;
			else
				$paymentremarksnew = 'Payment through Online Transfer. '.$paymentremarks.'';
		}
		$invoiceremarks = ($invoiceremarks == '')?'None':$invoiceremarks;
		$paymentremarksnew = ($paymentremarksnew == '')?'None':$paymentremarksnew;
					
		//If it is a new customer, generate new customer id and update it in Customer Master
		if($customerid17digit  == '')
		{
			if($selectedcookievalue <> '')
				$firstproduct = $selectedcookievaluesplit[0];
			else
				$firstproduct = '000';
			//Get new customer id
			$query = "select statecode from inv_mas_district where districtcode  = '".$district."';";
			$fetch = runmysqlqueryfetch($query);
			$statecode = $fetch['statecode'];
			$newcustomerid = $statecode.$district.$currentdealer.$firstproduct.$lastslno;
			$password = generatepwd();
			//update customer master with customer product
			$query = "update inv_mas_customer set firstdealer = '".$currentdealer."' , firstproduct = '".$firstproduct."', initialpassword = '".$password."', loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),customerid = '".$newcustomerid."' where slno = '".$lastslno."';";
			$result = runmysqlquery($query);
			$generatedcustomerid = $newcustomerid;
			sendwelcomeemail($lastslno, $userid);
	
		}
		//Check for payment type to update payment remarks 
		if($paymenttype == 'credit/debit')
		{
			$paymentremarks = 'Selected for Credit/Debit Card Payment. This is subject to successful transaction';
			$paymenttypeselected = 'paymentmadelater';
			$paymentmode = 'credit/debit';
		}
		
		//Fetch the max slno from dealer online purchase table
		$countquery = "select ifnull(max(slno),0) + 1 as slnotobeinserted from dealer_online_purchase;";
		$fetchcount = runmysqlqueryfetch($countquery);
		$slnotobeinserted = $fetchcount['slnotobeinserted'];
					
		//If payment is made later, set the payment mode to empty
		if($paymenttypeselected == 'paymentmadelater')
		{
			$paymentmode = "";
			$duedate = $duedate;
		}
		else
			$duedate = date('Y-m-d');
	
		//Insert the purchase details in dealer online purchase table
		$query = "insert into `dealer_online_purchase`(slno,customerreference,businessname,address,place,district,state,pincode,contactperson,stdcode,phone,fax,cell,emailid,category,type,currentdealer,amount,netamount,servicetax,kktax,sbtax,products, paymentdate, paymenttime, purchasetype, paymenttype, usagetype, offertype, offerdescription, offeramount, invoiceremarks, paymentremarks,quantity,pricingtype,pricingamount,productpricearray,createdby,createdip,createddate,lastmodifieddate,lastmodifiedip,lastmodifiedby,totalproductpricearray,offerremarks,module,service,serviceamount,paymenttypeselected,paymentmode,actualproductprice,duedate,privatenote,podate,poreference,productbriefdescription,itembriefdescription,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby) values
('".$slnotobeinserted."','".$lastslno."','".$businessname."','".addslashes($address)."','".$place."','".$district."','".$state."','".$pincode."','".$contactperson."','".$stdcode."','".$phone."','".$fax."','".$cell."','".$emailid."','".$category."','".$type."','".$currentdealer."','".$totalproductprice."','".$netamount."','".$servicetax."','". $kktax ."','".$sbtax."','".$selectedcookievalue."','','','".$purchasevalues."','".$paymenttype."','".$usagevalues."','".$descriptiontypevalues."','".$descriptionvalues."','".$descriptionamountvalues."','".$invoiceremarks."','".$paymentremarks."','".$productquantityvalues."','".$pricingtype."','".$pricingamount."','".$productpricearray."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."','".$totalproductpricearray."','".$offerremarks."','user_module','".$servicelist."','".$serviceamountvalues."','".$paymenttypeselected."','".$paymentmode."','".$actualproductprice."','".$duedate."','".$privatenote."','".changedateformat($podate)."','".$poreference."','".$productleveldescriptionvalues."','".$itemleveldescriptionvalues."','".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."')";
		$result = runmysqlquery($query);
		//$amount = '1^'.$calculatedprice.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$slnotobeinserted;

		if($selectedcookievalue <> '')
		{
			//Update current dealer of customer  with logged in dealer id
			$query = "update inv_mas_customer set currentdealer = '".$currentdealer."' where slno = '".$lastslno."';";
			$result = runmysqlquery($query);
		}
		
		
	//Get the logged in  dealer details
	$query0 = "select billingname,inv_mas_region.category as region,inv_mas_dealer.emailid as dealeremailid,inv_mas_dealer.region as regionid,inv_mas_dealer.branch  as branchid,inv_mas_dealer.district as dealerdistrict from inv_mas_dealer left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  where inv_mas_dealer.slno = '".$currentdealer."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealername = $fetch0['billingname'];
	$dealerregion = $fetch0['region'];
	$dealeremailid = $fetch0['dealeremailid'];
	$regionid = $fetch0['regionid'];
	$branchid = $fetch0['branchid'];
	$dealerdistrict = $fetch0['dealerdistrict'];

	//update region and branch of customer as per dealer
	$query11 = "update inv_mas_customer set branch = '".$branchid."', region = '".$regionid."' where slno = '".$lastslno."';";
	$result11 = runmysqlquery($query11);
	
	//Get the next record serial number for insertion in invoicenumbers table
	$query1 = "select ifnull(max(slno),0) + 1 as billref from inv_invoicenumbers";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$onlineinvoiceslno = $resultfetch1['billref'];
	
	//Get the next invoice number from invoicenumbers table, for this new_invoice
	$query4 = "select ifnull(max(onlineinvoiceno),".getstartnumber($dealerregion).")+ 1 as invoicenotobeinserted from inv_invoicenumbers where category = '".$dealerregion."'";
	$resultfetch4 = runmysqlqueryfetch($query4);
	$onlineinvoiceno = $resultfetch4['invoicenotobeinserted'];
	$invoicenoformat = 'RSL/'.$dealerregion.'/'.$onlineinvoiceno;
	
	//Get the statename and district name
	$query112 = "select districtname,statename,category as regionname,branchname from inv_mas_district left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_district.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid where inv_mas_district.districtcode = '".$dealerdistrict."'";
	$resultfetch112 = runmysqlqueryfetch($query112);
	$districtname = $resultfetch112['districtname'];
	$statename = $resultfetch112['statename'];
	$branchname = $resultfetch112['branchname'];

	$emailid = explode(',', trim($cusemail,','));
	$emailidplit = $emailid[0];
	$phonenumber = explode(',', trim($cusphone,','));
	$phonenumbersplit = $phonenumber[0];
	$cellnumber = explode(',', trim($cuscell,','));
	$cellnumbersplit = $cellnumber[0];
	$contactperson = explode(',', trim($cuscontactperson,','));
	$contactpersonplit = $contactperson[0];
	$stdcode = ($fetch['stdcode'] == '')?'':$fetch['stdcode'].' - ';
	$address1 = $address.', Pin: '.$pincode;
	$invoiceheading = ($fetch['statename'] == 'Karnataka')?'Tax Invoice':'Bill Of Sale';
	$branchname = $branchname;
	$amountinwords = convert_number($netamount);
	$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
	
//	$invoicedate = date('Y-m-d').' '.date('H:i:s');

	/*if(matcharray($spparray,$selectedcookievaluesplit))
	{
		$invoicedate = '2011-06-30 23:55:00';
	}
	else
	{
	$invoicedate = date('Y-m-d').' '.date('H:i:s');
	}*/

	$currentdate = strtotime(date('Y-m-d'));
	//$currentdate = strtotime('2012-04-05');
	//$backdateddate = strtotime('2014-03-31');
	$backdateddate = strtotime('2016-03-31');
	$expirydate1 = strtotime('2016-04-03');
	$expirydate = strtotime('2015-06-01');
	
	if($invoicedated == 'yes')
	{	
		if($expirydate1 > $currentdate)
		     $invoicedate = '2016-03-31 23:55:00';
			//$invoicedate = '2014-03-31 23:55:00';
		else
			$invoicedate = date('Y-m-d').' '.date('H:i:s');
			//$invoicedate = '2012-04-01 23:55:00';
	}
	else
		$invoicedate = date('Y-m-d').' '.date('H:i:s');
		//$invoicedate = '2012-04-01 23:55:00';
	//exit;
		//Insert complete invoice details to invoicenumbers table 
		$query = "Insert into inv_invoicenumbers(slno,customerid,businessname,contactperson,address,place,pincode,emailid,description,invoiceno,dealername,createddate,createdby,amount,servicetax,sbtax,kktax,netamount,phone,cell,customertype,customercategory,region,purchasetype,category,onlineinvoiceno,dealerid,products,productquantity,pricingtype,createdbyid,totalproductpricearray,actualproductpricearray,module,servicetype,serviceamount,paymenttypeselected,paymentmode,stdcode,branch,amountinwords,remarks,servicetaxdesc,invoiceheading,offerremarks,invoiceremarks,duedate,branchid,regionid,privatenote,podate,poreference,productbriefdescription,itembriefdescription,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby) values('".$onlineinvoiceslno."','".cusidcombine($generatedcustomerid)."','".$businessname."','".$contactpersonplit."','".addslashes($address1)."','".$place."','".$pincode."','".$emailidplit."','','".$invoicenoformat."','".$dealername."','".$invoicedate."','".$username."','".$totalproductprice."','".$servicetax."','".$sbtax."','". $kktax ."','".$netamount."','".$phonenumbersplit."','".$cellnumbersplit."','".$type."','".$category."','".$dealerregion."','Product','".$dealerregion."','".$onlineinvoiceno."','".$currentdealer."','".$selectedcookievalue."','".$productquantityvalues."','".$pricingtype."','".$userid."','".$totalproductpricearray."','".$actualproductprice."','user_module','".$servicelist."','".$serviceamountvalues."','".$paymenttypeselected."','".$paymentmode."','".$stdcode."','".$branchname."','".$amountinwords."','".$paymentremarksnew."','".$servicetaxdesc."','".$invoiceheading."','".$offerremarks."','".$invoiceremarks."','".$duedate."','".$branchid."','".$regionid."','".$privatenote."','".changedateformat($podate)."','".$poreference."','".$productleveldescriptionvalues."','".$itemleveldescriptionvalues."','".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."');";
		$result = runmysqlquery($query);
	
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
				$query7 = "INSERT INTO inv_dealercard(dealerid,cardid,productcode,date,usagetype,purchasetype,userid,customerreference,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,cusbillnumber,scheme,cuscardattacheddate,cuscardattachedby,usertype,addlicence,invoiceid) values('".$currentdealer."','".$cardid."','".$arraysplit[$i]."','".date('Y-m-d').' '.date('H:i:s')."','".$usagetype."','".$purchasevaluesplit[$i]."','2','".$lastslno."','".$usagetype."','".$purchasevaluesplit[$i]."','".$arraysplit[$i]."','".$currentdealer."','".$firstbillnumber."','1','".date('Y-m-d').' '.date('H:i:s')."','".$currentdealer."','user','".$addlicence."','".$onlineinvoiceslno."')";
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
			$query = "INSERT INTO inv_mas_receipt(invoiceno,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,chequedate,chequeno,drawnon,depositdate,receiptdate,receipttime,module,partialpayment) values('".$onlineinvoiceslno."','".$paymentamount."','".$paymentmode."','','','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$lastslno."','".$chequedate."','".$chequeno."','".$drawnon."','".$depositdate."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','user_module','".$partialpayment."');";
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
				//query changed for productcode
				$procode = $carddetailsfetch['productcode'];
				if($procode == "690" || $procode == "222" || $procode == "221" || $procode == "219" || $procode == "223"
				|| $procode == "220" || $procode == "101")
				{
					$description .= $slno.'$'.$carddetailsfetch['productname'].'$'.$purchasetype.'$'.$usagetype.'$'.
					$carddetailsfetch['scratchnumber'].'$'.$carddetailsfetch['cardid'].'$'.$productpricearraysplit[$k];
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
		
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','70','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno.'$$'.$onlineinvoiceslno."')";
		$eventresult = runmysqlquery_log($eventquery);
			
		//Online bill Generation in PDF.
		$pdftype = 'send';
		$invoicedetails = vieworgeneratepdfinvoice($onlineinvoiceslno,$pdftype);
		$invoicedetailssplit = explode('^',$invoicedetails);
		$filebasename = $invoicedetailssplit[0];
		sendpurchasesummaryemail($currentdealer,$slnotobeinserted);
		
		#########  Mailing Starts -----------------------------------
		if(($_SERVER['HTTP_HOST'] == "192.168.2.79") || ($_SERVER['HTTP_HOST'] == "bhumika"))
		{
			$emailid = 'samar.s@relyonsoft.com';
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
		
		if(($_SERVER['HTTP_HOST'] == "192.168.2.79") || ($_SERVER['HTTP_HOST'] == "bhumika"))
		{
			$dealeremailid = 'samar.s@relyonsoft.com';
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
		$date = date('d-m-Y');
		$time = date('H:i:s');
		$array = array();
		$subject = "Relyon Online Invoice | ".$invoicenoformat;
		$company = $fetch['businessname'];
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
		if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
		{
			$bccarray = array('meghana' => 'samar.s@relyonsoft.com'); 
		}
		else
		{
	   		$bccarray = array('Bigmail' => 'samar.s@relyonsoft.com'); 
		}
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$attachedfilename = explode('.',$filebasename);
		$html = $msg;
		$text = $textmsg;
		$replyto = $ccemailids[$ccemailarray[0]];
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
		$bcceallemailid = 'samar.s@relyonsoft.com';
		inserttologs(imaxgetcookie('userid'),$dealerid,$fromname,$fromemail,$emailid,$dealeremailid,$bcceallemailid,$subject);
		fileDelete('../filecreated/',$filebasename);
		//check for payment type
		if($paymenttype <> 'credit/debit')
		{
			echo(json_encode('1^Card Attached^'.$onlineinvoiceslno.''));
		}
		else
		{
			echo(json_encode('3^Card Attached^'.$onlineinvoiceslno.''));
		}
	}
	break;
	case 'customerregistration':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_mas_product.productname as productname FROM inv_customerproduct left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where customerreference = '".$lastslno."' order by `date`  desc,`time` desc ; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slno = 0;
		}
		else
		{
			$startlimit = $slno;
			$slno = $slno;
		}
		
		$query = "SELECT inv_mas_product.productname as productname,getPINNo(inv_customerproduct.cardid) AS cardid, 
inv_customerproduct.computerid AS computerid,inv_customerproduct.softkey AS softkey,inv_customerproduct.date AS regdate, 
inv_customerproduct.time AS regtime, inv_mas_users.fullname AS generatedby, inv_mas_dealer.businessname AS businessname, 
inv_customerproduct.billnumber as Billnum,inv_customerproduct.billamount as billamount,inv_customerproduct.remarks as remarks 
FROM inv_customerproduct 
left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno 
left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where customerreference = '".$lastslno."' order by `date`  desc,`time` desc LIMIT ".$startlimit.",".$limit."; ";

		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Computer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Soft Key</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Time</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generatd By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.' align="left">';
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left' >".$slno."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productname']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cardid']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['computerid']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['softkey']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['regdate']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['regtime']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['generatedby']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['businessname']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['Billnum']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['billamount']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
 
		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"  ><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td align ="left"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" class="resendtext" style="cursor:pointer;">Show More Records >></a> &nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
		$responsearray21 = array();
		$responsearray21['errorcode'] = '1';
		$responsearray21['grid'] = $grid;
		$responsearray21['count'] = $fetchresultcount;
		$responsearray21['linkgrid'] = $linkgrid;
		echo(json_encode($responsearray21));
		
		
	}
	break;
	
	case 'contactsave':
	{
		$businessname = $_POST['businessname'];
		$address = $_POST['address'];
		$place = $_POST['place'];
		$pincode = $_POST['pincode'];
		$district = $_POST['district'];
		$category = $_POST['category'];
		$type = $_POST['type'];
		$lastslno = $_POST['lastslno'];
		$fax = $_POST['fax'];
		$stdcode = $_POST['stdcode'];
		$contactarray = $_POST['contactarray'];
		$totalarray = $_POST['totalarray'];
		$branch = $_POST['branch'];
		$region = $_POST['region'];
		$currentdealer = $_POST['currentdealer'];
		$totalsplit = explode(',',$totalarray);
		$contactsplit = explode('****',$contactarray);
		$contactcount = count($contactsplit);
		if($contactcount > 1)
		{
			for($i=0;$i<$contactcount;$i++)
			{
				$contactressplit[] = explode('#',$contactsplit[$i]);
			}
		}
		else
		{
			for($i=0;$i<$contactcount;$i++)
			{
				$contactressplit[] = explode('#',$contactsplit[$i]);
			}
		}
		
		$website = $_POST['website'];
		$remarks = $_POST['remarks'];
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$date = datetimelocal('d-m-Y');
		
		$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS newcustomerid FROM inv_mas_customer");
		$cusslno = $query['newcustomerid'];

		$query = "Insert into inv_mas_customer(slno,customerid,businessname,address, place,pincode,district,region,category,type,stdcode,website,remarks,password,passwordchanged,disablelogin,createddate,createdby,corporateorder,currentdealer,fax,activecustomer,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,branch,companyclosed, promotionalsms,promotionalemail) values ('".$cusslno."','','".trim($businessname)."','".$address."','".$place."','".$pincode."','".$district."','".$region."','".$category."','".$type."','".$stdcode."','".$website."','".$remarks."','','N','no','".changedateformatwithtime($createddate)."','2','no','".$currentdealer."','".$fax."','yes','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$branch."','no','no','no');";
		$result = runmysqlquery($query);
		for($j=0;$j<count($contactressplit);$j++)
		{
			$selectiontype = $contactressplit[$j][0];
			$contactperson = $contactressplit[$j][1];
			$phone = $contactressplit[$j][2];
			$cell = $contactressplit[$j][3];
			$emailid = $contactressplit[$j][4];
			//Added Space after comma is not avaliable in phone, cell and emailid fields
			$phonespace = str_replace(", ", ",",$phone);
			$phonevalue = str_replace(',',', ',$phonespace);
			
			$cellspace = str_replace(", ", ",",$cell);
			$cellvalue = str_replace(',',', ',$cellspace);
			
			$emailidspace = str_replace(", ", ",",$emailid);
			$emailidvalue = str_replace(',',', ',$emailidspace);
			
			$query2 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$cusslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."');";
			$result = runmysqlquery($query2);
		}

		$responsesavearray = array();
		$responsesavearray['successcode'] = "1";
		$responsesavearray['successmessage'] = "Customer Record  Saved Successfully";
		echo(json_encode($responsesavearray));
	}
	break;
}


function calculatenormalprice($productamountsplit,$productquantitysplit)
{
	for($i = 0; $i < count($productamountsplit); $i++)
	{
		for($j=1;$j<=$productquantitysplit[$i];$j++)
		{
			$singleproductprice = $productamountsplit[$i];
			if($prdcount > 0)
			$productpricearray .= '*';
				$productpricearray .= ($singleproductprice);
			$prdcount++;
		}
		$productprice = $productamountsplit[$i] * ($productquantitysplit[$i]);
		if($prdcount1 > 0)
		$totalproductpricearray .= '*';
			$totalproductpricearray .= ($productprice);
		$prdcount1++;
		$productprice = ($productprice);
		$totalproductprice += $productprice ;
	}
	return $totalproductprice.'$'.$productpricearray.'$'.$totalproductpricearray;
}


function calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount)
{
	$prdcount = 0;
	if(($pricingtype == 'offer') || ($pricingtype == 'inclusivetax'))
	{
		$productratio = productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit);
	}
	else
	{
		$productratio = 1;
	}
	for($i = 0; $i < count($selectedcookievaluesplit); $i++)
	{
		$recordnumber = $selectedcookievaluesplit[$i];
		$purchasetype = $purchasevaluesplit[$i];
		$usagetype = $usagevaluesplit[$i];
		if($purchasetype == 'new' && $usagetype == 'singleuser')
			$producttypeprice = 'newsuprice';
		else if($purchasetype == 'new' && $usagetype == 'multiuser')
			$producttypeprice = 'newmuprice';
		else if($purchasetype == 'updation' && $usagetype == 'singleuser')
			$producttypeprice = 'updatesuprice';
		else if($purchasetype == 'updation' && $usagetype == 'multiuser')
			$producttypeprice = 'updatemuprice';
		else if($purchasetype == 'new' && $purchasetype == 'addlic')
			$producttypeprice = 'newaddlicenseprice';
		else
			$producttypeprice = 'updationaddlicenseprice';
		$query = "select ".$producttypeprice." as productprice from inv_dealer_pricing  where product = '".$recordnumber."'";
		$fetch = runmysqlqueryfetch($query);
		$singleproductprice = $fetch['productprice'];
		if($prdcount > 0)
			$productpricearray .= '*';
		if(($i == count($selectedcookievaluesplit)-1) && (($pricingtype == 'offer')|| ($pricingtype == 'inclusivetax')))
		{
			$lastproductprice = $offeramount - $totalproductprice;
			$productpricearray .= $lastproductprice;
		}
		else
		{
			$productpricearray .= roundnearest($singleproductprice * $productratio);
		}
		$prdcount++;
		$productprice = $fetch['productprice'] * ($productquantitysplit[$i]);
		if($prdcount1 > 0)
			$totalproductpricearray .= '*';
		$totalproductpricearray .= roundnearest($productprice * $productratio);
		$prdcount1++;
		$productprice = roundnearest($productprice * $productratio);
		$totalproductprice += $productprice ;
	}
	return $totalproductprice.'$'.$productpricearray.'$'.$totalproductpricearray.'$'.$productratio;
}

function getdescriptionamount($descriptionamountvalues,$descriptiontypevalues)
{
	$descriptionamountsplit = explode('~',$descriptionamountvalues);
	$descriptiontypesplit = explode('~',$descriptiontypevalues);
	$descriptioncount = count($descriptionamountsplit);
	$amount = 0;
	for($i=0;$i<$descriptioncount; $i++)
	{
		if($descriptiontypesplit[$i] == 'add')
			$amount = ($amount) + $descriptionamountsplit[$i];
		else if($descriptiontypesplit[$i] == 'less')
			$amount = ($amount) - $descriptionamountsplit[$i];
		else
			$amount;
	}
	return roundnearest($amount);
}

function productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit)
{
	for($i = 0; $i < count($selectedcookievaluesplit); $i++)
	{
		$recordnumber = $selectedcookievaluesplit[$i];
		$purchasetype = $purchasevaluesplit[$i];
		$usagetype = $usagevaluesplit[$i];
		$productquantity = $productquantitysplit[$i];
		if($purchasetype == 'new' && $usagetype == 'singleuser')
			$producttypeprice = 'newsuprice';
		else if($purchasetype == 'new' && $usagetype == 'multiuser')
			$producttypeprice = 'newmuprice';
		else if($purchasetype == 'updation' && $usagetype == 'singleuser')
			$producttypeprice = 'updatesuprice';
		else if($purchasetype == 'updation' && $usagetype == 'multiuser')
			$producttypeprice = 'updatemuprice';
		else if($purchasetype == 'new' && $purchasetype == 'addlic')
			$producttypeprice = 'newaddlicenseprice';
		else
			$producttypeprice = 'updationaddlicenseprice';
		$query = "select ".$producttypeprice." as productprice from inv_dealer_pricing  where product = '".$recordnumber."'";
		$fetch = runmysqlqueryfetch($query);
		$productprice = $fetch['productprice'] * $productquantity;
		$totalproductprice += $productprice;
	}
	$productratio = ($offeramount)/($totalproductprice);
	return round($productratio,2);
}

function roundnearest($amount)
{
	$firstamount = round($amount,1);
	$amount1 = round($firstamount);
	return $amount1;
}




function getpaymentstatus($receiptamount,$netamount)
{
	 if($receiptamount == '')
	 {
	   return '<span class="redtext">UNPAID</span>';
	 }
	 else if($receiptamount < $netamount)
	 { 
	  return '<span class="redtext">PARTIAL</span>';
	 }
	 else if($receiptamount == $netamount)
	 { 
	  return '<span class="greentext">PAID</span>';
	 } 
	 else
	   return '<span class="greentext">PAID</span>';
}

function getstartnumber($region)
{
	switch($region)
	{
		case 'BKG': $startnumber = '1'; break;
		case 'BKM': $startnumber = '1';break;
		case 'CSD': $startnumber = '11101';break;
		default: $startnumber = '1';break;
	}
	return ($startnumber-1);
}

?>