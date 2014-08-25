<?php
//putenv("NLS_LANG=THAI_THAILAND.TH8TISASCII");
putenv("NLS_LANG=AMERICAN_AMERICA.TH8TISASCII");

include("config/config.php");

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<title>Untitled Document</title>
</head>

<body>

<?php

$strSQL = "SELECT * FROM prefix ";
			$objParse = oci_parse($objConnect, $strSQL);
			oci_execute($objParse, OCI_DEFAULT);
		//	$objSelect =  oci_fetch_array($objParse);
		echo "<br>พันธ์";
while($objSelect = oci_fetch_array($objParse)) 
   
{
	echo "ID = $objSelect[PREFIX_ID]<br>";
	echo "NAME = ".$objSelect['PREFIX_NAME']."<br>";
}
$in_qry1="insert into prefix( prefix_id,prefix_name) values('6','test5')";
        $objParse = oci_parse($objConnect, $in_qry1);
			$objExecute =  oci_execute($objParse, OCI_DEFAULT);
			if($objExecute)
{
	oci_commit($objConnect); //*** Commit Transaction ***//
	echo "insert Done.";
}
else
{
	oci_rollback($objConnect); //*** RollBack Transaction ***//
	echo "Error Save [".$strSQL."";
}
$in_qry1="delete from prefix where prefix_id=5";
        $objParse = oci_parse($objConnect, $in_qry1);
			$objExecute =  oci_execute($objParse, OCI_DEFAULT);
			if($objExecute)
{
	oci_commit($objConnect); //*** Commit Transaction ***//
	echo "Del Done.";
}
else
{
	oci_rollback($objConnect); //*** RollBack Transaction ***//
	echo "Error Save [".$strSQL."";
}
$strSQL="update prefix set prefix_name='test' where prefix_id='4'";
$objParse = oci_parse($objConnect, $strSQL);
$r = oci_execute($objParse,OCI_DEFAULT);
			if($r)
{
	oci_commit($objConnect); //*** Commit Transaction ***//
	echo "update Done.";
}
else
{
	oci_rollback($objConnect); //*** RollBack Transaction ***//
	echo "Error Save [".$strSQL."";
}

oci_close($objConnect);

?> 

</body>
</html>

