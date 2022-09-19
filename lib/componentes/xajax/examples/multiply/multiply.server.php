<?php
// multiply.php, multiply.common.php, multiply.server.php
// demonstrate a very basic xajax implementation
// using xajax version 0.2
// http://xajaxproject.org

function multiply($x, $y)
{
	$objResponse = new xajaxResponse();
	$objResponse->addAssign("z", "value", $x*$y);
	return $objResponse;
}

function porcentaje($x, $y)
{
	$objResponse = new xajaxResponse();
	
	$val=$x*($y/100);
	
	$objResponse->addAssign("z", "value", $val);
	$objResponse->addScript("messageBox($val)");
	return $objResponse;
}
require("multiply.common.php");
$xajax->processRequests();
?>