<?php
	
	$url = "../data/" . $_POST['id'] . ".xml";
	if (file_exists($url)) {

		$xml = simplexml_load_file($url);
		$xml->addAttribute('accepted', 'true');
	  	echo json_encode(true);
		$xml->asXML($url);
		
		$messageBody = "Estimate #" . $_POST['id'] . " was accepted by the customer";
		mail("you@provider.com", "Estimate accepted", $messageBody);
		
	} else {
		
		
	}
		

?>
