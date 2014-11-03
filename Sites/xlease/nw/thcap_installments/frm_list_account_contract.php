<?php 
include("../../config/config.php");
include("../function/emplevel.php");
$user_id = $_SESSION['av_iduser'];
$idno=pg_escape_string($_GET["idno"]);//เลขที่สัญญา

if($idno ==""){
	$cancel=pg_escape_string($_POST["cancel"]);
	$idno=pg_escape_string($_POST["idno"]);
}
else{$cancel='off';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายการบันทึกบัญชีของสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>

    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script type="text/JavaScript">
$(document).ready(function(){
	if('<?php echo $cancel=='on'?>'){		
		document.getElementById('s_cancel').checked = true;
	}
});
</script>
<body>
<fieldset>
	<legend>
		<input type="button" value="แสดงผลแบบปกติ" style="height:40px;" disabled />
		<input type="button" value="แสดงผลแบบละเอียด" style="cursor:pointer; height:40px;" onClick="window.location='frm_list_account_contract_drcr.php?idno=<?php echo $idno; ?>';" />
	</legend>
	
	<div id="panel" style="padding-top: 20px;">
	<form name="my" method="post" action="frm_list_account_contract.php">
		<input type="checkbox" name="s_cancel" id="s_cancel" />แสดงรายการที่ยกเลิก
		<input type="text" id="cancel" name="cancel" hidden />
		<input type="text" id="idno" name="idno"  value="<?php echo $idno;?>" hidden />
		<input type="submit" id="search_cancel" name="search_cancel"  value="แสดง" onclick="cancel_show();" style="cursor:pointer;" />
	</form>
		<br>
		<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0" class="sort-table">
			<tr height="25" bgcolor="#79BCFF">
				<th width="7%">รายการ</th>
				<th width="10%">วันที่</th>
				<th width="10%">เลขที่</th>
				<th width="7%">ประเภทอ้างอิง</th>
				<th width="7%">เลขที่อ้างอิง</th>
				<th width="11%">ประเภทสมุดรายวันทั่วไป</th>
				<th width="10%">จำนวนเงิน</th>
                <th width="35%">คำอธิบาย</th>
				<th  width="5%">ดู</th>				
				</tr>
                <tbody>
					<?php
					if($cancel=="off"){
						$condition=" and (\"abh_correcting_entries_abh_autoid\" IS NULL AND  \"abh_is_correcting_entries\" <> '1' ) " ;
					}
					
					$query=pg_query("select \"abh_autoid\", \"abh_type\", \"abh_id\", \"abh_stamp\", \"abh_detail\", \"abh_lockStatus\", \"GJ_typeID\", \"abh_refid\", \"abh_reftype\",
									\"abh_correcting_entries_abh_autoid\", \"abh_is_correcting_entries\", \"abh_stamp\"::date as \"abh_stamp_dateOnly\"
								from account.\"all_accBookHead\" where \"abh_id\" 
								IN (
									--1.ข้อมูลจากใบเสร็จ ใช้ function thcap_receiptIDToContractID หาใบเสร็จของสัญญานั้นเพื่อหา abh_id เพื่อใช้ไป query
									SELECT a.\"abh_id\" FROM \"thcap_temp_receipt_details\" a
									WHERE a.\"receiptID\" IN 
										(SELECT distinct \"receiptID\" AS \"receiptID\" from \"thcap_temp_receipt_otherpay\"
											WHERE \"receiptID\" NOT IN ( SELECT \"receiptID\" FROM \"thcap_temp_receipt_otherpay_cancel\") AND 
											\"thcap_receiptIDToContractID\"(\"receiptID\")='$idno'
										)
										AND a.\"abh_id\" IS NOT NULL
									UNION
									--2.ข้อมูลจากใบกำกับภาษี ใช้ function thcap_taxinvoiceIDToreceiptID หาใบเสร็จของสัญญานั้นเพื่อหา abh_id เพื่อใช้ไป query
									--2.1 กรณที่ receiptRef มีค่า  จะนำไปหา ว่าเป็น เลขที่สัญญา นี้ หรือไม่
									SELECT temp_a.\"abh_id\"
											FROM ( SELECT \"abh_id\" FROM \"thcap_temp_receipt_details\" WHERE \"receiptID\" IN ( SELECT \"v_receiptRef\" FROM ( SELECT \"thcap_taxinvoiceIDToreceiptID\"(\"taxinvoiceID\") AS \"v_receiptRef\" from \"thcap_temp_taxinvoice_details\") temp_v WHERE \"v_receiptRef\" NOT IN ( SELECT \"receiptID\" FROM \"thcap_temp_receipt_otherpay_cancel\") AND 
											\"thcap_receiptIDToContractID\"(\"v_receiptRef\")='$idno' ) AND \"abh_id\" IS NOT NULL ) temp_a
									--2.2 กรณที่ abh_id มีค่า  จะนำไปหา ว่า taxinvoiceID debtID เป็น เลขที่สัญญา นี้ หรือไม่
									UNION
											(select temp_n.\"abh_id\" FROM \"thcap_temp_taxinvoice_details\" temp_n LEFT JOIN ( SELECT l.\"contractID\",k.\"taxinvoiceID\" FROM \"thcap_temp_taxinvoice_otherpay\" k left join \"thcap_temp_otherpay_debt\" l on k.\"debtID\"=l.\"debtID\" )temp_m ON temp_n.\"taxinvoiceID\"=temp_m.\"taxinvoiceID\" WHERE temp_m.\"contractID\"='$idno' AND temp_n.\"abh_id\" IS NOT NULL )
									UNION
									--3.ข้อมูลจาก voucher ใช้ table tag ในการเชื่อมสัญญากับ voucher โดย thcap_temp_voucher_detials เก็บ abh_id เพื่อใช้ไป query
										SELECT e.\"abh_id\" FROM \"thcap_temp_voucher_details\" e
										LEFT JOIN \"thcap_temp_voucher_tag\" f ON e.\"voucherID\"=f.\"voucherID\"
										WHERE e.\"abh_id\" IS NOT NULL AND \"contractID\"='$idno'
									UNION
									--4.ข้อมูลจาก ใบลดหนี้/ใบเพิ่มหนี้ ที่ table account.thcap_dncn_detials เก็บ anh_id เพื่อใช้ไป query
										SELECT n.\"abh_id\" FROM account.\"thcap_dncn\"  m 
										LEFT JOIN account.\"thcap_dncn_details\" n  ON m.\"dcNoteID\"=n.\"dcNoteID\"
										WHERE  \"abh_id\" IS NOT NULL AND  m.\"contractID\"='$idno'
									)
									 AND \"abh_status\" = '1' $condition order by \"abh_stamp\",\"abh_id\" ASC");	
									 
									 
						$numrow=pg_num_rows($query);
						$i=1;
						$color=1;
						while($res_vacc = pg_fetch_array($query))
						{
							$abh_autoid = $res_vacc["abh_autoid"];
							$abh_type = $res_vacc["abh_type"];
							$abh_id = $res_vacc["abh_id"];
							$abh_stamp = $res_vacc["abh_stamp"];
							$abh_stamp_dateOnly = $res_vacc["abh_stamp_dateOnly"];
							$abh_detail = $res_vacc["abh_detail"];
							$abh_lockStatus = $res_vacc["abh_lockStatus"];
							$GJ_typeID = $res_vacc["GJ_typeID"];
							$abh_refid = $res_vacc["abh_refid"];
							$abh_reftype = $res_vacc["abh_reftype"];
							$abh_correcting_entries_abh_autoid = $res_vacc["abh_correcting_entries_abh_autoid"];//not null-รายการปรับปรุงที่ถูกยกเลิก
							$abh_is_correcting_entries = $res_vacc["abh_is_correcting_entries"];//1- ปรับปรุง
							
							//หาผลรวม แสดงใน column "จำนวน"
							$query_get_accbook_amt=pg_query("select account.\"thcap_get_accbook_amt\"('$abh_id')");
							$accbook_amt = pg_fetch_result($query_get_accbook_amt,0);
							if($accbook_amt !=""){
								$accbook_amt=number_format($accbook_amt,2);
							}

							//การสลับสีของ row โดย ถ้าเป็นวันเดียวกันสลับกัน
							if($i==1){
								$abh_stamp_dateOnly_old=$abh_stamp_dateOnly;
								$color=1;
							}
							else{
								if($abh_stamp_dateOnly_old==$abh_stamp_dateOnly){}
								else{
									$abh_stamp_dateOnly_old=$abh_stamp_dateOnly;
									if($color==2)
									{	$color=1;}
									else
									{  	$color=2;}
									}
							}

							
							if($abh_correcting_entries_abh_autoid !=""){  //not null-รายการปรับปรุงที่ถูกยกเลิก สีชมพู
								echo "<tr bgcolor=\"#FFB6C1\"  onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFB6C1';\">";
							}
							else if($abh_is_correcting_entries =='1'){ 	//1- ปรับปรุง สีเขียว
								echo "<tr bgcolor=\"#98FB98\"  onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#98FB98';\">";
							
							}
							else{
							
								if($color%2==0)
								{
									echo "<tr bgcolor=\"#EDF8FE\"  onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EDF8FE';\">";
								}
								else
								{
									echo "<tr bgcolor=\"#D5EFFD\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#D5EFFD';\">";
								}
							}
								echo "<td align=center>$i</td>";
								echo "<td align=center>$abh_stamp_dateOnly</td>";
								echo "<td align=center>$abh_id</td>";
								echo "<td align=center>$abh_type</td>";
								echo "<td align=center> ";
								if($abh_reftype=='0'){ // Receipt (ใบเสร็จรับเงิน)
									echo "<span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='1'){ // Payment voucher
									echo "<span onclick=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='2'){ // Receive voucher
								
								echo "<span onclick=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='3'){ // Journal voucher
									echo "<span onclick=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='998'){ // Taxinvoice (ใบกำกับภาษี)
									echo "<span onclick=\"javascript:popU('../thcap/Channel_detail_v.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else {
									echo "$abh_refid</td>";
								}								
								echo "<td align=center>$GJ_typeID</td>";
								echo "<td align=right>$accbook_amt</td>";
								echo "<td>$abh_detail</td>";
								echo "<td align=center><img src=\"images/detail.gif\" width=16 height=16 style=\"cursor:pointer;\" onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\"></td>";
								
							echo "</tr>";
							$i++;
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=\"10\" align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
                  </tbody>
		</table>
		<table width="100%" align="center" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#D0D0D0">
			<tr height="50" bgcolor="#FFFFFF">
				<td align="right"><input type="button" value="  Close  " onclick="javascript:window.close();" style="cursor:pointer;" /></td>
			</tr>
		</table>
	</div>
</fieldset>
</body>
</html>
<script type="text/javascript">		
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function cancel_show(){
	if($("#s_cancel").is(':checked')){
		$("#cancel").val("on");		
	}else{
		$("#cancel").val("off");
	}	
}

</script>
		