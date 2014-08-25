<?php
session_start();
include("../../config/config.php");
$cid=$_POST["cid"]; //รายการที่จะทำการ Add
$nowdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
pg_query("BEGIN WORK");
$status = 0;

echo "<form method=\"post\" name=\"form1\" action=\"excel_member.php\">";
for($i=0;$i<sizeof($cid);$i++){ 
	$upd="insert into \"Fp_membercard\" (\"IDNO\",\"cardStamp\") values ('$cid[$i]','$nowdate')";
	if($result=pg_query($upd)){
	}else{
		$up_error=$result;
		$status++;
	}
	echo "<input type=\"hidden\" name=\"cid[]\" value=\"$cid[$i]\">";
}
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ออกบัตรสมาชิก', '$nowdate')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<div style=\"padding-top:50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<div style=\"padding:10px;text-align:center;\"><input type=\"submit\" value=\"Export Excel\"><input type=\"button\" value=\"Back\" onclick=\"window.location='frm_Index.php'\"></div>";
}else{
	pg_query("ROLLBACK");
	echo $upd;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='4; URL=frm_Index.php'>";
}
echo "</form>";	
?>