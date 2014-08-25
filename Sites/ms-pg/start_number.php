<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
include("config/config.php");
     $sql_del="TRUNCATE TABLE number_id";
	 if($res_del=mssql_query($sql_del,$conn))
		  {
			$st= "Empty data from $_GET[id]. OK";
		  }
		  else
		  {
			$st= "error at ".$ins_fa1;
		  }	 

	 $ins_cus=mssql_query("insert into number_id (typeID,run_number)values('C','0')",$conn);
	 $ins_cus=mssql_query("insert into number_id (typeID,run_number)values('TAX','0')",$conn);
	 $ins_cus=mssql_query("insert into number_id (typeID,run_number)values('CAR','0')",$conn);
	 $ins_cus=mssql_query("insert into number_id (typeID,run_number)values('GAS','0')",$conn);
	 echo "finish insert number_id ";
?>
<button onclick="javascript:window.close();">CLOSE</button>
</body>
</html>
