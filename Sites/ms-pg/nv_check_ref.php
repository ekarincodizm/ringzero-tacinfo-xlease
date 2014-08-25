<?php
set_time_limit (0);
ini_set("memory_limit","1024M"); 
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
	$sum=0;
  
	 $sql_in=mssql_query("SELECT *,convert(varchar,ISNULL(P_STDATE, ' '),111) AS STDATE,convert(varchar,ISNULL(P_FDATE,''),111) AS FDATE,convert(varchar,ISNULL(P_CLDATE, ' '),111) AS P_CLDATE,
	  ISNULL(P_VAT,'0')  AS P_VAT,
	  ISNULL(P_MONTH,'0') AS P_MONTH,
	  ISNULL(P_TOTAL,'0')  AS P_TOTAL,
	  ISNULL(P_DOWN,'0')  AS P_DOWN,
	  ISNULL(P_VatOfDown,'0')  AS P_VatOfDown,
	  ISNULL(P_BEGIN,'0')  AS P_BEGIN,
	  ISNULL(TranIDRef1,'') AS TranIDRef1,
	  ISNULL(TranIDRef2,'') AS TranIDRef2,
	  ISNULL (P_CustByYear,'') AS P_CustByYear,
	  ISNULL (P_ACCLOSE,'') AS P_ACCLOSE,
	  ISNULL (P_SL,0) AS P_SL,
	  ISNULL (P_StopVat,'') AS P_StopVat,
	  ISNULL (P_BEGINX,0) AS P_BEGINX 
	  
	  
	  FROM Fp",$conn);
	  
	  
	 while($res_fp=mssql_fetch_array($sql_in))
	  {
	    $res_id=$res_fp["IDNO"];

	 // ข้อมูลจากระบบเก่า
	//$res_fp[IDNO]
	$res_fp[TranIDRef1] = $res_fp[TranIDRef1][0].$res_fp[TranIDRef1][1].$res_fp[TranIDRef1][2].$res_fp[TranIDRef1][3].$res_fp[TranIDRef1][5].$res_fp[TranIDRef1][6].$res_fp[TranIDRef1][7].$res_fp[TranIDRef1][8];
	$res_fp[TranIDRef2] = $res_fp[TranIDRef2][0].$res_fp[TranIDRef2][1].$res_fp[TranIDRef2][2].$res_fp[TranIDRef2][3].$res_fp[TranIDRef2][5].$res_fp[TranIDRef2][6].$res_fp[TranIDRef2][7].$res_fp[TranIDRef2][8];
	 
	$sql_new = pg_query("SELECT \"IDNO\",\"TranIDRef1\",\"TranIDRef2\" FROM \"Fp\" WHERE \"IDNO\" = '$res_id'");
	$res_new=pg_fetch_array($sql_new);
	// $res_new[TranIDRef1]
	// $res_new[TranIDRef2]
	 
	if(($res_fp[TranIDRef1] != $res_new[TranIDRef1]) || ($res_fp[TranIDRef2] != $res_new[TranIDRef2]))
	{
		echo $res_id."......".$res_fp[TranIDRef1]." => ".$res_new[TranIDRef1]."  &  ".$res_fp[TranIDRef2]." => ".$res_new[TranIDRef2];
		echo "</br>";
		$sum++;
	}
 	
}
  echo $sum;
  ?>


</body>
</html>
