<?php

$variable = "new value";

$sMyString = <<<EOS
<h1>This 'is' a heading</h1>
<p>This is a "paragraph"</p>
<p>This is another paragraph with a $variable</p>
EOS;

echo $sMyString;
?>