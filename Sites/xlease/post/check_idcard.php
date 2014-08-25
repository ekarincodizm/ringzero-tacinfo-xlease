<?php
include("../config/config.php");
$f_idcard=$_POST["f_idcard"];

//ค้นหาว่ามีเลขบัตรประชาชนระบบหรือยัง
$qrydata=pg_query("select * from \"Fn\" where \"N_IDCARD\" like '$f_idcard' or \"N_CARDREF\" like '$f_idcard'");
if(pg_num_rows($qrydata)==0){ //กรณียังไม่มีสามารถบันทึกได้ปกติ
	echo 1;
}else{
	echo 2;
}

?>