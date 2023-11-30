function checkleftdealerselection(divid45,dealerid)
{

<?php
include('../functions/phpfunctions.php');
$dealerid = imaxgetcookie('dealeruserid');

echo('dealerlist = \'');
$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode, inv_mas_dealer.region, inv_mas_dealer.branchhead as branchhead,inv_mas_dealer.telecaller as telecaller from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 where inv_mas_dealer.slno = '".$dealerid."';";
$fetch = runmysqlqueryfetch($query);
$branchhead = $fetch['branchhead'];
$telecaller = $fetch['telecaller'];
if($branchhead == 'yes')
{
    echo('<input type="hidden" id="logintype" name="logintype" value="branchhead" />');
}
else
{
    echo('<input type="hidden" id="logintype" name="logintype" value="notbranchhead" />');
}
if($branchhead == 'yes' || $telecaller == 'yes')
{
    $query1 = "select * from inv_mas_dealer where slno = '".$dealerid."';";
    $resultfetch1 = runmysqlqueryfetch($query1);
    $branch = $resultfetch1['branch'];
    $query = "SELECT distinct inv_mas_dealer.slno, inv_mas_dealer.businessname
FROM inv_invoicenumbers left join inv_mas_dealer on  inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
where inv_mas_dealer.disablelogin = 'yes' and inv_mas_dealer.branch = '".$branch."' ";
    $result1 = runmysqlquery($query);
    //echo($query);exit;
    echo('<select name="leftdealerid" class="swiftselect" id="leftdealerid" onchange="disabledealer()" style="width:200px;" ><option value=" ">ALL</option>');
    while($fetch = mysqli_fetch_array($result1))
    {
        echo('<option value="'.$fetch['slno'].'">'.$fetch['businessname'].'</option>');
    }
    echo("</select>';"."\n\t\t");


}
else
{
    $dealerpiece = " where slno = '".$dealerid."'";
    $query = "select businessname as dealername, slno as dealerid from inv_mas_dealer ".$dealerpiece." ";
    $result1 = runmysqlqueryfetch($query);
    echo($result1['dealername']);
    echo('<input type="hidden" id="leftdealerid" name="leftdealerid" value="'.$dealerid.'" /><input type="hidden" id="logintype" name="logintype" value="notbranchhead" />');
    echo("';");
}



?>
document.getElementById(divid45).innerHTML = dealerlist ;
}

