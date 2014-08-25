<fieldset>	
	<!---->
	<legend><font color="black"><b>
			ประวัติการ reprint 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history_all.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>		
	</legend>	
<br>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>รหัส Cradit Note</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้กู้หลัก</th>
		<th>วันที่มีผล</th>		
		<th>ประเภทเงินที่ขอคืน</th>
		<th>จำนวนเงิน</th>
		<th>ช่องทางการคืนเงิน</th>
		<th>วันเวลาที่ Re-print</th>
		<th>ผู้ทำรายการRe-Print</th>	
		<th>ครั้งที่ Re-Print</th>		
	</tr>
	<?php
	$rootpath = redirect($_SERVER['PHP_SELF'],''); // rootpath สำหรับเรียกไฟล์ PHP โดยเริ่มต้นที่ root	
	$i=0;
	$costold="";	
	//เลือกรายการ 30 ล่าสุดที่ ที่ทำรายการใน ตาราง  thcap_dncn_reprint ที่มีการสั่งพิมพ์
	$query = pg_query("select * from account.\"thcap_dncn_reprint\" where \"printTime\" <> '0' order by  \"printStamp\" desc limit 30 ");
	$numrows = pg_num_rows($query);
	while(($result = pg_fetch_array($query)))
	{
		$i++;
		$dcNoteID=$result["dcNoteID"];//รหัส Cradit Note
		$id_user= $result["id_user"];	//รหัสพนักงานที่ Re-Print
		$printStamp= $result["printStamp"]; //วันเวลาที่ Re-print
		$printTime = $result["printTime"];//ครั้งที่ print ของใบนี้
		//หาว่า รหัสพนักงานที่ Re-Print ชื่อ อะไร
		$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$id_user'");
		list($doer_fullname) = pg_fetch_array($qry_username);
		//ข้อมูลใน view โดยเลือกจาก view ทั้งส่วนลด และ คืนเงิน  ที่ มีสถานะ อนุมัติ 
		$qry_detail = pg_query("
			SELECT 
				\"contractID\",
				\"doerStamp\",
				\"doerID\",
				\"debtID\",
				\"dcNoteAmtALL\",
				\"dcNoteID\", 
				\"dcNoteRev\",
				\"typeChannel\" as \"byChannel\",
				\"typeChannelName\",
				\"byChannelName\",
				\"dcNoteDate\" 
			FROM 
				account.thcap_dncn_payback
			WHERE
				\"dcNoteStatus\" = '1' AND
				\"dcType\" = '2' AND
				\"dcNoteID\"='$dcNoteID'
				
			UNION
			
			SELECT 
				\"contractID\",
				\"doerStamp\",
				\"doerID\",
				\"debtID\",
				\"dcNoteAmtALL\",
				\"dcNoteID\",
				\"dcNoteRev\",
				null as \"byChannel\",
				null as \"typeChannelName\",
				null as \"byChannelName\",
				\"dcNoteDate\" 
			FROM 
				account.thcap_dncn_discount 
			WHERE
				\"dcNoteStatus\" = '1' AND 
				\"dcType\" = '2' AND
				\"dcNoteID\"='$dcNoteID'
		");
		
		//จำนวน ข้อมูล
		//$numrows_detail = pg_num_rows($qry_detail);
		//if ($numrows_detail >0 ){
			$result_detail = pg_fetch_array($qry_detail);
			$contractID = $result_detail["contractID"]; // เลขที่สัญญา
			$dcNoteAmtALL = $result_detail["dcNoteAmtALL"];
			$byChannel = $result_detail["byChannel"];
			$typeChannelName = $result_detail["typeChannelName"];
			$byChannelName = $result_detail["byChannelName"]; // ช่องทางการคืนเงิน
			$dcNoteDate = $result_detail["dcNoteDate"]; // วันที่มีผล
			$debtID = $result_detail["debtID"]; //รหัสหนี้
		//}
		//-- หาชื่อผู้กู้หลัก
		$qry_maincus = pg_query("SELECT \"dcMainCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' ");
		$maincus_fullname = pg_fetch_result($qry_maincus,0);
		//-- หาผู้กู้ร่วม
		$qry_cocus = pg_query("SELECT \"dcCoCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID'");
		$namecoopall = pg_fetch_result($qry_cocus,0);	
		//เงินค้ำประกันการชำระหนี้
		$qry_chkchannel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
		list($chkbyChannelget) = pg_fetch_array($qry_chkchannel);
		//เงินพักรอตัดรายการ
		$qry_chkchannel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
		list($chkbyChannelhold) = pg_fetch_array($qry_chkchannel);
		//ตรวจสอบว่าเป้นประเภทใด						
		if($chkbyChannelget == $byChannel){	//ถ้าเป็น เงินค้ำประกันการชำระหนี้										
			$qry_channel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','$byChannel')");
			list($byChannel) = pg_fetch_array($qry_channel);
		}else if($chkbyChannelhold == $byChannel){ //ถ้าเป็น เงินพักรอตัดรายการ
			$qry_channel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','$byChannel')");
			list($byChannel) = pg_fetch_array($qry_channel);	
		}		
		//รายละเอียดประเภทการขอคืน
		$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel' ");
		list($tpDesc) = pg_fetch_array($qry_txtchannel);
		if($i%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		
		// รายละเอียดประเภทการขอคืน ของการคืนเงินหนี้ที่ชำระไว้เกิน หรือเงินมัดจำ
		if($tpDesc == "" && ($debtID != '' || $debtID != NULL)){
			// หารหัสประเภทค่าใช้จ่าย และค่าอ้างอิง
			$qry_typePayID = pg_query("select \"typePayID\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debtID' ");
			$typePayID = pg_fetch_result($qry_typePayID,0);
																					
			// รายละเอียดประเภทค่าใช้จ่าย
			$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			while($res_type=pg_fetch_array($qry_type))
			{
				$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
			}
			$tpDesc = "$tpDesc";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$dcNoteID</td>";	
		echo "<td align=\"left\">
			<span onclick=\"javascript:popU('$rootpath/nw/thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"  >
			<font color=\"red\"><u>$contractID<u></font>
		</td>";		
		echo "<td align=\"center\">$maincus_fullname</td>";
		echo "<td align=\"center\">$dcNoteDate</td>";
		echo "<td align=\"center\">$tpDesc</td>";
		echo "<td align=\"center\">$dcNoteAmtALL</td>";
		echo "<td align=\"center\">$byChannelName</td>";		
		echo "<td align=\"center\">$printStamp</td>";
		echo "<td align=\"center\">$doer_fullname</td>";
		echo "<td align=\"center\">$printTime</td>";
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>
</fieldset>