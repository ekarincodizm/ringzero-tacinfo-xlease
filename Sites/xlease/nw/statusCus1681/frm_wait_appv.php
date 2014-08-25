<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
	<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#CDC9A5">
	<?php IF($readonly == 'readonly'){ 
			$txtdetail = 'รายละเอียด';
	?>
		<tr><td colspan="8" bgcolor="#FFFFFF"><b>รายการที่รออนุมัติอยู่ขณะนี้</b></td></tr>		
	<?php }else{ $txtdetail = 'ตรวจสอบ'; } ?>
	
		<tr align="center" bgcolor="#8B8970" style="color:#FFFFFF">
			<th height="25">ที่</th>
			<th>เลขที่สัญญา</th>
			<th>เลขที่ใบเสร็จ</th>
			<th>จำนวนเงินรวม</th>
			<th>วันที่ชำระ</th>
			<th>ผู้ทำรายการ</th>
			<th>วันเวลาที่ทำรายการ</th>
			<th><?php echo $txtdetail; ?></th>
		</tr>
		<?php	
		$query = pg_query("SELECT \"tacID_Old\", \"tacXlsRecID_Old\",\"tacID\", \"tacXlsRecID\", sum(\"tacMoney\") as summoney,  
		\"tacTempDate\", b.\"fullname\", \"req_stamp\" as stampdate,\"req_user\"
		FROM \"tacReceiveTemp_waitedit\" a
		LEFT JOIN \"Vfuser\" b on a.\"req_user\"=b.\"id_user\" 
		WHERE \"statusApp\" IN ('2','3')
		GROUP BY \"tacID_Old\", \"tacXlsRecID_Old\",\"tacID\", \"tacXlsRecID\", \"tacTempDate\",b.\"fullname\", \"req_stamp\",\"req_user\" order by \"req_stamp\"");
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
			$tacOldRecID = $result["tacOldRecID"]; // เลขที่ใบเสร็จ TAC
			$tacMoney = $result["summoney"]; // จำนวนเงินที่จ่ายในเดือนนั้นๆ
			$fullname = $result["fullname"]; // พนักงานที่ขอแก้ไข
			$req_stamp = $result["stampdate"]; // วันเวลาที่ขอแก้ไข
			$req_user = $result["req_user"]; // รหัสพนักงานที่ขอแก้ไข
			
			
			
			if($i%2==0){
				echo "<tr bgcolor=\"#EEE9BF\" height=25 onmouseover=\"javascript:this.bgColor = '#EEEEE0';\" onmouseout=\"javascript:this.bgColor = '#EEE9BF';\">";
			}else{
				echo "<tr bgcolor=\"#FFFACD\" height=25 onmouseover=\"javascript:this.bgColor = '#EEEEE0';\" onmouseout=\"javascript:this.bgColor = '#FFFACD';\">";
			}
			
			echo "<td align=\"center\">$i</td>";
			echo "<td align=\"center\"><span onclick=\"javascript:popU('frm_PaymentChk.php?car=$tacID_Old','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" title=\"รายละเอียดรับชำระแทน 1681\" style=\"cursor:pointer\"><u>$tacID_Old</u></span></td>";
			echo "<td align=\"center\">$tacXlsRecID_Old</td>";
			echo "<td align=\"right\">".number_format($tacMoney,2)."</td>";
			echo "<td align=\"center\">$tacTempDate</td>";
			echo "<td align=\"center\">$fullname</td>";		
			echo "<td align=\"center\">$req_stamp</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_ShowEditReceiveDlt.php?tacID=$tacID&tacXlsRecID=$tacXlsRecID&tacID_Old=$tacID_Old&tacXlsRecID_Old=$tacXlsRecID_Old&req_user=$req_user&readonly=$readonly','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1230,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$txtdetail</u></font></a></td>";
			echo "</tr>";
			
			unset($fullname);
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=10 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#EEE8CD\" height=25><td colspan=10><b>ข้อมูลทั้งหมด $numrows รายการ</b></td><tr>";
		}
		?>
	</table>
</body>
</html>	