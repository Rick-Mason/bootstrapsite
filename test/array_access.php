<?php
include("errors_functions.php");

$aArray = ["foo", "bar", "tig", "tag", "ber", "tos"];
preTags($aArray);

$sVar = $aArray[1];
preTags($sVar);