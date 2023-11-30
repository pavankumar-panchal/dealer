<?php

	
	//$log_me = "Add this to the file\n";
	//log_me("hi");
	function log_me($log_me)
	{
		$filename = 'test.txt';
		// Let's make sure the file exists and is writable first.
		
		
			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence
			// that's where $log_me will go when we fwrite() it.
			
			$log_me=  date("d/m/y h:m:s") . " \t " . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']." \t " . $log_me . "\n";
			if (!$handle = fopen($filename, 'a+')) {
				 echo "Cannot open file ($filename)";
				 exit;
			}
		
			// Write $log_me to our opened file.
			if (fwrite($handle, $log_me) === FALSE) {
				echo "Cannot write to file ($filename)";
				exit;
			}
		
			//echo "Success";
		
			fclose($handle);
		
		
	}
?>