<?php
include("../config/config.php");
$carnum=$_POST["carnum"];

//ค้นหาว่ามีเลขตัวถังนี้ในระบบหรือยัง
$qrydata=pg_query("select * from \"Fc\" where \"C_CARNUM\"='$carnum'");
if(pg_num_rows($qrydata)==0){ //กรณียังไม่มีสามารถบันทึกได้ปกติ
	echo 1;
}else{
	echo 2;
}

?>