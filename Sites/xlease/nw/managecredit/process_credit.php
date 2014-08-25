<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$method=$_POST["method"];
$KeyDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$creditID = $_POST['creditID'];
$creditID2 = $_POST['creditID2'];
$creditType = $_POST['creditType'];
$creditReserved = $_POST['creditReserved'];
$creditDetail = $_POST['creditDetail'];
$statusUse = $_POST['statusUse'];
$oldIDNO = $_POST['oldIDNO'];

if($creditReserved=="1"){
	$guide="'".$creditReserved."'";
}else{
	$guide="null";
}

if($oldIDNO=="1"){
	$oldIDNO=1;
}else{
	$oldIDNO=0;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body style="background-color:#ffffff; margin-top:0px;">

	<?php
	pg_query("BEGIN WORK");
	$status = 0;
 
	if($method == "add"){
		$url="frm_Index.php";
		$in_sql="insert into \"nw_credit\" (\"creditID\",\"creditType\",\"creditReserved\",\"creditDetail\",\"statusUse\",\"createDate\",\"id_user\",\"oldidno\") values ('$creditID','$creditType',$guide,'$creditDetail','$statusUse','$KeyDate','$id_user',$oldIDNO)";
		if($result=pg_query($in_sql)){		
		}else{
			$status += 1;
			$error=$in_sql;
		}
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) เพิ่มประเภทสินเชื่อเช่าซื้อ', '$KeyDate')");
		//ACTIONLOG---
	}else if($method=="edit"){
		$url="frm_Index2.php";
		$up_sql="update \"nw_credit\" set \"creditID\"='$creditID',
						\"creditType\"='$creditType',
						\"creditReserved\"=$guide,
						\"creditDetail\"='$creditDetail',
						\"statusUse\"='$statusUse',
						\"createDate\"='$KeyDate',
						\"id_user\"='$id_user',
						\"oldidno\"=$oldIDNO
						where \"creditID\"='$creditID2'";
		if($result=pg_query($up_sql)){		
		}else{
			$status += 1;
			$error=$up_sql;
		}
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) แก้ไขประเภทสินเชื่อเช่าซื้อ', '$KeyDate')");
		//ACTIONLOG---
	
	}

	if($status == 0){
		
		pg_query("COMMIT");
		echo "<div style=\"padding-top:50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
		echo "<meta http-equiv='refresh' content='2; URL=$url'>";
	}else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br>";
		echo $error;
		echo "<meta http-equiv='refresh' content='5; URL=frm_editCredit.php?creditID=$creditID'>";
	}		
	
?>



</body>
</html>