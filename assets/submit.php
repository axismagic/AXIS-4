<?php
	
	$log = "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - \n";
	$log .= "MESSAGE SENT FAILED";
	$log .= "\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - \n";
	$log .= "Date: " . date("D M j G:i:s T Y") . " | Estimate ID: " . $_POST['estimateId'];
	$log .= "\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - \n\n";
	
	$body = "Someone (likely the customer) made a comment on an estimate. Check if you need to get back to him... \n\n";
	
	foreach($_POST as $key => $value){
		$body .= $key.": ".$value . " \n";
	}
	
	$log .= $body;
	
	$to = "you@provider.com";
	$subject = "Eduardo Nunes - Estimate - Comment on " . $_POST['estimateId'];
	if (mail($to, $subject, $body)) {

      		echo json_encode(true);
	} else {
		$myFile = "log.txt";
		$fh = fopen($myFile, 'a') or die("can't open file");
		$log .= "Message failed! Please check with customer";
		$log .= "\n\n\n\n";
		fwrite($fh, $log);
		fclose($fh);
	}

?>
