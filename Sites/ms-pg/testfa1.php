<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
include("config/config.php");
$sql_list=mssql_query("select TOP 100 A_NAME from Fa1 ",$conn);
while($res_list=mssql_fetch_array($sql_list))
{
 
  echo $i_pname=iconv('WINDOWS-874','UTF-8',$res_list["A_NAME"]);
}
?>
</body>
</html>
