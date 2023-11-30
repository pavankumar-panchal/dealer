<?php
ob_start("ob_gzhandler");
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php'); 
$switchtytpe = $_POST['switchtype'];
switch($switchtytpe)
{
		case 'retrivepwd':
		{
			$password= $_POST['password'];
			$confirmpwd= $_POST['confirmpwd'];
			$key= $_POST['key'];
			if($password == "" || $confirmpwd == "")
			{
				echo('2^'.'New / confirm passwords cannot be empty.');
			}elseif($password <> $confirmpwd)
			{
				echo('2^'.'New and confirm passwords does not match.');
			}
			$query = "select slno,pwdresetkey,pwdresettime  from inv_mas_dealer where pwdresetkey = '".$key."';";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				echo('2^'.'The Request Key is Invalid.');
				break;
			}
			else
			{
				$fetch = runmysqlqueryfetch($query);
				$slno= $fetch['slno'];
				$currentime = date('Y-m-d').' '.date('H:i:s');
				$requesttime = $fetch['pwdresettime'];
				$interval = 48 * 60 * 60;
				$time2 = strtotime($currentime);
				$time3 = strtotime('+'.$interval.' second '.$requesttime);
				if($time2 <=$time3)
				{
					$query = "UPDATE inv_mas_dealer SET loginpassword=AES_ENCRYPT('".$confirmpwd."','imaxpasswordkey'),pwdresetkey = '',pwdresettime = '',passwordchanged = 'Y' where slno = '".$slno."'";
					$result = runmysqlquery($query);
					echo('1^'.'Password Updated Sucessfully');
				}
				else
				echo('2^'.'The Request Key has been expired. Please place a password request again.');
			}
			
			
		}
		break;
			
}

?>