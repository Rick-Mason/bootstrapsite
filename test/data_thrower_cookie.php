<?php
$value = "some value";

setcookie("TestCookie", $value, time()+3600);

echo "<a href='data_catcher.php'>click me</a>";
