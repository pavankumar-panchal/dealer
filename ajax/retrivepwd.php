<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php'); 
$switchtytpe = $_POST['switchtype'];
switch($switchtytpe)
{
	case 'sendemail':
	{
		$emails = $_POST['emailresult'];
		$dealerid= $_POST['dealerid'];
		$timestamp = date('Y-m-d').' '.date('H:i:s');
		
		//random key value generated
		$key = rand_str();
		
		//md5 value of customerid ,timestamp and the random key generated
		$resultkey = md5($customerid.$timestamp.$keyvalue);
		
		//server side validation
		$query1 = "select inv_mas_dealer.dealerusername ,inv_mas_dealer.businessname, inv_mas_dealer.emailid,inv_mas_dealer.contactperson from inv_mas_dealer where inv_mas_dealer.slno = '".$dealerid."'";
		$result = runmysqlquery($query1);
		if(mysqli_num_rows($result) == 0)
		{
			//Invalid Dealer Username
			echo('2^'.'Invalid Dealer Username');
		}
		else
		{
			$fetch1 = mysqli_fetch_array($result);
			$fetchemail = $fetch1 ['emailid'];
			
			if(strrpos($fetchemail,$emails) === false)
			{
				//Invalid Email ID
				echo('2^'.'Invalid Email ID');
			}
			else
			{
				$dealerusername = $fetch1['dealerusername'];
				$businessname = $fetch1['businessname'];
				$contactperson = $fetch1['contactperson'];
				//$url = "http://meghanab/saralimax-dealer/update/password.php?key=".$resultkey."";
				$url = "http://imax.relyonsoft.net/dealer/update/password.php?key=".$resultkey."";
				$query = "UPDATE inv_mas_dealer SET pwdresetkey='".$resultkey."',pwdresettime='".$timestamp."' where slno = '".$dealerid."'";
				$result = runmysqlquery($query);
					#########  Mailing Starts -----------------------------------
					$emailid = $emails;
					//$emailid = 'meghana.b@relyonsoft.com';
					$emailarray = explode(',',$emailid);
					$emailcount = count($emailarray);
					
					for($i = 0; $i < $emailcount; $i++)
					{
						if(checkemailaddress($emailarray[$i]))
						{
							if($i == 0)
								$emailids[$contactperson] = $emailarray[$i];
							else
								$emailids[$emailarray[$i]] = $emailarray[$i];
						}
					}
					$fromname = "Relyon";
					$fromemail = "imax@relyon.co.in";
					//$toarray = array($contactperson => $emailids);
					require_once("../inc/RSLMAIL_MAIL.php");
					$msg = file_get_contents("../mailcontents/retrivepwd.htm");
					$textmsg = file_get_contents("../mailcontents/retrivepwd.txt");
					$date = datetimelocal('d-m-Y');
					$array = array();
					$array[] = "##DATE##%^%".$date;
					$array[] = "##CONTACTPERSON##%^%".$businessname;
					$array[] = "##USERNAME##%^%".$dealerusername;
					$array[] = "##URL##%^%".$url;
					$filearray = array(
						array('../images/relyon-logo.jpg','inline','1234567890')
					);
					$toarray = $emailids;
					$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
					$bccemailids['bigmail'] ='bigmail@relyonsoft.com';

					$bccarray = $bccemailids;
					$msg = replacemailvariable2($msg,$array);
					$textmsg = replacemailvariable2($textmsg,$array);
					$subject = "Password Retrival | Relyon Dealer Login Area";
					$html = $msg;
					$text = $textmsg;
					rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
					
					//Insert the mail forwarded details to the logs table
					$bccmailid = 'bigmail@relyonsoft.com'; 
					inserttologs($dealerid,$dealerid,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);
					
					echo('1^'.'The URL to reset your login password associated with the Dealer Username '.$dealerusername.' has been emailed successfully to '.$emails.'. Please check your email account for details');
			
				###################  Mailing Ends ------------------------------------------------------------
			}
		}	
	}
	break;
	case 'dealerusername':
		{
			$dealerusername= $_POST['dealerusername'];
			$query2 = "select slno  from inv_mas_dealer where dealerusername = '".$dealerusername."';";
			$result = runmysqlquery($query2);
			if(mysqli_num_rows($result) == 0)
			{
				echo('2^The Dealer Username '.$dealerusername.' is not registered in our Dealer database.Please enter a valid Dealer ID provided by Relyon.');
				break;
			}
			else
			{
				$fetch1 = runmysqlqueryfetch($query2);
				$dealerid= $fetch1['slno'];
			}
			$query = "select emailid,slno  from inv_mas_dealer where dealerusername = '".$dealerusername."';";
			$result = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result))
				{
					$emailarray = explode(',',$fetch['emailid']);
					$emailcount = count($emailarray);
					$value.='<select name="email" id="email" class="swiftselect-mandatory" style="width: 200px;" >';
					$value.= '<option value="">--Select--</option>';
					for($i = 0; $i < $emailcount; $i++)
					{
						$value.='<option value="'.$emailarray[$i].'">'.$emailarray[$i].'</option>';
					}
				$value.='</select>';
				}
				echo('1^'.$value.'^'.$dealerid);
			
		}
			break;
}



?>