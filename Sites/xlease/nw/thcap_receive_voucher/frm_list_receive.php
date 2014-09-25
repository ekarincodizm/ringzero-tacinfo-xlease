<?php 
include("../../config/config.php");
	$txt_voucher = pg_escape_string($_REQUEST['txt_voucher']);// เลขที่ใบสำคัญ
	$s_date = pg_escape_string($_REQUEST['s_date']);  //วันที่  ที่ต้องการสิืบค้นข้อมูล 
	$s_month = pg_escape_string($_REQUEST['s_month']);//เดือน ที่ต้องการสืบค้นข้อมูล
	$s_year = pg_escape_string($_REQUEST['s_year']);// ปี ที่้ต้องการสืบค้นข้อมูล   จากค้นหาตามปี     
	$s_sel_year = pg_escape_string($_REQUEST['s_sel_year']);//ปีที่ค้นหา จาก ค้นหาตามเดือน
 	$s_datefrom = pg_escape_string($_REQUEST['s_datefrom']);// วันที่เริิ่มต้น ของต้องการสืบค้นข้อมูล
	$s_dateto = pg_escape_string($_REQUEST['s_dateto']);// วันที่สุดท้าย ของการสืบค้นข้อมูล
	$s_valuee = pg_escape_string($_REQUEST['s_value']); // ค่าตัวแปรจากการเลือก  วิธีการสืบค้นข้อมูลในเงื่อนไขหลัก
	$s_detail = pg_escape_string($_REQUEST['s_detail']);// ส่วนของ ตามรายละเอียด ที่ใช้สืบค้นข้อมูล
	$s_cancel = pg_escape_string($_REQUEST['s_cancel']);  // สถานะการเลือก  "แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก "
	$s_purpose_idx = pg_escape_string($_REQUEST['s_purpose_idx']); // เลขรหัสจุดประสงค์ ของใบสำคัญ 
	$s_chk_detail = pg_escape_string($_REQUEST['s_chk_detail']);    // สถานะการเลือก  "ตามรายละเอียด "
	$s_chk_purpose = pg_escape_string($_REQUEST['s_chk_purpose']); // สถานะการเลือก "จุดประสงค์"
	
	// Part Of SQL Comand จากสถานะ Click Check box ที่  "แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก"
	
	if($s_cancel == "on"){	//กรณี คลิก
		$condition_c = "";
	}else{					//กรณี ไม่คลิก 
		if($s_valuee=="4"){
			$condition_c = " where a.\"voucherStatus\" = '1' and a.\"voucherAdjustCancelFor\" is null ";
		}else{
			$condition_c = " and a.\"voucherStatus\" = '1' and a.\"voucherAdjustCancelFor\" is null  ";
		}
	}
	
	// สร้าง Part Of SQL Comand ส่วนการระบุ col และ ตาราง
	$qry ="select a.* from v_thcap_temp_voucher_details_receive a ";
	
	// Part Of SQL Comand จาก เงื่อนไขหลัก
	if($s_valuee=="0"){ //เลือกค้นหา Receive ID:
		$qry_c1 = " where a.\"voucherID\"='$txt_voucher' $condition_c ";
	}else if($s_valuee=="1"){ //เลือกค้นหาตาม วันที่ :
		$qry_c1 .= " where a.\"voucherDate\"='$s_date' $condition_c ";
	}else if($s_valuee=="2"){ //เลือกค้นหาตาม เดือน:
		$qry_c1 = " where EXTRACT(MONTH FROM a.\"voucherDate\")='$s_month' and EXTRACT(YEAR FROM a.\"voucherDate\")='$s_year' $condition_c ";
	}else if($s_valuee=="3"){ //เลือกค้นหาตาม ช่วงวันที่:
		$qry_c1 = " where a.\"voucherDate\" between '$s_datefrom' and '$s_dateto' $condition_c ";
	}else if($s_valuee=="4"){ //เลือกค้นหาทั้งหมด
		$qry_c1 = " $condition_c ";
	}else if($s_valuee=="5"){ //เลือกค้นหาตาม ปี
		$qry_c1 = " where EXTRACT(YEAR FROM a.\"voucherDate\")='$s_sel_year' $condition_c ";
	}	 
	
	
	// Part Of SQL Comand จาก เงื่อนไขรอง
	if($s_chk_detail == "on"){ // กรณีที่เลือกสืบค้น ตามรายละเอียด 
	    if(strlen($qry_c1) ==0){
	    $qry_c2 = " WHERE \"voucherRemark\" like '%$s_detail%' ";  
		}else{
		$qry_c2 = " AND \"voucherRemark\" like '%$s_detail%' ";
		}
	}
	if($s_chk_purpose == "on"){  // กรณีที่เลือกสืบค้น ตามจุดประสงค์
	    if(strlen($qry_c2)<>0){
		  $qry_c2 .= " and (\"voucherPurpose\" = ".$s_purpose_idx." )";
		}else{
		  if(strlen($qry_c1) == 0){ 
		    $qry_c2 .= "  WHERE (\"voucherPurpose\" = ".$s_purpose_idx.") ";
		  }else{
		    $qry_c2 .= " and (\"voucherPurpose\" = ".$s_purpose_idx." )";
		  }
		}  
    }
	
	// Part Of SQL Comand ส่วนการเรียงข้อมูล
	$qry_end = " order by a.\"voucherDate\", a.\"voucherTime\", a.\"voucherID\" ";
	
	$qry = $qry.$qry_c1.$qry_c2.$qry_end; // Sql Comand สำหรับการสิืบค้นข้อมูล
	$method = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใบสำคัญรับ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>

