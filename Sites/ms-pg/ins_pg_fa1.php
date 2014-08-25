<?php
set_time_limit (0); 
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ins_pg_fa1</title>
</head>

<body>
<?php
$sql_si=mssql_query("select A.*,B.* from fill_CusID",$conn);
while($res_si=mssql_fetch_array($sql_si))
{
  //echo $res_si["CusID"]."<br>";
  /*SELECT     A.CusID AS CusID, B.A_FIRNAME AS A_FIRNAME, A.p_name AS A_NAME
FROM         fill_CusID A LEFT OUTER JOIN
                      Fa1 B ON B.A_NAME = A.o_name 
					  
SELECT DISTINCT A.CusID, B.A_NO, B.A_SUBNO, B.A_SOI, B.A_RD, B.A_TUM, B.A_AUM, B.A_PRO
FROM         fill_CusID A LEFT OUTER JOIN
                      Fa1 B ON A.o_name = B.A_NAME
 */
  
  
  
  
}

?>
</body>
</html>
