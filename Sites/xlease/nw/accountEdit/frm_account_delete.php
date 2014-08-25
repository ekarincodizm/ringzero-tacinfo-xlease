<?php
include("../../config/config.php");

$abh_autoid = pg_escape_string($_GET["abh_autoid"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ลบบัญชี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>

<body>
	<center>
		<?php
			// หาข้อมูลหัวบัญชี
			$qry_m = pg_query("select \"abh_id\", \"abh_stamp\"::date from account.\"all_accBookHead\" where  \"abh_autoid\" = '$abh_autoid'");
			
			$a_id = pg_fetch_result($qry_m,0);
			$as_date = pg_fetch_result($qry_m,1);
		?>
		
		<h1>ลบรายการ</h1>
		
		<br><br>
		
		<!--h2>ใบสำคัญจ่าย</h2-->
		<table width="80%">
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td align="left"><h3>วันที่ <?php echo $as_date; ?></h3></td>
							<td align="right"><h3>เลขที่ <?php echo $a_id; ?></h3></td>
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
								$v_acid=" [ ".$res_vacc["abd_accBookID"]." ]"." ".$v_acname;
								$vs_dt=$res_vacc["abh_detail"];
								$abd_bookType = $res_vacc["abd_bookType"]; // ประเภท 1 Dr 2 Cr
								$abd_amount = $res_vacc["abd_amount"];
							   
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
								
								echo "<tr bgcolor=\"#DDFFAA\">";
								echo "<td align=\"left\">$v_acid</td>";
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
				<td align="left"><input type="button" value="  ยืนยันลบรายการ  " onclick="javascript:window.location='process_account_delete.php?abh_autoid=<?php echo $abh_autoid; ?>';"></td>
				<td align="right"><input type="button" value="  ยกเลิก  " onclick="javascript:window.close();"></td>
			</tr>
		</table>
	</center>
</body>

</html>