<form name="frm" action="pdf_payment_voucher.php" method="post" target="_blank">
	<div style="margin-top:10px;"align="center">
		<table cellpadding="5" cellspacing="0" border="0" width="80%" bgcolor="#F0F0F0" align="center">
			<tr bgcolor="white">
				<td colspan="2" align="left"><font size="3" color="blue"><b>รายการใบสำคัญรับ</b></font></td>
				<td colspan="8" align="right">	
					<input type="button" name="cancel" id="cancel" value="ขอยกเลิก" onclick="validate(this.form,'C');"/>				
					<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF" onclick="validate(this.form,'PDF');"/>
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#BEBEBE" align="center">
				<td>ลำดับที่</td>
				<td>รหัสใบสำคัญรับ</td>
				<td>วันที่ใบสำคัญรับ</td>
				<td>เวลาใบสำคัญรับ</td>
				<td>ผลรวม</td>
				<td>เลขที่บันทึกบัญชี</td>
				<td>ผู้ำทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td><span id="selectAll" style="cursor:pointer;"><u><font color="blue">เลือกรายการ</font></u></span></td>
				<td>หมายเหตุ</td>
			</tr>
			<?php 
				$query_list = pg_query($qry);
				$num_row = pg_num_rows($query_list);
				if($num_row>0){
					$i = 0;
					while($res_v = pg_fetch_array($query_list)){
						$i++;
						$sum_debit=0;
						$voucherID = $res_v['voucherID'];
						$voucherDate = $res_v['voucherDate'];
						$doerFull = $res_v['doerFull'];
						$doerStamp = $res_v['doerStamp'];
						$abh_id = $res_v['abh_id'];
						$voucherTime = $res_v['voucherTime'];
						$voucherCancelRef = $res_v['voucherCancelRef'];
						$voucherStatus = $res_v['voucherStatus'];
						$voucherAdjustCancelFor = $res_v['voucherAdjustCancelFor'];//not null คือ ปรับปรุงยกเลิก
						//เพิ่มรายละเอียด ในบรรทัดใหม่ #6771
						$voucherRemark = $res_v['voucherRemark'];
						
						$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
						$abh_autoid = pg_fetch_result($qry_bookhead,0);
						
						$qry_concurrent = pg_query("select \"voucherID\" from thcap_temp_voucher_cancel where \"voucherID\"='$voucherID' and \"appvStatus\"='9' ");
						$num = pg_num_rows($qry_concurrent);
						
						if($num>0){
							$textStatus = "รออนุมัติยกเลิก";
							$Fcolor = "FF8000";
						}else if($voucherStatus == '0'){
							$textStatus = "ยกเลิกแล้ว";
							$Fcolor = "red";
						}else if($voucherAdjustCancelFor != ''){
							$textStatus = "ปรับปรุงยกเลิก";
							$Fcolor = "#CD853F";
						}else{
							$textStatus = "";
						}
						//หาผลรวม เดบิต
						$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
						$abh_autoid = pg_fetch_result($qry_bookhead,0);
						$qry_detail = pg_query("select sum(\"abd_amount\") as \"sum_amount\" from account.\"all_accBookDetail\" 
						where abd_autoidabh='$abh_autoid' and \"abd_bookType\" ='1' ");
						$sum_debit = pg_fetch_result($qry_detail,0);						
						$sum_debit = number_format($sum_debit,2);
						
					$bgcolor="";		
					if($i%2==0){	
						if($voucherStatus == "0"){
							echo "<tr bgcolor=\"#CCCCCC\" >";
							$bgcolor="#CCCCCC";
						}else if($voucherAdjustCancelFor != ''){
							echo "<tr bgcolor=\"#FFFFCC\" >";
							$bgcolor="#FFFFCC";
						}else{
							echo "<tr bgcolor=\"#EDF8FE\" >";
							$bgcolor="#EDF8FE";
						}
					}else{
						if($voucherStatus == "0"){
							echo "<tr bgcolor=\"#CCCCCC\" >";
							$bgcolor="#CCCCCC";
						}else if($voucherAdjustCancelFor != ''){
							echo "<tr bgcolor=\"#FFFFCC\" >";
							$bgcolor="#FFFFCC";
						}else{
							echo "<tr>";
						}
					}
							echo "<td>$i</td>";
							echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('frm_voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
							echo "<td align=\"left\">$voucherDate</td>";
							echo "<td align=\"left\">$voucherTime</td>";
							echo "<td align=\"right\">$sum_debit</td>";
							echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');\" style=\"cursor:pointer;\">$abh_id</a></u></font></td>";
							echo "<td align=\"left\">$doerFull</td>";
							echo "<td align=\"left\">$doerStamp</td>";
							echo "<td align=\"center\"><input type=\"checkbox\" name=\"select_print[]\" id=\"select_print$i\" value=\"$voucherID\"></td>";
							echo "<td align=\"center\"><font color=\"$Fcolor\">$textStatus</font></td>";
						echo "</tr>";
						
						 //if($s_valuee=="6"){
						//format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
						$fromChannelDetails_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/';
						$fromChannelDetails_popup = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4\5'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4\5'."</u></font></span>";
		
						//รายละเอียด
						$voucherRemark = preg_replace($fromChannelDetails_format,$fromChannelDetails_popup,$voucherRemark);
						echo "<tr bgcolor=$bgcolor><td colspan=\"10\"><b>รายละเอียด:</b> $voucherRemark</tr>";
						// }
					}
				}else{
					echo "<tr><td colspan=\"10\" align=\"center\">ไม่พบข้อมูลที่ค้นหา</td></tr>";
				}
			?>
			<tr cellspacing="10px" bgcolor="#BEBEBE">
				<td colspan="10" align="right">
					<input type="hidden" id="AllorClear" value="A"/>
					<input type="button" name="cancel" id="cancel" value="ขอยกเลิก" onclick="validate(this.form,'C');"/>
					<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF" onclick="validate(this.form,'PDF');"/>
				</td>
			</tr>
		</table>
	</div>
</form>

</body>
<script>
$("#selectAll").click(function(){
	var select = $("input[name=select_print[]]");
	var chkBT = $("#AllorClear").val();
	var num = 0;
	
	if(chkBT=="A"){
		for(i=0; i<select.length; i++){
			$(select[i]).attr("checked","checked");
		}
		$("#AllorClear").val('C');
	}else{
		for(i=0; i<select.length; i++){
			$(select[i]).removeAttr("checked");
		}
		$("#AllorClear").val('A');
	}
});

function validate(frm,method){
	
	var select = $("input[name=select_print[]]:checked");
	var ErrorMessage = "Error Message! \n";
	var Error = 0;
	if(select.length<1){
		ErrorMessage += "กรุณาเลือกรายการที่ต้องการ Print";
		Error++;
	}

	if(Error>0){
		alert(ErrorMessage);
		return false;
	}else{
		if(method == "PDF"){
			frm.action="pdf_receive_voucher.php";
			frm.submit();
		}else if(method == "C"){
			frm.action="frm_cancel_receive_voucher.php";
			frm.submit();
		}
	} 
}
</script>
</html>	