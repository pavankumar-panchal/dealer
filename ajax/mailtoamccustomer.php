<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('dealeruserid') <> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

include('../inc/checksession.php');
$switchtype = $_POST['switchtype'];


switch($switchtype)
{
case 'getcustomerlist':
    { 
        $grid="";
                $limit=$_POST['limit'];
                $databasefield = $_POST['databasefield'];
        $textfield = $_POST['textfield'];
        $dealer = $_POST['dealer2'];
        $district = $_POST['district'];
        $productslist = $_POST['product'];
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


           



          //echo $query;
           $result1 = runmysqlquery($query);
           $total=mysqli_num_rows($result1);
            
                if($limit){$query.= " LIMIT ".$limit;}   
            $result = runmysqlquery($query);
            $grid.=' <table class="table-border-grid" style="border-bottom:none;" cellpadding="3" cellspacing="0" width="100%">
                                                <tbody><tr class="tr-grid-header">
                                                   <td class="td-border-grid" align="left" nowrap="nowrap"><input type="checkbox" class="customer-list" id="check_all_customer"></td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Sl No</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Customer Name</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Dealer Name</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Proforma Inv</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Total Amount</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Mail Count</td>

                                            
                                                </tr>';
                  

                $count=1;
            while($fetch=mysqli_fetch_assoc($result))
              { 
                
                $pinv_slno=$fetch['slno'];
                $count_query="SELECT count(isap_id) as countt FROM `inv_spp_amc_mailer` WHERE isap_id=$pinv_slno";
                $count_result=runmysqlquery($count_query);
                $count_fetch=mysqli_fetch_assoc($count_result);
                $mailcount=$count_fetch['countt'];
                  
                $slnoo= $fetch['slno'];
                  
                  $grid.='<tr><td class="td-border-grid" align="left" nowrap="nowrap"><input type="checkbox" class="customer-list" id="check_customer" name="customersl[]" value="'.$fetch['slno'].'"></td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">'.$count.'</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">'.$fetch['businessname'].'</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">'.$fetch['dealername'].'</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap"><form method="post" id="submitformm"><input type="hidden" name="invoicelastslno" id="invoicelastslno" > <a onclick="viewperformainvoicee('.$slnoo.');" class="resendtext">'.$fetch['invoiceno'].'</a></form></td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">'.$fetch['netamount'].'</td><td class="td-border-grid" align="left" nowrap="nowrap">'.$mailcount.'</td></tr>';
             $count++;
                 }
                   
              

if($count>1)
           {

             if($total <= $limit)
               {
                 $grid.="<tr><td colspan='5' ><span style='color:red;'>**No More Records**</span></td></tr></table>";
               }
               else if($limit)
               {
                 $grid.="<tr><td colspan='2'><center><span onclick='showmore();' style='cursor:pointer;'>Show More >></span></center></td><td ><center><span onclick='showall();' style='cursor:pointer;'>Show All >></span></></td><td colspan='2'><div id='showloadpic' class='showloadpic'><img src='../images/imax-loading-image.gif' alt='loading...' /></div></td></tr></table>";
               }
            else
               {
                $grid.="<tr><td colspan='5' ><span style='color:red;'>**No More Records**</span></td></tr></table>";
               }
           }
         else
           {
              $grid.="<tr><td colspan='5'><center><b>No datas found to display</b></center></td></tr></table>";
           }

          $mailresultarray=Array();
          $mailresultarray['grid']=$grid;
          $mailresultarray['total']="Total Count:".$total;
           echo json_encode($mailresultarray);

         }


}

?>