<?php
if(imaxgetcookie('dealeruserid') == false) { $url = '../index.php'; header("Location:".$url); }
echo(imaxgetcookie('dealeruserid'));
?>
