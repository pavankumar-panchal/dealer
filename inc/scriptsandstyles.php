<?php
	echo('<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">');

	if(file_exists("../style/main.css")) echo('<LINK href="../style/main.css" rel=stylesheet>');
	elseif(file_exists("../../style/main.css")) echo('<LINK href="../../style/main.css" rel=stylesheet>');
	elseif(file_exists("../../../style/main.css")) echo('<LINK href="../../../style/main.css" rel=stylesheet>');
	elseif(file_exists("./style/main.css")) echo('<LINK href="./style/main.css" rel=stylesheet>');

echo("\n");	
	if(file_exists("../functions/jquery.js")) echo('<SCRIPT src="../functions/jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/jquery.js")) echo('<SCRIPT src="../../functions/jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/jquery.js")) echo('<SCRIPT src="../../../functions/jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/jquery.js")) echo('<SCRIPT src="./functions/jquery.js" type=text/javascript></SCRIPT>');
	
echo("\n");	
	if(file_exists("../functions/jquery-xtra.js")) echo('<SCRIPT src="../functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/jquery-xtra.js")) echo('<SCRIPT src="../../functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/jquery-xtra.js")) echo('<SCRIPT src="../../../functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/jquery-xtra.js")) echo('<SCRIPT src="./functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	

echo("\n");	

	if(file_exists("../functions/main.js")) echo('<SCRIPT src="../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/main.js")) echo('<SCRIPT src="../../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/main.js")) echo('<SCRIPT src="../../../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/main.js")) echo('<SCRIPT src="./functions/main.js" type=text/javascript></SCRIPT>');
	

echo("\n");	

	
/*	if(file_exists("../functions/highcharts-jquery.js")) echo('<SCRIPT src="../functions/highcharts-jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/highcharts-jquery.js")) echo('<SCRIPT src="../../functions/highcharts-jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/highcharts-jquery.js")) echo('<SCRIPT src="../../../functions/highcharts-jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/highcharts-jquery.js")) echo('<SCRIPT src="./functions/highcharts-jquery.js" type=text/javascript></SCRIPT>');
*/	
	
echo("\n");	

	if(file_exists("../functions/javascript.js")) echo('<SCRIPT src="../functions/javascript.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/javascript.js")) echo('<SCRIPT src="../../functions/javascript.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/javascript.js")) echo('<SCRIPT src="../../../functions/javascript.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/javascript.js")) echo('<SCRIPT src="./functions/javascript.js" type=text/javascript></SCRIPT>');
	
	
echo("\n");	

/*	if(file_exists("../functions/lookout.js")) echo('<SCRIPT src="../functions/lookout.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/lookout.js")) echo('<SCRIPT src="../../functions/lookout.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/lookout.js")) echo('<SCRIPT src="../../../functions/lookout.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/lookout.js")) echo('<SCRIPT src="./functions/lookout.js" type=text/javascript></SCRIPT>');
*/
	

	if(file_exists("../functions/cookies.js")) echo('<SCRIPT src="../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/cookies.js")) echo('<SCRIPT src="../../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/cookies.js")) echo('<SCRIPT src="../../../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/cookies.js")) echo('<SCRIPT src="./functions/cookies.js" type=text/javascript></SCRIPT>');
	
echo("\n");	

?>
