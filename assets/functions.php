<?php

function plainEcho($pre, $this, $post) {
	if ($this != '') {
		echo $pre . $this . $post;
	}
}

function convert($amount, $from, $to) {	 
	 
	//make string to be put in API
	$string = $amount.$from."=?".$to;
	 
	//Call Google API
	$google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;
	 
	//Get and Store API results into a variable
	$result = file_get_contents($google_url);
	 
	//Explode result to convert into an array
	$result = explode('"', $result);
	 
	################################
	# Right Hand Side
	################################
	$converted_amount = explode(' ', $result[3]);
	$conversion = $converted_amount[0];
	$conversion = preg_replace('/[^0-9,.]/', '', $conversion);
	 
	//Get text for converted currency
	$rhs_text = ucwords(str_replace($converted_amount[0],"",$result[3]));
	 
	//Make right hand side string
	$rhs = $conversion.$rhs_text;
	 
	################################
	# Make the result
	################################
	 
	return round($conversion, 2);
}

function moneyEcho($this, $locale) {
	$cost = round($this, 2);
    $money = utf8_encode(money_format('%.2', $cost));
	switch($locale) {
		case "en_US":
			$from = "USD";
			$to = "EUR";
			break;
		case "pt_PT":
			$from = "EUR";
			$to = "USD";
			break;
		case "en_GB":
			$from = "GBP";
			$to = "EUR";
			break;
		case "fr_LU":
			$from = "EUR";
			$to = "USD";
			break;
	}
	$converted = convert($cost, $from, $to);
	echo '<a href="#" rel="tooltipRight" title="' . $to . ' ' . $converted . '">' . $from . " " . $cost . '</a>';
}

?>