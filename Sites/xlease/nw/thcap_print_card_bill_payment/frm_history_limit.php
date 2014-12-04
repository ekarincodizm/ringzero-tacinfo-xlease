<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr bgcolor="#FFFFFF">
		<td colspan="11" align="left" style="font-weight:bold;">ประวัติการอนุมัติ 30 รายการล่าสุด <input type="button" value="แสดงประวัติทั้งหมด" onClick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" style="cursor:pointer;"></td>
	</tr>
	<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
		<th>ชื่อ-นามสกุลลูกค้า</th>
		<th>เลขที่สัญญา</th>
		<th>ยอดผ่อนขั้นต่ำ</th>
		<th>วันที่ครบกำหนดชำระงวดแรก</th>
		<th>จ่ายทุกวันที่</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>ผู้อนุมัติ</th>
		<th>วันเวลาที่อนุมัติ</th>
		<th>ผลการอนุมัติ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php
	$qry_wait = pg_query("
							SELECT
								\"autoID\",
								\"CusFullName\",
								\"contractID\",
								\"minPayment\",
								\"firstDueDate\",
								\"payDay\",
								(select \"fullname\" from \"Vfuser\" where \"id_user\" = \"doerID\") AS \"doerName\",
								\"doerStamp\",
								\"appvStatus\",
								(select \"fullname\" from \"Vfuser\" where \"id_user\" = \"appvID\") AS \"appvName\",
								\"appvStamp\"
							FROM
								\"thcap_print_card_bill_payment\"
							WHERE
								\"appvStatus\" IN('0', '1')
							ORDER BY
								\"appvStamp\" DESC
							LIMIT 30
						");
	$i = 0;
	while($res_wait = pg_fetch_array($qry_wait))
	{
		$i++;
		$autoID = $res_wait["autoID"]; // ลำดับรายการ
		$CusFullName = $res_wait["CusFullName"]; // ชื่อลูกค้า
		$contractID = $res_wait["contractID"]; // เลขที่สัญญา
		$minPayment = $res_wait["minPayment"]; // ยอดผ่อนขั้นต่ำ
		$firstDueDate = $res_wait["firstDueDate"]; // วันที่ครบกำหนดชำระงวดแรก
		$payDay = $res_wait["payDay"]; // จ่ายทุกวันที่
		$doerName = $res_wait["doerName"]; // พนักงานที่ทำรายการ
		$doerStamp = $res_wait["doerStamp"]; // วันเวลาที่ทำรายการ
		$appvName = $res_wait["appvName"]; // พนักงานที่ทำรายการ
		$appvStamp = $res_wait["appvStamp"]; // วันเวลาที่ทำรายการ
		$appvStatus = $res_wait["appvStatus"]; // ผลการอนุมัติ
		
		// ผลการอนุมัติ
		if($appvStatus == "0")
		{
			$appvStatusText = "<font color=\"red\">ไม่อนุมัติ</font>";
		}
		elseif($appvStatus == "1")
		{
			$appvStatusText = "<font color=\"green\">อนุมัติ</font>";
		}
		
		// ตรวจสอบว่ามีเลขที่สัญญาในระบบหรือไม่
		$qry_checkContract = pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$contractID'");
		$row_checkContract = pg_num_rows($qry_checkContract);
		if($row_checkContract > 0) // ถ้ามีสัญญาอยู่จริง
		{
			$contractID_link = "<font color=\"green\" style=\"cursor:pointer;\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')\"><u>$contractID</u></font>";
		}
		else // ถ้าไม่มีเลขที่สัญญาดังกล่าวในระบบ
		{
			$contractID_link = "<font color=\"red\" style=\"cursor:pointer;\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')\"><u>$contractID</u></font>";
		}
		
		if($i%2==0){
			echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
		}else{
			echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
		}
		
		echo "<td align=\"left\">$CusFullName</td>";
		echo "<td align=\"center\">$contractID_link</td>";
		echo "<td align=\"right\">".number_format($minPayment,2)."</td>";
		echo "<td align=\"center\">$firstDueDate</td>";
		echo "<td align=\"center\">$payDay</td>";
		echo "<td align=\"left\">$doerName</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		echo "<td align=\"left\">$appvName</td>";
		echo "<td align=\"center\">$appvStamp</td>";
		echo "<td align=\"center\">$appvStatusText</td>";
		echo "<td align=\"center\"><img src=\"../thcap/images/detail.gif\" height=\"19\" width=\"19\" style=\"cursor:pointer;\" onClick=\"javascript:popU('popup_view_detail.php?id=$autoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')\"></td>";
		
		echo "</tr>";
	}
	
	if($i == 0)
	{
		echo "<tr><td colspan=\"11\" align=\"center\">--ไม่พบข้อมูล--</td></tr>";
	}
	?>
	<tr bgcolor="#D6D6D6">
		<td colspan="11" align="left" >รวม : <?php echo number_format($i,0); ?>  รายการ</td>
	</tr>
</table>