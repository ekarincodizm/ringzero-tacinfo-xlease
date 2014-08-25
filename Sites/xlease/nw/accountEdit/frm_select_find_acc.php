<?php include("../../config/config.php");
include("../function/emplevel.php");

$user_id = $_SESSION['av_iduser'];
$em_level = emplevel($user_id);

$select_date=pg_escape_string($_GET["date1"]);//เลือกวัน
$select_find=pg_escape_string($_GET["find"]);//เลือกการค้นหา
$datepicker=pg_escape_string($_GET["datepicker"]);//ตามวันที่
$month=pg_escape_string($_GET["month"]);//ตามเดือน
$year=pg_escape_string($_GET["year"]);//ตามปี
$datefrom=pg_escape_string($_GET["datefrom"]);
$dateto=pg_escape_string($_GET["dateto"]);
$id_s=pg_escape_string($_GET["id"]);//กรอกข้อมูล
$mfrom_s=pg_escape_string($_GET["mfrom"]);//จำนวนเงินจาก
$mto_s=pg_escape_string($_GET["mto"]);//จำนวนเงินถึง
$selectfind_s=pg_escape_string($_GET["selectfind"]);//ประเภทสมุดรายวันทั่วไป
$by_year=pg_escape_string($_GET["by_year"]);
$cancel=pg_escape_string($_GET["cancel"]);
$selecttype=pg_escape_string($_GET["selecttype"]);

