<?php


function outputSomeText(){
	$sOutput = "<h1>Here is some text</h1>";
	return $sOutput;
}


/// working with our first function
$sReturnedFromFunction = outputSomeText();
//echo $sReturnedFromFunction;


// working with our second function
$sSecondFunction = outputSomeTextWithArgs("This is a new heading");
//echo $sSecondFunction;

wrapPreTags($sReturnedFromFunction);


function outputSomeTextWithArgs($sArg1){
	$sOutput = "<h3>";
	$sOutput .= $sArg1;
	$sOutput .= "</h3>";
	return $sOutput;
}

function wrapPreTags($arg1){
	echo "<pre>";
	var_dump($arg1);
	echo "</pre>";
}