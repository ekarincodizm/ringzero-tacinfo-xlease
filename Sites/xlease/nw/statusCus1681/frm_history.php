<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}


</script>

</head>
<body>

<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#CDC9C9">
	<tr><td colspan="8" bgcolor="#FFFFFF" align="center"><h2>ประวัติการอนุมัติแก้ไขรับชำระชั่วคราว 1681</h2></td></tr>
	<tr align="center" bgcolor="#8B8989" style="color:#FFFFFF" height="25">
		<th>ที่</th>
		<th>เลขที่สัญญา</th>
		<th>เลขที่ใบเสร็จ</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>ผู้อนุมัติ</th>
		<th>วันเวลาที่อนุมัติ</th>
		<th>สถานะ</th>
	</tr>
	<?php	
	$query = pg_query("SELECT \"tacID_Old\", \"tacXlsRecID_Old\",\"tacID\", \"tacXlsRecID\",  
	\"tacTempDate\", b.\"fullname\" as req_user,c.\"fullname\" as app_user, \"req_stamp\" ,\"app_stamp\",\"statusApp\"
	FROM \"tacReceiveTemp_waitedit\" a
	LEFT JOIN \"Vfuser\" b on a.\"req_user\"=b.\"id_user\" 
	LEFT JOIN \"Vfuser\" c on a.\"app_user\"=c.\"id_user\" 
	WHERE \"statusApp\" NOT IN ('2','3')
	GROUP BY \"tacID_Old\", \"tacXlsRecID_Old\",\"tacID\",\"tacXlsRecID\",  
	\"tacTempDate\", b.\"fullname\",c.\"fullname\", \"req_stamp\" ,\"app_stamp\",\"statusApp\" order by \"app_stamp\" DESC");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$tacID_Old = $result["tacID_Old"]; //เลขที่สัญญาก่อนแก้ไข
		$tacXlsRecID_Old = $result["tacXlsRecID_Old"]; // เลขที่ใบเสร็จก่อนแก้ไข
		$tacID = $result["tacID"]; //เลขที่สัญญาก่อนแก้ไข
		$tacXlsRecID = $result["tacXlsRecID"]; // เลขที่ใบเสร็จก่อนแก้ไข
		$tacTempDate = $result["tacTempDate"]; // วันที่ชำระ
		$req_user = $result["req_user"]; // รหัสพนักงานที่ขอแก้ไข		
		$req_stamp = $result["req_stamp"]; // วันเวลาที่ขอแก้ไข
		$app_user = $result["app_user"]; // ผู้อนุมัติ
		$app_stamp = $result["app_stamp"]; // ผู้อนุมัติ
		$statusApp = $result["statusApp"]; // สถานะการอนุมัติ
		
		if($statusApp=="1"){
			$txtapp="อนุมัติ";
		}else{
			$txtapp="ไม่อนุมัติ";
		}
		
		if($i%2==0){
			echo "<tr height=\"25\" bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFE4C4';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr height=\"25\" bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFE4C4';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\"><span onclick=\"javascript:popU('frm_PaymentChk.php?car=$tacID_Old','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" title=\"รายละเอียดรับชำระแทน 1681\" style=\"cursor:pointer\"><u>$tacID_Old</u></span></td>";
		echo "<td align=\"center\">$tacXlsRecID_Old</td>";
		echo "<td align=\"center\">$req_user</td>";		
		echo "<td align=\"center\">$req_stamp</td>";
		echo "<td align=\"center\">$app_user</td>";
		echo "<td align=\"center\">$app_stamp</td>";
		echo "<td align=\"center\">$txtapp</td>";
		echo "</tr>";
		
	}
	if($numrows==0){
		echo "<tr bgcolor=#EEE9E9 height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#EEE9E9\" height=25><td colspan=10><b>ข้อมูลทั้งหมด $numrows รายการ</b></td><tr>";
	}
	?>
</table>
<div style="text-align:center;padding-top:10px;"><input type="button" value="ปิด" onclick="window.close();" style="width:100px;height:30px;"></div>
</body>
</html>