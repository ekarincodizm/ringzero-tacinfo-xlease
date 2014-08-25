<?php
set_time_limit (0); 
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>list fp</title>
</head>

<body>
<?php
 $yy=$_POST["f_year"];
 $sql_fp=mssql_query("select * from  Fp WHERE  P_CustByYear='$yy'",$conn);
 //echo $sql_fp;
  while($res_name=mssql_fetch_array($sql_fp))
  { 
   echo $res_name["IDNO"]."<br>";
  } 
?>
</body>
</html>
