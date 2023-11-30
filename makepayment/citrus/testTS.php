<?php 

set_include_path('../lib'.PATH_SEPARATOR.get_include_path());
require_once('../lib/CitrusPay.php');

if(isset($_POST['submit']))
{
	$merchantAccessKey = $_POST['merchantAccessKey'];
	$txnStartDate = $_POST['txnStartDate'];
	$txnEndDate = $_POST['txnEndDate'];
	$bankName = $_POST['bankName'];

	$tarr = array(
			"merchantAccessKey" => "$merchantAccessKey",
			"txnStartDate" => "$txnStartDate",
			"txnEndDate" => "$txnEndDate",
			"bankName" => "$bankName",
	);

	CitrusPay::setApiKey("557976be2080d7560fd3e4e5a47a711c566f3eac",'sandbox');

	$response = TransactionSearch::create($tarr,CitrusPay::getApiKey());
	
	if($response->get_resp_code() == 200)
	{
		$ts = $response->get_transaction();
	
		$html = "<div>";
		$html = "<h3>Transaction List</h3>";
		$html .= "<ul id=\"chkoutPageUserPramList\" class=\"tbl-wrapper clearfix\">" ;
		$html .= "<li class=\"tbl-header\">";
		$html .= "<div class=\"tbl-col col-1\">TXN Resp Code</div>";
		$html .= "<div class=\"tbl-col col-2\">TXN Resp Msg</div>";
		$html .= "<div class=\"tbl-col col-3\">Merchant TXN ID</div>";
		$html .= "<div class=\"tbl-col col-4\">TXN ID</div>";
		$html .= "<div class=\"tbl-col col-5\">AUTH ID</div>";
		$html .= "<div class=\"tbl-col col-6\">PG TXN ID</div>";
		$html .= "<div class=\"tbl-col col-7\">RRN</div>";
		$html .= "<div class=\"tbl-col col-8\">TXN TYPE</div>";
		$html .= "<div class=\"tbl-col col-9\">TXN DATE</div>";
		$html .= "<div class=\"tbl-col col-10\">Amount</div>";
		$html .= "</li>";
	
		for($i=0;$i < count($ts);++$i)
		{
			$tsObj = $ts[$i];
			$html .= "<li>";
			$html .= "<div class=\"tbl-col col-1\">".$tsObj->get_resp_code()."</div>";
			$html .= "<div class=\"tbl-col col-2\" title=\"".$tsObj->get_resp_msg()."\">".$tsObj->get_resp_msg()."</div>";
			$html .= "<div class=\"tbl-col col-3\" title=\"".$tsObj->get_merchant_txn_id()."\">".$tsObj->get_merchant_txn_id()."</div>";
			$html .= "<div class=\"tbl-col col-4\" title=\"".$tsObj->get_txn_id()."\">".$tsObj->get_txn_id()."</div>";
			$html .= "<div class=\"tbl-col col-5\" title=\"".$tsObj->get_auth_id_code()."\">".$tsObj->get_auth_id_code()."</div>";
			$html .= "<div class=\"tbl-col col-6\" title=\"".$tsObj->get_pg_txn_id()."\">".$tsObj->get_pg_txn_id()."</div>";
			$html .= "<div class=\"tbl-col col-7\" title=\"".$tsObj->get_rrn()."\">".$tsObj->get_rrn()."</div>";
			$html .= "<div class=\"tbl-col col-8\">".$tsObj->get_txn_type()."</div>";
			$html .= "<div class=\"tbl-col col-9\">".$tsObj->get_txn_date_time()."</div>";
			$html .= "<div class=\"tbl-col col-10\">".$tsObj->get_amount()."</div>";
			$html .= "</li>";
		}
		$html .= "</ul>";
		$html .= "</div>";
	}
	else
	{
		$html = "<p>Response Code is ".$response->get_resp_code()."</p>";
		$html .= "<p>Response Message is ".$response->get_resp_msg()."</p>";
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Transaction Search</title>
<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="page-header">
		<div class="page-wrap">
			<div class="logo-wrapper">
				<a href="http://www.citruspay.com/"> <img height="32" width="81"
					src="images/logo_citrus.png" alt="Citrus" />
				</a>
			</div>
		</div>
	</div>

	<div id="page-client-logo">&#160;</div>
	<div id="page-wrapper">
		<div class="box-white">
			<div class="page-content">
				<div>
					<ul class="form-wrapper add-merchant clearfix">
						<?php
						if(!isset($_POST['submit']))
						{
							?>
						<form action="testTS.php" method="POST" name="TransactionForm">
							<li class="clearfix"> <label width="125px;"> Merchant Access Key:</label><input class="text" name="merchantAccessKey" type="text"
									value="" />
							</li>
							<li class="clearfix"> <label width="125px;"> Transaction Start Date:</label><input class="text"
									name="txnStartDate" type="text" value="" /> &nbsp;&nbsp;&nbsp;(YYYYMMDD)
							</li>
							<li class="clearfix"> <label width="125px;"> Transaction End Date:</label><input class="text" name="txnEndDate"
									type="text" value="" /> &nbsp;&nbsp;&nbsp;(YYYYMMDD)
							</li>
							<input name="bankName" type="hidden" value="ABC" />
							<li class="clearfix"> <label width="125px;"></label>
								<input type="submit" class="btn-orange" name="submit" value="Test Transaction" /> <input
									type="reset" class="btn" name="reset" value="Cancel" />
							</li>

						</form>
						<?php 
						}
						else
						{
							echo $html;
						}
						?>
					</ul>
				</div>
			</div>
			<div
				style="padding-left: 700px; padding-bottom: 20px; padding-top: 20px;">
				<div>Copyrights © 2012 Citrus.</div>
			</div>
		</div>
	</div>
</body>
</html>
