<?php
require_once("../../../config/config.php");

/*
=========================================================================================
	รายละเอียด $tab_id
=========================================================================================
	0 - แสดงทั้งหมด
	XX - แสดงตามประเภทสัญญานั้นๆ
*/
$tab_id = pg_escape_string($_GET["tabid"]);
if($tab_id == '0'){
	// เอาทุกสัญญา where เลยไม่มี
	$where = "";
} else {
	// สำหรับใช้ where เอาเฉพาะสัญญาใดๆ
	$where = "AND \"thcap_get_contractType\"(\"contractID\") = '$tab_id'";
}
?>

<table align="center" frame="box" width="100%">
	<tr bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th width="150">เลขที่สัญญา</th>
		<th width="150">ชื่อผู้กู้หลัก</th>
		<th width="110">วันที่ครบกำหนดชำระ</th>
		<th width="110">ยอดค้างชำระ</th>
		<th width="">วันที่ UPDATE ข้อมูลล่าสุด</th>
		<th width="">วันที่สร้างรายการ</th>
		<th width="100">ลงบันทึการติดตาม</th>			
	</tr>

	<?php	
		$qry_selcol = pg_query("
								SELECT \"contractID\", \"colpre_serial\", \"colpre_debtamt\", \"colpre_debtupdatestamp\", \"colpre_timestamp\", \"colpre_debtdetails\",
									Date(\"colpre_duedate\") as \"dd\",current_date-date(colpre_timestamp) as \"nubdate\"
								FROM thcap_collect_pre
								WHERE colpre_status = '0' $where
								ORDER BY \"dd\" ASC
								");
		$row_Selcol = pg_num_rows($qry_selcol);
		if($row_Selcol > 0){
			
			while($re_selcol = pg_fetch_array($qry_selcol)){
				$conid = $re_selcol['contractID'];
				$col_preID = $re_selcol['colpre_serial'];
				$Debt = $re_selcol['colpre_debtamt'];//ยอดค้างชำระ
				$duedate = $re_selcol['dd'];
				$colpre_debtupdatestamp = $re_selcol['colpre_debtupdatestamp']; //วันที่ update ล่าสุด
				$nubdate = $re_selcol['nubdate']; //จำนวนวันที่ค้างติดตาม
				$datecreatetime = $re_selcol['colpre_timestamp']; //วันที่สร้างรายการ
								
				$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$conid' AND \"CusState\" = '0' ");
				$re_cusname = pg_fetch_array($qry_cusname);
				$Debt_Details = $re_selcol['colpre_debtdetails'];
				
				//จำนวนวันที่คำนวณในการติดตาม ให้ ดูตาม thcap_config แต่หากมีราย สัญญาที่ กำหนดวันที่ไม่ตรงกับ thcap_config ให้ ยึด ตาม รายสัญญา
				
				//--1.จำนวนวัน ที่กำหนดเป็นรายสัญญา ในตาราง  thcap_config
				$qry_config = pg_query("SELECT config_value FROM \"thcap_config\" where \"config_control\" = '$conid' 
				AND \"config_variable\" = 'collection_check_first'");
				list($s_config_control) = pg_fetch_array($qry_config); 
				
				//--2.หาวันที่ค้างชำระ
				$qry_over_date = pg_query("select (current_date-\"backDueDate\") AS \"over\" from \"thcap_lease_contract\" a
				left join \"thcap_backDueDatePerDay\" b on a.\"contractID\"=b.\"contractID\" 
				where a.\"contractID\"='$conid'");
				list($n_over_date) = pg_fetch_array($qry_over_date);
				$n_over_date=number_format($n_over_date,2);
				//--3.ตรวจสอบ
				$chk='0';
				if(($s_config_control > $n_over_date) and ($n_over_date !='') and ($s_config_control !='')){
					$chk='1';//ไม่แสดง
				}
				//จบ
				
				//เบี้ยปรับน้อยกว่า 100 บาท
				$Details = explode("<p>",$Debt_Details);
				$txt_1="";
				for($num = 0;$num<sizeof($Details);$num++){
					$count=0;
					$detail=trim($Details[$num]);
					$txt =str_replace('- ค่าเบี้ยปรับ','',$detail,$count);
					
					if($count>0){
						$txt_1 =str_replace('บาท','',$txt);		
						continue;
					}
					
				}
				$num_btwnt=0;
				// อยู่ระหว่างช่วง NT
				$qry_btwnt = pg_query("select \"NT_ID\" from \"thcap_history_nt\" 
				where \"contractID\" = '$conid' and \"NT_isprint\"='1' AND \"NT_enddate\" is null AND \"NT_Date\"::date <= current_date");
				$num_btwnt=pg_num_rows($qry_btwnt);
				// อยู่ระหว่างฟ้องศาล
				$dateSue=pg_query("select \"thcap_get_all_dateSue\"('$conid')<=current_date");
				list($s_dateSue) = pg_fetch_array($dateSue);
				
				//หาผลรวมหนี้ต่าง ๆ ที่ยังไม่ถึงกำหนด
				$qry_typePayLeft = pg_query("SELECT sum(a.\"typePayLeft\") AS \"sum_typePayLeft\"  FROM  thcap_v_otherpay_debt_realother_current a
				left join  account.\"thcap_typePay\"  b on a.\"typePayID\"=b.\"tpID\" 
				WHERE
					\"debtStatus\" = 1 AND
					\"typePayLeft\" > 0 AND 
					((\"debtDueDate\" IS NOT NULL) AND (\"debtDueDate\" > current_date)) AND
					\"contractID\" = '$conid'");
				$re_qry_typePayLeft = pg_fetch_array($qry_typePayLeft);
				
				if(($re_qry_typePayLeft["sum_typePayLeft"]==$Debt)OR (($txt_1!="") AND ($txt_1 <=100))OR ($num_btwnt >0) OR(($s_dateSue !="") and ($s_dateSue=='t')) OR ($chk=='1')){
				}
				else{
				$i++;
				if($i%2==0){
					echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
				}else{
					echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
				} 
				
				//เมื่อเกิน 3 วัน จากวันที่ gen จะให้แสดงแถบสีเป็นสีแดง
				if($nubdate >3){
					echo "<tr bgcolor=#FFCCCC onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFCCCC';\" align=center>";
				}
	?>
									
		<td align="center"><?php echo $i ?></td>
		<td><span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;">
			<font color="red"><u><?php echo $conid ?></u></font></span>
		</td>
		<td align="left"><?php echo $re_cusname["thcap_fullname"]; ?></td>
		<td><?php echo $duedate; ?></td>
		<td align="right"><?php echo number_format($Debt,2); ?></td>
		<td align="center"><?php echo $colpre_debtupdatestamp; ?></td>
		<td align="center"><?php echo $datecreatetime; ?></td>
	<td><img src="../images/onebit_03.png" style="cursor:pointer;"  width="20px" height="20px" onclick="popU('frm_add_call.php?col_preID=<?php echo $col_preID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=650');" /></td>									
	</tr>
	<?php }
}	?>
	
	<tr><td colspan="2">รวม: <?php echo $i ;?> รายการ</td></tr>
	<table align="center" width="100%">
		<tr>
			<td align="right">
				<font color="red" size="1px;">********************</font>
			</td>
		</tr>
	</table>

	<?php }else{  echo "<tr bgcolor=\"#BFEFFF\"><td align=\"center\" colspan=\"7\"><h2> ไม่พบรายการติดตาม  </h2></td></tr>"; }?>	
</table>