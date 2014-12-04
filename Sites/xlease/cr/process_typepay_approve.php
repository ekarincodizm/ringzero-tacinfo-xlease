<?php 
session_start();

include("../config/config.php");
include("../nw/function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>
<?php

	$autoID = pg_escape_string($_POST["autoID"]);
	$appvNote = pg_escape_string($_POST["appvNote"]);
	
	$appvNote_checknull = checknull($appvNote);

	$id_user = $_SESSION["av_iduser"]; // รับ id ผู้ใช้
	$logs_any_time = nowDateTime(); // วันที่ปัจจุบัน
		
	if(isset($_POST["btn_appv"])){
		$appvStatusNew = "1";   //อนุมัติ
	}else{
		$appvStatusNew = "0";//ไม่อนุมัติ
	}

	pg_query("BEGIN");
	$status = 0;
	
	// ตรวจสอบก่อนว่ามีการอนุมัติไปก่อนหน้านี้แล้วหรือยัง
	$qry_chkStatus = pg_query("select \"appvStatus\", \"ActionRequest\" from \"TypePay_Request\" where \"autoID\"= '$autoID' ");
	$appvStatusOld = pg_fetch_result($qry_chkStatus,0); // สถานะการอนุมัติเดิม
	$ActionRequest = pg_fetch_result($qry_chkStatus,1); // ประเภทการทำรายการ
	if($appvStatusOld == "1")
	{
		$status++;
		$error = "มีการ อนุมัติ ไปก่อนหน้านี้แล้ว";
	}
	elseif($appvStatusOld == "0")
	{
		$status++;
		$error = "มีการ ปฏิเสธ ไปก่อนหน้านี้แล้ว";
	}
	else
	{
		//--- อนุมัติ / ไม่อนุมัติ รายการ
		$qry_up = pg_query("
							UPDATE
								\"TypePay_Request\"
							SET
								\"appvStatus\" = '$appvStatusNew',
								\"appvID\" = '$id_user',
								\"appvStamp\" = '$logs_any_time',
								\"appvNote\" = $appvNote_checknull
							WHERE
								\"autoID\" = '$autoID' AND
								\"appvStatus\" = '9'
							");
		if($qry_up){}else{$status++;}
		//---
			
		if($appvStatusNew == "1") // ถ้าอนุมัติ
		{
			if($ActionRequest == "I")
			{
				$qry_up = pg_query("
									INSERT INTO \"TypePay\"(
										\"TypeID\",
										\"TName\",
										\"UseVat\",
										\"TypeRec\",
										\"TypeDep\"
									)
									SELECT
										\"TypeID\",
										\"TName\",
										\"UseVat\",
										\"TypeRec\",
										\"TypeDep\"
									FROM
										\"TypePay_Request\"
									WHERE
										\"autoID\" = '$autoID' AND
										\"appvStatus\" = '1' 
									");
				if($qry_up){}else{$status++;}
			}
			elseif($ActionRequest == "U")
			{
				$qry_up = pg_query("
									UPDATE
										\"TypePay\"
									SET
										\"TName\" = (select \"TName\" from \"TypePay_Request\" where \"autoID\" = '$autoID' and \"appvStatus\" = '1'),
										\"UseVat\" = (select \"UseVat\" from \"TypePay_Request\" where \"autoID\" = '$autoID' and \"appvStatus\" = '1'),
										\"TypeRec\" = (select \"TypeRec\" from \"TypePay_Request\" where \"autoID\" = '$autoID' and \"appvStatus\" = '1'),
										\"TypeDep\" = (select \"TypeDep\" from \"TypePay_Request\" where \"autoID\" = '$autoID' and \"appvStatus\" = '1')
									WHERE
										\"TypeID\" = (select \"TypeID\" from \"TypePay_Request\" where \"autoID\" = '$autoID' and \"appvStatus\" = '1')
									");
				if($qry_up){}else{$status++;}
			}
			else
			{
				$status++;
			}
		}
	}

if($status == 0){
	pg_query("COMMIT");
	
	//ACTIONLOG
		if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', 'อนุมัติ TypePay', '$logs_any_time')")); else $status++;
	//ACTIONLOG---
	
	echo "<center>";
	echo "<font color=\"#0000FF\">บันทึกสำเร็จ</font>";
	echo "<br/><br/>";
	echo "<input type=\"button\" value=\"ตกลง\" style=\"cursor:pointer;\" onClick=\"RefreshMe();\" />";
	echo "</center>";
}else{
	pg_query("ROLLBACK");
	echo "<center>";
	echo "<font color=\"#FF0000\">บันทึกผิดพลาด!! $error</font>";
	echo "<br/><br/>";
	echo "<input type=\"button\" value=\"ปิด\" style=\"cursor:pointer;\" onClick=\"RefreshMe();\" />";
	echo "</center>";
}	
?>