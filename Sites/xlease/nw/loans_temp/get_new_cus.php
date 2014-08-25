<?php
include("../../config/config.php");

$prefix = $_POST['prefix'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];

$qr = pg_query("select \"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" from \"Fa1\" where \"A_FIRNAME\"='$prefix' and \"A_NAME\"='$fname' and \"A_SIRNAME\"='$lname'");
if($qr)
{
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		$rs = pg_fetch_array($qr);
		$CusID = trim($rs['CusID']);
		$A_FIRNAME = trim($rs['A_FIRNAME']);
		$A_NAME = trim($rs['A_NAME']);
		$A_SIRNAME = trim($rs['A_SIRNAME']);
		
		$join = $CusID."#".$A_FIRNAME.$A_NAME." ".$A_SIRNAME;
		echo $join;
	}
}
?>