<?php
session_start();
if((imaxgetcookie('sessionkind') <> false) && (imaxgetcookie('dealeruserid') <> false))
{
	$cookie_logintype = imaxgetcookie('sessionkind');
	$cookie_username = imaxgetcookie('dealeruserid');
}
else
{
	echo(json_encode('Thinking to redirect'));
	exit;
}

$checklogin_data = "select disablelogin from inv_mas_dealer where slno = '$cookie_username'";
$result_checklogin = runmysqlqueryfetch($checklogin_data);
$result_check_disable = $result_checklogin['disablelogin'];
if($result_check_disable == 'yes')
{
    header("Location: http://imax.relyonsoft.net/dealer/logout.php");
}

switch($cookie_logintype)
{
	case "logoutforthreemin":
		if($_SESSION['verificationid'] == '4563464364365')
		{
			ini_set('session.gc_maxlifetime',180);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',1);
			$sessionCookieExpireTime = 180;
			session_start();
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		}
		else
		{
			echo(json_encode('Thinking to redirect'));
			exit;
		}
		break;

	case "logoutforsixhr":
		if($_SESSION['verificationid'] == '4563464364365')
		{
			ini_set('session.gc_maxlifetime',21600);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',1);
			$sessionCookieExpireTime = 21600;
			session_start();
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		}
		else
		{
			echo(json_encode('Thinking to redirect'));
			exit;
		}
		break;

	case "logoutforever":
		//session_start();
		break;
	case "logoutforonehour":
		header("Location: http://imax.relyonsoft.net/dealer/logout.php");
		break;		
	
	default:
		echo(json_encode('Thinking to redirect'));
}
?>