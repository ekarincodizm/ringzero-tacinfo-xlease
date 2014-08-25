<?php
session_start();
include("../../config/config.php");
include("../../nv_function.php");

$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$IDNO = trim($_REQUEST['IDNO']);
$c_carnum = trim($_REQUEST['c_carnum']);

pg_query("BEGIN");
$status=0;

//ref1
$gen1=pg_query("select gen_encode_ref1('$IDNO')");
$resgen1=pg_fetch_result($gen1,0);

//ref2	
$resstnumber=strlen($c_carnum);         
$var_cnumber=substr($c_carnum,$resstnumber-9,9);		

$resgen2 = nv_correct_TranIDRef2($var_cnumber); // รัน function เพื่อแก้ปัญหาที่ TranIDRef2 มีตัว a-z,A-Z,- ติดอยู่ซึ่งผิดหลัก โดย function return ค่าเป็นตัวเลขล้วน เปลี่ยน a-z,A-Z เป็น 0 และ - เป็น 9

$up_ref="Update \"Fp\" SET \"TranIDRef1\"='$resgen1',\"TranIDRef2\"='$resgen2'  WHERE \"IDNO\"='$IDNO' "; 
if($result=pg_query($up_ref)){
}else{
	$status++;
}

if($status==0){	

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) สร้างรหัสโอนเงิน', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "สร้างรหัสโอนเงินเรียบร้อย\n\n";	
	echo "TranIDRef1 = $resgen1\n";
	echo "TranIDRef2 = $resgen2\n";
}else{
	pg_query("ROLLBACK");
	echo "999999999";
}		
		
?>
		
	

