<?php
include("../../../config/config.php");

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) แก้ไขรายการเงินโอน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

$(document).ready(function(){	
	$("#datechk").datepicker({
        showOn: 'button',
        buttonImage: '../images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
});

function srh(frm){
		frm.action="frm_edit_index.php";
		frm.submit();
		document.myform.submit.disabled='true';
		return true;
}			
</script>
</head>
<div style="margin-top:1px" ></div>
<body>

<table width="950" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td bgcolor="" align="center" height="25px">
				<h1><b>แก้ไขรายการเงินโอนที่ไม่อนุมัติ</b><h1>
			</td>
		</tr>
		<tr>
			<td>
				<span style="background-color:#FFC0CB;">&nbsp;&nbsp;&nbsp;&nbsp;</span> สีชมพูคือรายการที่ผิด<br>
			</td>
		</tr>
</table>

			
<?php 
$sumfail = 0;
$sql = pg_query("SELECT \"BID\",\"BAccount\", \"BName\" FROM \"BankInt\" where \"isTranPay\" = '1' $btypeqry ");
while($result = pg_fetch_array($sql)){ 
	$BID = $result['BID']; 
	$BAccount = $result['BAccount']; 
	$BName = $result['BName'];
	$sumamt = 0;
	$fail = 0;
	
?>
	
	<table width="950" frame="box" cellspacing="0" cellpadding="0"  align="center">	
		<tr>
			<td width="200" valign="top"  bgcolor="#6CA6CD" >
				<table width="100%"  cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
						<tr>
							<td width="200px;">ชื่อบัญชี: <font size="3px"><b><?php echo $BName;?></b></font></td>						
							<td>เลขบัญชี: <font size="3px"><b><?php echo $BAccount;?></b></font></td>
						</tr>					
				</table>	
			</td>
		</tr>
		<tr>	
			<td>
				<table width="950" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">									
						<tr bgcolor="#CDCDC1">
							<th align="center" width="100" rowspan="2">รายการ</th>
							<th align="center" colspan="2"> เวลาที่โอน </th>
							<th align="center" width="150"rowspan="2">รหัสสาขาที่โอน</th>
							<th align="center" width="150" rowspan="2">จำนวนเงิน</th>
							<th align="center" width="150" rowspan="2">เหตุผล</th>
						</tr>	
						<tr bgcolor="#CDCDC1">
							<th align="center" width="50">ชั่วโมง</th>
							<th align="center" width="50">นาที</th>
						</tr>
				</table>
			</td>
		</tr>	
		<tr>	
			<td>
			
		<?php 
			
			$sql_time = pg_query("SELECT distinct(DATE(\"bankRevStamp\")) as datefail FROM finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranStatus\" in('4') AND \"appvXID\" is not null AND \"bankRevAccID\" = '$BID' order by date(\"bankRevStamp\") ");
			$row_time = pg_num_rows($sql_time);
			if($row_time > 0){ 
					while($re_time = pg_fetch_array($sql_time)){
						$dateedit = $re_time['datefail'];
						
						$picedit = "<input type=\"button\" value=\" แก้ไขข้อมูลชุดนี้ \" onclick=\"javascript:popU('frm_edit_edit_notapp.php?date=$dateedit&BID=$BID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=400')\">";

								
				?>
						<table width="100%"  cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
							<tr>							
								<td align="left" bgcolor="#C6E2FF" >วันที่: <b><?php echo $dateedit ?></b></font></td>
								<td align="right" bgcolor="#C6E2FF">แก้ไข: <?php echo $picedit ?></font></td>
							</tr>					
						</table>				
						<table width="100%" border="0" cellspacing="1" cellpadding="0" style="margin-top:1px" align="center">	
					<?php 				
							$i = 0;								
							$sql2 = pg_query("SELECT * FROM finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranStatus\" in('0','4') AND \"appvXID\" is not null AND \"bankRevAccID\" = '$BID' AND Date(\"bankRevStamp\") = '$dateedit' ");
							$row_sql2 = pg_num_rows($sql2);
							while($result2 = pg_fetch_array($sql2)){
								$i++;
								if($result2['revTranStatus'] == '4'){
									$fail += 1;
									echo "<tr bgcolor=#FFC0CB onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFC0CB';\" align=center>";
								}else{
									echo "<tr bgcolor=#FFFFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFFF';\" align=center>";
								}
								list($datebank,$timebank) = explode(" ",$result2['bankRevStamp']);
								list($hour,$min,$seconds) = explode(":",$timebank);	
								if($result2['appvXRemask'] != "" OR $result2['appvYRemask']!= ""){ 
											$revID = $result2['revTranID'];
											$txtreason = "<img src=\"../images/detail.gif\" style=\"cursor:pointer;\" Title=\"ดูเหตุผล\" onclick=\"popU('frm_pop_txt.php?revID=$revID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=200')\">";
										
								}else{
									$txtreason = "-";
								}
								echo "
											<td width=\"100\">".$result2['revTranID']."</td>
											<td width=\"50\">".$hour."</td>
											<td width=\"50\">".$min."</td>
											<td width=\"150\">".$result2['bankRevBranch']."</td>
											<td align=\"right\" width=\"150\">".number_format($result2['bankRevAmt'],2)."</td>
											<td align=\"center\" width=\"150\">".$txtreason."</td>
									</tr>";
								$sumamt = $sumamt + $result2['bankRevAmt'];	
							}
							
						$rowshow += $row_time;
					}					
					?>	
					<tr bgcolor="#BCD2EE">
						<td align="left" colspan="5" >
							รวม :  <font color="red"><b><?php echo $row_time; ?></b></font> วัน
						</td>
						<td align="right" >
							ผิดทั้งสิ้น:  <font color="red"><b><?php echo $fail; ?></b> </font>รายการ
						</td>
					</tr>
				<?php
					}else{
						echo "<tr>
							<td colspan=\"6\" align=\"center\" bgcolor=\"#E0EEEE\" height=\"15px\" ><b> -- ไม่พบข้อมูล --</b></td>
						  </tr>";
	
					}
				?>	
				</table>	
			</td>
				
		</tr>			
	</table>
<?php 
$sumallamt = $sumallamt+$sumamt;
$rowsum = $row_time + $rowsum;
$sumfail += $fail;
unset($rowshow);
echo "<div style=\"margin-bottom:20px\" ></div>";
} ?>




	<table width="950"  cellspacing="0" cellpadding="0"  align="center">
		
		<tr>
			<td align="left"  height="18px" width="200" bgcolor="">
							รวมทั้งหมด :  <font color="red"><b><?php echo $rowsum; ?></b></font> วัน
			</td>
			<td bgcolor="" align="right" height="25px">
				<h2> ผิดทั้งหมด :  <font color="red"><b><?php echo $sumfail; ?></b></font> รายการ<h2>
			</td>
		</tr>
	</table>
</body>

</html>