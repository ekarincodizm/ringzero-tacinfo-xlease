<?php
include("../../config/config.php");
?>

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 

<?php

$editID=$_POST['editID'];

$CusID=$_POST['CusID'];
$A_PAIR=$_POST['A_PAIR'];
$A_NO=$_POST['A_NO'];
$A_SUBNO=$_POST['A_SUBNO'];
$A_SOI=$_POST['A_SOI'];
$A_RD=$_POST['A_RD'];
$A_TUM=$_POST['A_TUM'];
$A_AUM=$_POST['A_AUM'];
$A_PRO=$_POST['A_PRO'];
$A_POST=$_POST['A_POST'];

$CusID_Trim = trim($CusID);
		
$N_AGE=$_POST['N_AGE'];
$N_CARD=$_POST['N_CARD'];
$N_IDCARD=$_POST['N_IDCARD'];
$N_OT_DATE=$_POST['N_OT_DATE'];
$N_BY=$_POST['N_BY'];
$N_SAN=$_POST['N_SAN'];
$N_OCC=$_POST['N_OCC'];
$N_ContactAdd=$_POST['N_ContactAdd'];

//------------ update ข้อมูล
pg_query("BEGIN WORK");
$status = 0;

$test_sql1="update public.\"Fa1\" set \"A_PAIR\"='$A_PAIR' , \"A_PRO\"='$A_PRO', \"A_POST\"='$A_POST'
			, \"A_NO\"='$A_NO', \"A_SUBNO\"='$A_SUBNO', \"A_SOI\"='$A_SOI', \"A_RD\"='$A_RD', \"A_TUM\"='$A_TUM', \"A_AUM\"='$A_AUM'
			where \"CusID\"='$editID'";
if($result1=pg_query($test_sql1)){
	}else{
		$status++;
	}
	
$test_sql2="update public.\"Fn\" set \"N_AGE\"='$N_AGE', \"N_CARD\"='$N_CARD', \"N_IDCARD\"='$N_IDCARD', \"N_OT_DATE\"='$N_OT_DATE'
			, \"N_BY\"='$N_BY', \"N_SAN\"='$N_SAN', \"N_OCC\"='$N_OCC', \"N_ContactAdd\"='$N_ContactAdd'
			where \"CusID\"='$editID'";
if($result2=pg_query($test_sql2)){
	}else{
		$status++;
	}
//---------- จบการ update ข้อมูล
if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h2>บันทึกสำเร็จ</h2></center>";
	echo "<center><input type=\"button\" value=\"     ปิดหน้านี้     \" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>บันทึกผิดพลาด!!</h2></center>";
	echo "<center><input type=\"button\" value=\"  Close  \" class=\"ui-button\" onclick=\"javascript:window.close();\"></center>";
}
?>