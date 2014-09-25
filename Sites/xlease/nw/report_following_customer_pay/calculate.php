<?php
$arrayMoney = pg_escape_string($_POST["arrayMoney"]);
$ans = 0;

$s_temp = split("#", $arrayMoney);
for($i=0; $i<count($s_temp); $i++)
{
	$ans += $s_temp[$i];
}

$ans = number_format($ans,2);
echo $ans;
?>