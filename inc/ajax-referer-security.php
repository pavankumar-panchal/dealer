<?php

//Security check for Ajax pages

$referurl = parse_url($_SERVER['HTTP_REFERER']);
$referhost = $referurl['host'];


if($referhost <> 'localhost' && $referhost <> 'vijaykumar'  && $referhost <> 'rashmihk' &&  $referhost <> 'archanaab'  && $referhost <> 'imax.relyonsoft.net' && $referhost <> 'www.imax.relyonsoft.net')
{
	echo("Thinking, why u called this page. Anyways, call me on my cell");
	exit;
}

?>