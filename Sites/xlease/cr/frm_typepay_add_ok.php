<?php
session_start();
include("../config/config.php");
include("../nw/function/checknull.php");

$typeid = pg_escape_string($_POST['typeid']);
$tname = pg_escape_string($_POST['tname']);
$uservat = pg_escape_string($_POST['uservat']);
$typerec = pg_escape_string($_POST['typerec']);
$typepay = pg_escape_string($_POST['typepay']);

// ตรวจสอบค่าว่าง
$typeid_checknull = checknull($typeid);
$tname_checknull = checknull($tname);
$uservat_checknull = checknull($uservat);
$typerec_checknull = checknull($typerec);
$typepay_checknull = checknull($typepay);

$id_user = $_SESSION["av_iduser"]; // รับ id ผู้ใช้
$logs_any_time = nowDateTime(); // วันที่ปัจจุบัน
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบ TypePay</h1></div>
<div class="wrapper">

<?php
    
	pg_query("BEGIN");
	$status = 0;
	
	// ตรวจสอบ Concurrency
	$qry_chk_TypeID = pg_query("select * from \"TypePay_Request\" where \"TypeID\" = '$typeid' and \"appvStatus\" = '9'");
	$row_chk_TypeID = pg_num_rows($qry_chk_TypeID);
	if($row_chk_TypeID > 0)
	{
		$status++;
		echo "มี TypeID = '$typeid' รออนุมัติอยู่แล้ว<br/>";
	}
	else
	{
		// ตรวจสอบ Concurrency
		$qry_chk_TName = pg_query("select * from \"TypePay_Request\" where \"TName\" = '$tname' and \"appvStatus\" = '9'");
		$row_chk_TName = pg_num_rows($qry_chk_TName);
		if($row_chk_TName > 0)
		{
			$status++;
			echo "มี TName = '$tname' รออนุมัติอยู่แล้ว<br/>";
		}
		else
		{
			$in_sql = "
						INSERT INTO \"TypePay_Request\"(
							\"TypeID\",
							\"TName\",
							\"UseVat\",
							\"TypeRec\",
							\"TypeDep\",
							\"ActionRequest\",
							\"doerID\",
							\"doerStamp\",
							\"appvStatus\"
						)
						VALUES(
							$typeid_checknull,
							$tname_checknull,
							$uservat_checknull,
							$typerec_checknull,
							$typepay_checknull,
							'I',
							'$id_user',
							'$logs_any_time',
							'9'
						)
					";    
			if($result=pg_query($in_sql)){
			}else{
				$status++;
			}
		}
	}
	
	if($status == 0){
		pg_query("COMMIT");
		
		//ACTIONLOG
			if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', 'ระบบ TypePay', '$logs_any_time')")); else $status++;
		//ACTIONLOG---
	
		echo "บันทึกเรียบร้อยแล้ว"; 
    }else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกได้";
    }

?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_typepay_add.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>