//เงื่อนไขการค้นหา
if($select_find=='0'){	//เลือกทั้งหมด
	$find_s="select abh_autoid from account.\"all_accBookHead\"";
}
else if($select_find=='1'){ //เลขที่
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where abh_id like '%$id_s%'";
}
else if($select_find=='2'){//ประเภทสมุดรายวันทั่วไป
	if($selectfind_s=='all'){
	$find_s="select abh_autoid from account.\"all_accBookHead\"";}
	else{
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where \"GJ_typeID\" like '%$selectfind_s%'";
	}
}
else if($select_find=='3'){//คำอธิบาย
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where \"abh_detail\" like '%$id_s%'";
}
else if($select_find=='4'){//จำนวนเงิน
	$find_s="select distinct a.\"abd_autoidabh\"
	from account.\"all_accBookDetail\" a
	where (select sum(b.\"abd_amount\") from account.\"all_accBookDetail\" b where b.\"abd_autoidabh\" = a.\"abd_autoidabh\" and b.\"abd_bookType\" = '1') between '$mfrom_s' and $mto_s
	or (select sum(b.\"abd_amount\") from account.\"all_accBookDetail\" b where b.\"abd_autoidabh\" = a.\"abd_autoidabh\" and b.\"abd_bookType\" = '2')  between '$mfrom_s' and $mto_s
	union
	select distinct \"abd_autoidabh\" from account.\"all_accBookDetail\" where \"abd_amount\" between '$mfrom_s' and $mto_s
	";
}
else if($select_find=='5'){//ประเภทใบสำคัญ
	$find_s="select abh_autoid from account.\"all_accBookHead\"
			where \"abh_type\" = '$selecttype'";
}
$condition="";
if($cancel=="off"){
	$condition=" and (\"abh_correcting_entries_abh_autoid\"  IS NULL  AND \"abh_is_correcting_entries\" <> '1') ";
}

//กรอองข้อมูลวันที่
if($select_date=='0'){ //	ทั้งหมด 
	$date_s="";
}
else if($select_date=='1'){//ตามวันที่
	$date_s=" and abh_stamp::date = '$datepicker' and abh_status = '1'";
}
else if($select_date=='2'){//ตามเดือน
	$date_s=" and EXTRACT(MONTH FROM \"abh_stamp\") = '$month' AND EXTRACT(YEAR FROM \"abh_stamp\") = '$year'  and abh_status = '1'";
}
else if($select_date=='3'){//ตามช่วง
	$date_s=" and abh_stamp::date between '$datefrom' and '$dateto'";
	}
else if($select_date=='4'){//ตามปี
	$date_s=" and EXTRACT(YEAR FROM \"abh_stamp\") = '$by_year'  and abh_status = '1' ";
	
	}
?>
	<form name="mypdf" method="post" action="frm_pdf.php" target="_blank">
		<div>
			<img src="images/print.gif" height="20px"> 
			<input type="hidden" name="date1" id="date1" value="<?php echo $select_date; ?>">
			<input type="hidden" name="find" id="find" value="<?php echo $select_find; ?>">
			<input type="hidden" name="datepicker" id="datepicker" value="<?php echo $datepicker; ?>">
			<input type="hidden" name="month" id="month" value="<?php echo $month; ?>">
			
			<input type="hidden" name="year" id="year" value="<?php echo $year; ?>">
			<input type="hidden" name="datefrom" id="datefrom" value="<?php echo $datefrom; ?>">
			<input type="hidden" name="dateto" id="dateto" value="<?php echo $dateto; ?>">
			<input type="hidden" name="id" id="id" value="<?php echo $id_s; ?>">
			
			<input type="hidden" name="mfrom" id="mfrom" value="<?php echo $mfrom_s; ?>">
			<input type="hidden" name="mto" id="mto" value="<?php echo $mto_s; ?>">
			<input type="hidden" name="selectfind" id="selectfind" value="<?php echo $selectfind_s; ?>">
			<input type="hidden" name="selecttype" id="selecttype" value="<?php echo $selecttype; ?>">
			<input type="hidden" name="by_year" id="by_year" value="<?php echo $by_year; ?>">
			<input type="hidden" name="cancel" id="cancel" value="<?php echo $cancel; ?>">
		   <a href onclick="document.forms['mypdf'].submit();return false;"><b><u>พิมพ์รายงาน (PDF)</u></b></a>
		</div>
	</form>
		<div id="panel" style="padding-top: 20px;">
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr bgcolor="#FFFFFF">
						<td colspan="10" align="left" height="25"><u><b>หมายเหตุ</b></u>
						<div><font color="red"> <span style="background-color:#98FB98;">&nbsp;&nbsp;&nbsp;</span> รายการสีเขียว คือ รายการปรับปรุงของรายการที่ถูกยกเลิก</font></div>
						<div style="padding-top:5px;"><font color="red"> <span style="background-color:#FFB6C1;">&nbsp;&nbsp;&nbsp;</span> รายการสีชมพู   คือ รายการที่ถูกยกเลิก</font></div>
						</td>
					</tr>
				</table>
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0" class="sort-table">
					<tr height="25" bgcolor="#79BCFF">
						<th width="7%">รายการ</th>
						<th width="10%">วันที่</th>
						<th width="10%">เลขที่</th>
						<th width="10%">ประเภทใบสำคัญ</th>
						<th width="7%">เลขที่อ้างอิง</th>
						<th width="11%">สมุดเฉพาะ</th>
						<th width="11%">จำนวนเงิน</th>
                        <th width="35%">คำอธิบาย</th>
						<th  width="5%">ดู</th>
						<?php if($em_level<=3){ ?>
						<th  width="5%">แก้ไข</th>
						<?php } ?>
						<th  width="5%">ลบ</th>
					</tr>
                 <tbody>
					<?php
					$query=pg_query("select *, \"abh_stamp\"::date as \"abh_stamp_dateOnly\" from account.\"all_accBookHead\"
												where abh_autoid in(  $find_s  										
												) and \"abh_status\" = '1' $date_s	$condition
												order by \"abh_stamp\"::date, \"abh_refid\" ASC");
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
							//จำนวนเงิน
							$qry = "select account.\"thcap_get_accbook_amt\"('$abh_id')";
							$res_qry=pg_query($qry);							
							$amt = pg_fetch_result($res_qry,0);							
							if($amt !=''){$amt=number_format($amt,2);}
							
							
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
							/*if($color)
							{
								echo "<tr bgcolor=\"#EDF8FE\"  onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EDF8FE';\">";
							}
							else{
								echo "<tr bgcolor=\"#D5EFFD\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#D5EFFD';\">";
							}*/
							/* -- วันที่แบบไทย
							$trn_date = pg_query("select * from c_date_number('$abh_stamp')");
							$a_date = pg_fetch_result($trn_date,0);*/
							
							if($abh_correcting_entries_abh_autoid !=""){  //not null-รายการปรับปรุงที่ถูกยกเลิก สีชมพู
								echo "<tr bgcolor=\"#FFB6C1\"  onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFB6C1';\">";
							}
							else if($abh_is_correcting_entries =='1'){ //1- ปรับปรุง สีเขียว
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
								if($abh_reftype=='0'){									
									echo "<span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='1'){								
									echo "<span onclick=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='2'){
								
								echo "<span onclick=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='3'){							
									echo "<span onclick=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='101'){
									echo "<span onclick=\"javascript:popU('../thcap_dncn/popup_dncn.php?idapp=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='102'){
									echo "<span onclick=\"javascript:popU('../thcap_dncn/popup_dncn.php?idapp=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}
								else if($abh_reftype=='998'){								
									echo "<span onclick=\"javascript:popU('../thcap/Channel_detail_v.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$abh_refid</u></font></span></td>";
								}else{
									echo "$abh_refid</td>";
								}
								
								echo "<td align=center>$GJ_typeID</td>";
								echo "<td align=right>$amt</td>";
								echo "<td>$abh_detail</td>";
								echo "<td align=center><img src=\"images/detail.gif\" width=16 height=16 style=\"cursor:pointer;\" onclick=\"javascript:popU('frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\"></td>";
								if($abh_lockStatus == 1)
								{
									echo "<td align=center>LOCK</td>";
									echo "<td align=center>LOCK</td>";
								}
								else
								{
									if($em_level<=3){
									echo "<td align=center><img src=\"images/edit.png\" width=16 height=16 style=\"cursor:pointer;\" onclick=\"javascript:popU('frm_account_edit.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"></td>";
									}
									echo "<td align=center><img src=\"images/del.png\" width=16 height=16 style=\"cursor:pointer;\" onclick=\"javascript:popU('frm_account_delete.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\"></td>";
								}
							echo "</tr>";
							$i++;
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=\"11\" align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
                  </tbody>
			</table>
			<table width="100%" align="center" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#D0D0D0">
				<tr height="50" bgcolor="#FFFFFF">
					<td align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
				</tr>
			</table>
		</div>
<script type="text/javascript">		
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>
		