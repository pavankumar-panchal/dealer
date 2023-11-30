<?php 
	$userid = imaxgetcookie('dealeruserid');
	$query = "select * from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$relyonexecutive = $resultfetch['relyonexecutive'];
	$enablebilling = $resultfetch['enablebilling'];
	$enablematrixbilling = $resultfetch['enablematrixbilling'];
	$blockcancel = $resultfetch['blockcancel'];
	
	$beanchhead=$resultfetch['branchhead'];
	$maindealers = $resultfetch['maindealers'];	
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><DIV class=navigation>
        <DIV>
          <UL class=sf-menu>
            <LI class=current><A href="./index.php?a_link=dashboard">Home</A> </LI>
            <LI><a>Customers</a>
              <ul>
                <li><a href="./index.php?a_link=customers">Customers</a></li>
                <li><a href="./index.php?a_link=interaction">Interaction</a></li>
                <?php if($relyonexecutive == 'yes') {?>
                <li><a href="./index.php?a_link=cuscardattach">Attach PIN number to customer</a></li>
                <?php } ?>
                <?php if($beanchhead == 'yes') {?>
                <li><a href="./index.php?a_link=productstodealers">Products to Dealers</a></li>
                <?php } ?>
                 <?php if($relyonexecutive == 'no') {?>
                <li><a href="./index.php?a_link=dealerpinattach">Attach PIN number (Dealers)</a></li>
                <?php } ?>
                <li><a href="./index.php?a_link=crossproduct">Cross Product Sales</a></li>
                
                <?php if($beanchhead == 'yes' || $userid == '1780' || $userid == '1701' || $userid == '1369' || $userid == '1316' || $userid == '1795' || $userid == '1526' || $userid == '1428' || $userid == '1429' || $userid == '1567' || $userid == '1791' || $userid == '1877') {?>
                <li><a href="./index.php?a_link=customuser">Custom User</a> </li>
                <li><a href="./index.php?a_link=mailamccustomer">Mail Amc Customer</a> </li>
                <?php } ?>
                
                 <!--<li><a href="./index.php?a_link=implementation">Implementation</a></li>-->
                <?php if($maindealers == 'yes') {?>
                    <li><a href="./index.php?a_link=dealerpinallot">Allot PIN number Sub Dealer</a></li>
                <?php } ?>                 
              </ul>
            </LI>
            <?php if($enablebilling == 'no') {?>
           <LI ><a>Purchase</a>
              <ul>
              
                <li><a href="./index.php?a_link=purchaseproduct">Purchase Products</a></li>
               
                <?php if($relyonexecutive == 'no') {?>
                <li><a  href="./index.php?a_link=dealerpayment">Make Payment (Debit / Credit)</a></li>
                <?php } ?>
              </ul>
            </LI>
             <?php } ?>
             <?php if($enablebilling == 'yes' || $enablematrixbilling == 'yes') {?>
            <LI ><a>Billing</a>
              <ul>
              <?php if($enablebilling == 'yes') { ?>
                  <li><a href="./index.php?a_link=invoicing">Invoicing</a></li>
                <?php } ?>
                <?php if($enablematrixbilling == 'yes') { ?>
                  <li><a href="./index.php?a_link=matrixinvoicing">Matrix Invoicing</a></li>
                <?php } ?>
                <?php //if($enablebilling == 'yes') { ?>
                <li><a  href="./index.php?a_link=receipts">Receipts</a></li>
                <?php if($enablematrixbilling == 'yes') { ?>
                <li><a href="./index.php?a_link=matrixreceipts">Matrix Receipts</a> </li>
                <?php } ?>
                <!--<li><a  href="./index.php?a_link=viewinvoice">Register</a></li>-->
                <li ><a ><span class="sf-menupointer" style="text-align:left">Register</span></a>
                <ul class="sf-menu">
                <li><a href="./index.php?a_link=invoiceregister">Invoice Register</a> </li>
                <?php if($enablematrixbilling == 'yes') { ?>
                <li><a href="./index.php?a_link=matrixinvoiceregister">Matrix Invoice Register</a> </li>
                <?php } ?>
                <li><a href="./index.php?a_link=receiptregister">Receipt Register</a> </li>
                 <li><a href="./index.php?a_link=outstandingregister">Outstanding Register</a> </li>
                 
                <!--<li><a href="./index.php?a_link=productmapping">Product to Dealer</a> </li>
                <li><a href="./index.php?a_link=schemepricing">Scheme Pricing</a> </li>-->
                </ul></li>
                <li><a href="../invoicingdemo/index.php" target="_blank">Video Tutorials</a> </li>
              </ul>
              <?php //} ?>
            </LI>
			<?php } ?>
            <LI><a>Implementation</a>
              <ul>
                <li><a href="./index.php?a_link=implementation">Create Implementation</a></li>
                <li><a href="http://imax.relyonsoft.net/implementation/demo/" target="_blank">Video Tutorials</a></li>
              </ul>
            </LI>
            <?php if($blockcancel == 'yes') {?>
            <LI class=current><a>Cards</a>
              <ul>
                  <li><a  href="./index.php?a_link=blockcancel">Block/Cancel Pins</a></li>
              </ul>
            </LI>
            <?php } ?>
            <LI class=current><a>Reports</a>
              <ul>
                <li><a href="./index.php?a_link=viewpurchase">View Purchases</a></li>
                <li><a  href="./index.php?a_link=transactionsummary">Transaction Summary</a></li>
                <li><a  href="./index.php?a_link=dealerstockreport">Dealer Stock Report</a></li>
                <li><a  href="./index.php?a_link=registrationreport">Registration Report</a></li>
                <?php if($relyonexecutive == 'yes') {?>
                <li ><A ><span class="sf-menupointer" >Implementation</span></A>
                  <ul class="sf-menu">
                    <li><a href="./index.php?a_link=implementationsummary">Implementation Summary</a></li>
                    <li><a href="./index.php?a_link=implementationdetailed">Implementation Detailed Report </a></li>
                    <li><a href="./index.php?a_link=handholddetailed">Hand Hold Detailed Report</a></li>
                  </ul>
                </li>
                <?php } ?>
                <li><a  href="./index.php?a_link=cuscardattachreport">Pin No Attached Details</a></li>
                 <li><a  href="./index.php?a_link=labelcontactdetails">Label Print (Customers) </a></li>
                  <li><a  href="./index.php?a_link=customeranalysis">Data Inaccuracy Report </a></li>
              </ul>
            </LI>
            <LI class=current><a>Profiles</a>
              <ul>
                <li><a href="./index.php?a_link=editprofile">Edit Profile</a> </li>
                <li><a href="./index.php?a_link=changepassword">Change Password</a></li>
              </ul>
            </LI>
            <LI class=current><A href="../logout.php">Logout</A></LI>
          </UL>
          <DIV class=clear></DIV>
        </DIV>
      </DIV></td>
  </tr>
</table>
