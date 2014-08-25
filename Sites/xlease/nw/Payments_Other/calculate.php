<?php
$operation_one = $_POST["operation_one"];
$operation_two = $_POST["operation_two"];
$operator = $_POST["operator"];
$temp_k = $_POST["temp_k"];
$temp_t1k = $_POST["temp_t1k"];
$temp_sumwht = $_POST["temp_sumwht"];
$temp_ksum = $_POST["temp_ksum"];
$ans = 0;

if($operator == "plus")
{
	$ans = $operation_one + $operation_two;
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
elseif($operator == "minus")
{
	$ans = $operation_one - $operation_two;
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
elseif($operator == "multiply")
{
	$ans = $operation_one * $operation_two;
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
elseif($operator == "divide")
{
	$ans = $operation_one / $operation_two;
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
elseif($operator == "k")
{
	$s_temp = split("#", $temp_k);
	$numTest = count($s_temp);
	for($i=0; $i<$numTest; $i++)
	{
		$ans += $s_temp[$i];
	}
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
elseif($operator == "t1k")
{
	$s_temp = split("#", $temp_t1k);
	$numTest = count($s_temp);
	for($i=0; $i<$numTest; $i++)
	{
		$ans += $s_temp[$i];
	}
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
elseif($operator == "sumwht")
{
	$s_temp = split("#", $temp_sumwht);
	$numTest = count($s_temp);
	for($i=0; $i<$numTest; $i++)
	{
		$ans += $s_temp[$i];
	}
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
elseif($operator == "ksum")
{
	$s_temp = split("#", $temp_ksum);
	$numTest = count($s_temp);
	for($i=0; $i<$numTest; $i++)
	{
		$ans += $s_temp[$i];
	}
	$ans =  number_format($ans,2);
	$ans = str_replace(",", "", $ans);
	echo $ans;
}
?>