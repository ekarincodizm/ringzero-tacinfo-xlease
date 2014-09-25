<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใบสำคัญ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		function popU(U,N,T)
		{
			newWindow = window.open(U, N, T);
		}
	</script>
</head>
<body>
	<center>
		<h1>ใบสำคัญ</h1>
		<h3>(ตามเงื่อนไขที่กำหนดเองใน CODE)</h3>
		<table width="90%">
			<tr>
				<td>
					<?php
					$qry_voucher = pg_query("
										SELECT
											a.\"voucherID\",
											a.\"voucherDate\",
											a.\"doerStamp\",
											b.\"abh_autoid\"
										FROM
											\"thcap_temp_voucher_details\" a
										LEFT JOIN
											account.\"all_accBookHead\" b ON a.\"abh_id\" = b.\"abh_id\"
										WHERE
											(a.\"voucherDate\"::date BETWEEN '2014-01-01' AND '2014-06-30') AND -- วันที่ voucher มีผล
											(a.\"doerStamp\" > '2014-08-09 17:16:33' AND a.\"doerStamp\" <= CURRENT_TIMESTAMP) AND -- วันเวลาที่ทำรายการ
											a.\"voucherStatus\" = '1' -- เฉพาะใบสำคัญที่ยังไม่ถูกยกเลิกจาก thcap_temp_voucher_cancel
										ORDER BY
											a.\"voucherID\"
									");
					$i = 0;
					while($res_voucher = pg_fetch_array($qry_voucher))
					{
						$i++;
						$abh_autoid = $res_voucher["abh_autoid"];
						$voucherDate = $res_voucher["voucherDate"]; // วันที่ voucher มีผล
						$doerStamp = $res_voucher["doerStamp"]; // วันเวลาที่ทำรายการ
						
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
						else{
							$textStatus = "";
							$Fcolor = "#FFFFFF";
						}
						?>
						
						<!--h2>ใบสำคัญจ่าย</h2-->
						<table width="100%">
							<tr>
								<td>
									<table width="100%" >
										<tr>
											<td colspan="5" align="right"><font color="<?php echo $Fcolor; ?>" size="3"><b><?php echo $textStatus; ?></b></font></td>
										</tr>
									
										<tr>
											<td align="left"><b>รายการที่ : <?php echo number_format($i,0); ?></b></td>
											<td align="left"><b>ใบสำคัญ : 
												<?php if($abh_reftype == '1') {?> <u><a onclick="javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php } 
												else if($abh_reftype == '2')  {?> <u><a onclick="javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }
												else if($abh_reftype == '3')  {?> <u><a onclick="javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }
												else if($abh_reftype =='0')  {?> <u><a onclick="javascript:popU('../thcap/Channel_detail.php?receiptID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }							
												else if($abh_reftype =='998')  {?> <u><a onclick="javascript:popU('../thcap/Channel_detail_v.php?receiptID=<?php echo $abh_refid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');" style="cursor:pointer;"><?php echo $abh_refid; ?></a></u><?php }
												else {?><?php echo $abh_refid; ?><?php }?>
											</b></td>
											<td align="left"><b>วันที่ voucher มีผล : <?php echo $voucherDate; ?></b></td>
											<td align="left"><b>วันเวลาที่ทำรายการ : <?php echo $doerStamp; ?></b></td>
											<td align="right"><b>เลขที่บันทึกบัญชี : <?php echo $a_id; ?></b></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#000000" width="100%">
										<tr bgcolor="#DDFFAA">
											<th align="center">รายการ </th>
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
						<br/><br/><br/>
					<?php
					}
					?>
				</td>
			</tr>
		</table>
	</center>
</body>
</html>