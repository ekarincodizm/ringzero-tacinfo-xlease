<?php
require_once("../../config/config.php");

$tab_id = pg_escape_string($_GET['tabid']); //id contype ที่ต้องการให้แสดง
$s=pg_escape_string($_GET['s']); //รายการที่ต้องการให้แสดง

if($s==1){ //ใบแจ้งหนี้ที่ถึงกำหนดส่ง
	echo "
	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\" bgcolor=\"#CDC1C5\">
		<tr bgcolor=\"#8B8386\" style=\"color:#FFF\">
			<th>รหัสใบแจ้งหนี้</th>
			<th>เลขที่สัญญา</th>
			<th>ชื่อผู้กู้หลัก</th>
			<th>วันที่กำหนดส่งใบแจ้งหนี้</th>
			<th>วันที่ครบกำหนด<br>ในใบแจ้งหนี้</th>
			<th width=\"300\">สถานที่ส่งจดหมาย</th>
			<th><a onclick=\"javascript:selectAll('checkdebt','frm');\" style=\"cursor:pointer;\"><font color=\"#FFF\"><u>เลือกทั้งหมด</u></font></a></td>
			<th>เหตุผลที่ยกเลิกการส่ง</th>
		</tr>";
	if($tab_id=='0')
	{
		$qr=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\",\"sendduedate\"
			from \"Vthcap_send_invoice\" 
			where \"print_user\" is null and \"sendduedate\" <= current_date and \"status_sent\"='TRUE'
			order by \"sendduedate\"");
	}
	else
	{
		$qr=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\",\"sendduedate\"
			from \"Vthcap_send_invoice\" 
			where \"print_user\" is null and \"sendduedate\" <= current_date
			and \"conType\"='$tab_id' and \"status_sent\"='TRUE'
			order by \"sendduedate\"");	
	}
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		$i=0;
		while($res = pg_fetch_array($qr))
		{
			$debtInvID=trim($res["debtInvID"]); // รหัสใบแจ้งหนี้
			$contractID=trim($res["contractID"]); // เลขที่สัญญา
			$thcap_fullname=trim($res["thcap_fullname"]); // ชื่อลูกค้า
			$debtDueDate=trim($res["debtDueDate"]); // กำหนดชำระเงิน
			$thcap_address=trim($res["addrSend"]); // ที่อยู่
			$sendduedate=trim($res["sendduedate"]); // วันที่กำหนดส่งใบแจ้งหนี้
			
			if($i%2==0){
				echo "<tr bgcolor=#EEE0E5 align=center>";
			}else{
				echo "<tr bgcolor=#FFF0F5 align=center>";
			}
			echo "
			<td valign=top><span onclick=\"javascript:popU('../thcap/Channel_detail_i.php?debtInvID=$debtInvID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\" style=\"cursor:pointer;\"><u>$debtInvID</u></span></td>
			<td valign=top><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$contractID</u></span></td>
			<td align=left valign=top>$thcap_fullname</td>
			<td valign=top>$sendduedate</td>
			<td valign=top>$debtDueDate</td>
			<td align=left valign=top>$thcap_address</td>
			<td valign=top><input type=\"checkbox\" name=\"checkdebt[]\" id=\"debt$i\" value=\"$debtInvID\" onclick=\"chkclick('$i')\"></td>
			<td>
			<select name=\"addtxtdebt[]\" id=\"addtxtdebt$i\" disabled>
				<option value=0>--เลือก--</option>
				<option value=1>ใบแจ้งหนี้ไม่ทันสมัย</option>
				<option value=2>มีการจ่ายเช็คล่วงหน้าของใบแจ้งหนี้นี้</option>
			</select>
			</td>
			</tr>
			";
			$i++;
		}
	}
	if($row==0){
		echo "<tr><td colspan=8 height=50 bgcolor=#FFF0F5 align=center><b>----------ไม่พบข้อมูล----------</b></td></tr>";
	}else{
		echo "<tr><td colspan=8 align=right><input type=\"hidden\" name=\"chkchoise\" id=\"chkchoise\" value=\"$i\" ><input type=\"hidden\" name=\"method\" value=\"saveprint\"><input type=\"button\" value=\"บันทึกหรือพิมพ์รายการ\" onclick=\"app(this.form);\"></td></tr>";
	}
	echo "</table>";
}else if($s==2){ //ใบแจ้งหนี้ที่พิมพ์แล้วรอส่ง
	if($tab_id!='01'){
		//แยกตัวเลข ออกจากข้อความ  
		list($tab_id,$number)=explode("-",$tab_id);
	}
	echo "
	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\" bgcolor=\"#838B83\">
	<tr bgcolor=\"#C1CDC1\">
		<th>รหัสใบแจ้งหนี้</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้กู้หลัก</th>
		<th>วันที่กำหนดส่งใบแจ้งหนี้</th>
		<th>วันที่ครบกำหนด<br>ในใบแจ้งหนี้</th>
		<th width=\"300\">สถานที่ส่งจดหมาย</th>
		<th>วันที่ออกใบแจ้งหนี้</th>
		<th>ผู้พิมพ์รายการ</th>
		<th>วันเวลาที่พิมพ์์รายการ</th>
		<th><a onclick=\"javascript:selectAll('checkdebt2','frm2');\" style=\"cursor:pointer;\"><font color=\"#FFF\"><u>เลือกทั้งหมด</u></font></a></td>				
	</tr>";
	if($tab_id=='01')
	{
		//แสดงข้อมูลทั้งหมด
		$qr=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\" ,\"sendduedate\",\"invoiceDate\",
			\"printname\" as fullname,\"print_date\"
			from \"Vthcap_send_invoice\" 
			where \"print_user\" is not null and \"send_user\" is null
			order by \"sendduedate\"");
	}
	else
	{
		//กรณีเลือกประเภทที่จะให้แสดง
		$qr=pg_query("select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\" ,\"sendduedate\",\"invoiceDate\",
			\"printname\" as fullname,\"print_date\"
			from \"Vthcap_send_invoice\"
			where \"print_user\" is not null and \"send_user\" is null
			and \"conType\"='$tab_id'
			order by \"debtInvID\"");
	}
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		$i=0;
		while($res = pg_fetch_array($qr))
		{
			$debtInvID2=trim($res["debtInvID"]); // รหัสใบแจ้งหนี้
			$contractID2=trim($res["contractID"]); // เลขที่สัญญา
			$thcap_fullname2=trim($res["thcap_fullname"]); // ชื่อลูกค้า
			$debtDueDate2=trim($res["debtDueDate"]); // กำหนดชำระเงิน
			$thcap_address2=trim($res["addrSend"]); // ที่อยู่
			$sendduedate2=trim($res["sendduedate"]); // วันที่กำหนดส่งใบแจ้งหนี้
			$invoiceDate2=trim($res["invoiceDate"]); // วันที่กำหนดส่งใบแจ้งหนี้
			$fullname=trim($res["fullname"]); // ผู้พิมพ์รายการ
			$print_date=trim($res["print_date"]); // วันเวลาที่พิมพ์รายการ
			
			if($i%2==0){
				echo "<tr bgcolor=#E0EEE0 align=center>";
			}else{
				echo "<tr bgcolor=#F5FFFA align=center>";
			}
			echo "
			<td><span onclick=\"javascript:popU('../thcap/Channel_detail_i.php?debtInvID=$debtInvID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\" style=\"cursor:pointer;\"><u>$debtInvID2</u></span></td>
			<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$contractID2</u></span></td>
			<td align=left>$thcap_fullname2</td>
			<td>$sendduedate2</td>
			<td>$debtDueDate2</td>
			<td align=left>$thcap_address2</td>
			<td>$invoiceDate2</td>
			<td>$fullname</td>
			<td>$print_date</td>
			<td valign=top><input type=\"checkbox\" name=\"checkdebt2[]\" id=\"debt2$i\" value=\"$debtInvID2\"></td>				
			</tr>
			";
			$i++;
		}
	}
	if($row==0){
		echo "<tr><td colspan=10 height=50 bgcolor=#E0EEE0 align=center><b>----------ไม่พบข้อมูล----------</b></td></tr>";
	}else{
		echo "<tr><td colspan=10 align=right><input type=\"hidden\" name=\"chkchoise2\" id=\"chkchoise2\" value=\"$i\" ><input type=\"hidden\" name=\"method\" value=\"reprint\"><input type=\"button\" value=\"พิมพ์รายการซ้ำ\" onclick=\"app2(this.form);\"></td></tr>";
	}
	echo "</table>";
}

?>