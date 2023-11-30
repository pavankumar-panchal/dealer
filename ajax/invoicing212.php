<?php
if($p_invoicing <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	?>
	<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body>

<h1 style="font-size: 30px">Currently pins are not available. Please wait for sometime.</h1>


</body>
</html>
<?php } ?>