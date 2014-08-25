<?php
include("../../config/config.php");

$abh_autoid = pg_escape_string($_GET["abh_autoid"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดการบันทึกบัญชี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>

<body>
	<div align="center"><h2>รายละเอียดการบันทึกบัญชี</h2></div>
	<center>
		<?php
			// หาข้อมูลหัวบัญชี
			$qry_m = pg_query("select \"abh_id\", \"abh_stamp\"::date, abh_reftype,\"abh_refid\", \"abh_correcting_entries_abh_autoid\", \"abh_is_correcting_entries\"
								from account.\"all_accBookHead\" where  \"abh_autoid\" = '$abh_autoid'");
			
			$a_id = pg_fetch_result($qry_m,0);
			$as_date = pg_fetch_result($qry_m,1);
			$abh_reftype = pg_fetch_result($qry_m,2);
			$abh_refid = pg_fetch_result($qry_m,3);
			$abh_correcting_entries_abh_autoid = pg_fetch_result($qry_m,4); // เลขที่รายการที่เป็นการปรับปรุง ความผิดพลาดของรายการนี้
			$abh_is_correcting_entries = pg_fetch_result($qry_m,5); // รายการนี้เป็นรายการปรับปรุงเพื่อยกเลิกรายการเดิมให้ถูกต้องหรือไม่
			
			if($abh_correcting_entries_abh_autoid != ""){
				$textStatus = "ยกเลิกแล้ว";
				$Fcolor = "red";
			}elseif($abh_is_correcting_entries == "1"){
				$textStatus = "รายการปรับปรุงยกเลิก";
				$Fcolor = "#CD853F";
			}	
			
			/*if($a_id!=""){
				if($abh_reftype == '1'){
					$qry_abh_id = pg_query("select \"voucherID\" from v_thcap_temp_voucher_details_payment where abh_id='$a_id' ");
				}
				else if($abh_reftype == '2'){
					$qry_abh_id = pg_query("select \"voucherID\" from v_thcap_temp_voucher_details_receive where abh_id='$a_id' ");
				}
				else if($abh_reftype == '3'){
					$qry_abh_id = pg_query("select \"voucherID\" from v_thcap_temp_voucher_details_journal where abh_id='$a_id' ");
				}
				$voucherID = pg_fetch_result($qry_abh_id,0);
			}*/
		?>
		
		<!--h2>ใบสำคัญจ่าย</h2-->
		<table width="80%">
			<tr>
				<td>
					<table width="100%" >
						<tr>
							<td colspan="2" align="right"><font color="<?php echo $Fcolor; ?>" size="3"><b><?php echo $textStatus; ?></b></font></td>
						</tr>
					
						<tr>
							<td align="left"><h3>วันที่ <?php echo $as_date; ?></h3></td>
							<td align="right"><h3>เลขที่ <?php echo $a_id; ?></h3></td>
						</tr>
						
						<tr>
							<td align="right" colspan="2"><h3>เอกสารอ้างอิง 
							<?php if($abh_reftype == '1') {?> <u><a onclick="javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php } 
							else if($abh_reftype == '2')  {?> <u><a onclick="javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }
							else if($abh_reftype == '3')  {?> <u><a onclick="javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }
							else if($abh_reftype =='0')  {?> <u><a onclick="javascript:popU('../thcap/Channel_detail.php?receiptID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }							
							else if($abh_reftype =='998')  {?> <u><a onclick="javascript:popU('../thcap/Channel_detail_v.php?receiptID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }
							else {?><?php echo $abh_refid; ?><?php }
							
							?></h3></td>
						</tr>
					
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#000000" width="100%">
						<tr bgcolor="#DDFFAA">
							<th align="center">รายการ</th>
							<th align="center">Dr</th>
							<th align="center">Cr</th>
						</tr>
						<?php
							// หารายละเอียดบัญชี
							$qry_vacc=pg_query("select * from account.\"V_all_AccountBook\" where abh_id='$a_id' order by \"abd_bookType\" ");
							$v_dr_sum = 0; // ผลรวม debit
							$v_cr_sum = 0; // ผลรวม Credit
							
							
							while($res_vacc=pg_fetch_array($qry_vacc))
							{
								$v_acname=$res_vacc["accBookName"];
								$v_acid=" [ <u>".$res_vacc["abd_accBookID"]."</u> ]"." ".$v_acname;
								$vs_dt=$res_vacc["abh_detail"];
								$abd_bookType = $res_vacc["abd_bookType"]; // ประเภท 1 Dr 2 Cr
								$abd_amount = $res_vacc["abd_amount"];
								$accBookserial = $res_vacc["accBookserial"];

								if($abd_bookType == 1)
								{
									$v_dr = number_format($abd_amount,2);
									$v_cr = "0.00";
									
									$v_dr_sum += $abd_amount;
								}
								elseif($abd_bookType == 2)
								{
									$v_dr = "0.00";
									$v_cr = number_format($abd_amount,2);
									
									$v_cr_sum += $abd_amount;
								}
								else
								{
									$v_dr = "";
									$v_cr = "";
								}
							   
							   
								$exp_dtl=str_replace("\n","#",$vs_dt);
								$sep_dtl=explode("#",$exp_dtl);
								
								$sp_dtl=str_replace("\n"," ",$vs_dt);
								//หา เดือน-ปี จาก วันเวลารายการทางบัญชีที่รายการนั้นๆเกิดขึ้น
								list($year,$month,$day)=explode("-",$as_date);
								
								echo "<tr bgcolor=\"#DDFFAA\">";
								echo "<td align=\"left\"><a href=\"javascript:popU('../thcap_accbank_type/frm_Index.php?fromaccpaper=1&accserial=$accBookserial&date1=1&month1=$month&year1=$year','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=600')\">$v_acid</a></td>";
								echo "<td align=\"right\">$v_dr</td>";
								echo "<td align=\"right\">$v_cr</td>";
								echo "</tr>";
							}
						?>
						<tr bgcolor="#DDFFAA">
							<td align="right"><b>รวม</b></td>
							<td align="right"><b><?php echo number_format($v_dr_sum,2); ?></b></td>
							<td align="right"><b><?php echo number_format($v_cr_sum,2); ?></b></td>
						</tr>
						<tr bgcolor="#DDFFAA">
							<td colspan="3" align="left"><?php echo $sp_dtl; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<br><br>
		
		<table width="80%">
			<tr>
				<td align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
			</tr>
		</table>
	</center>
</body>
<script>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</html>