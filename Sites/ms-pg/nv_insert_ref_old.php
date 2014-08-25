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

	 $sql_in=mssql_query("SELECT * FROM Fp",$conn);
	  
	  
	 while($res_fp=mssql_fetch_array($sql_in))
	  {
	    $res_id=$res_fp["IDNO"];

		// ข้อมูลจากระบบเก่า
		//$res_fp[IDNO]
		$res_fp[TranIDRef1] = $res_fp[TranIDRef1][0].$res_fp[TranIDRef1][1].$res_fp[TranIDRef1][2].$res_fp[TranIDRef1][3].$res_fp[TranIDRef1][5].$res_fp[TranIDRef1][6].$res_fp[TranIDRef1][7].$res_fp[TranIDRef1][8];
		$res_fp[TranIDRef2] = $res_fp[TranIDRef2][0].$res_fp[TranIDRef2][1].$res_fp[TranIDRef2][2].$res_fp[TranIDRef2][3].$res_fp[TranIDRef2][5].$res_fp[TranIDRef2][6].$res_fp[TranIDRef2][7].$res_fp[TranIDRef2][8];
		
		echo "$res_id;$res_fp[TranIDRef1];$res_fp[TranIDRef2])</br>";
		
		// นำข้อมูล TranIDRef1 และ TranIDRef2 เข้า table pmain.new_fp_trans ใน POSTGRES เพื่อใช้ Cross check กับ TranID ที่ถูก Gen ขึ้นมาใหม่
		$sql_new = pg_query("INSERT INTO pmain.new_fp_trans(\"IDNO\", \"TranIDRef1\", \"TranIDRef2\")
								VALUES ('$res_id', '$res_fp[TranIDRef1]', '$res_fp[TranIDRef2]')");
		$sum++;
	}
	echo "ข้อมูลจำนวน : ".$sum." ข้อมูล ได้ถูกคัดลอกลงฐานข้อมูลใหม่แล้ว";

?>


</body>
</html>
