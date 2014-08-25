<?php
include("../../../config/config.php");
$bankchk = $_POST['chkbank'];

if($bankchk != ""){
	for($con = 0;$con < sizeof($bankchk) ; $con++){
		if($bankchk[$con] != ""){	
			if($btypeqry == ""){
				$btypeqry = "\"BID\" = '$bankchk[$con]' ";
			}else{
				$btypeqry = $btypeqry."OR \"BID\" = '$bankchk[$con]' ";
			}
		}

	}
	if($btypeqry != ""){
		$btypeqry = "AND (".$btypeqry.")";
	}
}
$datechk = pg_escape_string($_POST['datechk']);
if($datechk == ""){
	$dateshow = Date('Y-m-d');
}else{
	$dateshow = $datechk;	
}	
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

<table width="950"  cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td bgcolor="" align="center" height="25px">
				<h1><b>แก้ไขรายการเงินโอน</b><h1>
			</td>
		</tr>
</table>
<form name="myform" method="POST">
<table width="600" border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<td>
			<fieldset style="width:500"><legend>ค้นหา</legend>
				<table width="100%" border="0" cellspacing="0" cellpadding="0"  align="center">
					<tr>
						<td align="right" width="300"><b>แสดงเฉพาะ :</b>
										<?php $qry_bank = pg_query("SELECT \"BID\",\"BName\" FROM \"BankInt\" where \"isTranPay\" = '1' ");
												while($re_bank = pg_fetch_array($qry_bank)){
													$BNamechk = $re_bank["BName"];
													$BIDchk = $re_bank["BID"];
													
													if($bankchk != ""){
														if(in_array($BIDchk,$bankchk)){
															$checked = "checked";
														}else{
															$checked = "";
														}
													}else{ 	$checked = "checked"; }
													echo "<input type=\"checkbox\" name=\"chkbank[]\" value=\"$BIDchk\" $checked>$BNamechk";							
												}
										?>
						</td>
						<td align="left" width="190"> &nbsp&nbsp&nbsp&nbsp&nbsp <b>ของวันที่ :</b>
							<input type="text" id="datechk" name="datechk" size="10" value="<?php echo $dateshow; ?>">
						</td>
						<td align="left"> 
							<input type="button" value=" ค้นหา " onclick="srh(this.form);" >
						</td>	
					</tr>
				</table>
			</fieldset>
		</td>	
	</tr>
</table>				
</form>
<div style="margin-top:20px" ></div>
	
		<?php 
		$sql = pg_query("SELECT \"BID\",\"BAccount\", \"BName\" FROM \"BankInt\" where \"isTranPay\" = '1' $btypeqry ");
		while($result = pg_fetch_array($sql)){ 
		$BID = $result['BID']; 
		$BAccount = $result['BAccount']; 
		$BName = $result['BName'];

		$sql2 = pg_query("SELECT * FROM finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranStatus\" = '9' AND \"appvXID\" is null AND \"bankRevAccID\" = '$BID' AND Date(\"bankRevStamp\") = '$dateshow' ");
		$row2 = pg_num_rows($sql2);
		
		?>
			
				
			<table width="950" cellspacing="0" cellpadding="0"  align="center" bgcolor="#EEEEE0">	
				<tr>
					<td>				
							<table width="100%"  cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
									<tr bgcolor="#CDC8B1">
										<td width="250px;">ชื่อบัญชี: <b><?php echo $BName;?></b>	</td>
										<td width="250px;">เลขบัญชี: <b><?php echo $BAccount;?></b></td>
										<td align="right">
										<?php if($row2 > 0 ){ ?>		
											แก้ไข: <input type="button" value="แก้ไขข้อมูลชุดนี้" onclick="javascript:popU('frm_edit_edit.php?BID=<?php echo $BID; ?>&date=<?php echo $dateshow; ?>&row=<?php echo $row2; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=400');">		
										<?php } ?>		
										</td>
									</tr>			
							</table>
					</td>
				</tr>
				<tr>
					<td align="center">
		<?php if($row2 > 0){	?>
						<table width="100%" border="0" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
							
							<tr bgcolor="#CDCDC1">
								<th align="center" width="100" rowspan="2">รายการ</th>
								<th align="center" colspan="2"> เวลาที่โอน </th>
								<th align="center" width="200"rowspan="2">รหัสสาขาที่โอน</th>
								<th align="center" width="200" rowspan="2">จำนวนเงิน</th>
							</tr>	
							<tr bgcolor="#CDCDC1">
								<th align="center" width="50">ชั่วโมง</th>
								<th align="center" width="50">นาที</th>
							</tr>
						</table>
						
						<table width="100%" border="0" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">	
					<?php 				
							$i = 0;
							$sumamt = 0;
						
							while($result2 = pg_fetch_array($sql2)){
								$i++;
								if($i%2==0){
									echo "<tr bgcolor=#EEEEE0 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEE0';\" align=center>";
								}else{
									echo "<tr bgcolor=#FFFFF0 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFF0';\" align=center>";
								}
								
								list($datebank,$timebank) = explode(" ",$result2['bankRevStamp']);
								list($hour,$min,$seconds) = explode(":",$timebank);
								echo "
											<td width=\"100\">".$result2['revTranID']."</td>
											<td width=\"50\">".$hour."</td>
											<td width=\"50\">".$min."</td>
											<td width=\"200\">".$result2['bankRevBranch']."</td>
											<td align=\"right\" width=\"200\">".number_format($result2['bankRevAmt'],2)."</td>
									</tr>";
								$sumamt = $sumamt + $result2['bankRevAmt'];	
							}
								
					?>			
							<tr bgcolor="#BCD2EE">
								<td align="left" colspan="2" height="18px">
									รวม :  <font color="red"><b><?php echo $row2; ?></b></font> รายการ
								</td>
								<td align="right" colspan="3" height="18px">
									รวม :  <font color=""><b><?php echo number_format($sumamt,2); ?></b></font>
								</td>
							</tr>
						</table>
		<?php }else{
					echo " --- ไม่มีรายการ ---  ";
			 }
					?>					
					</td>		
				</tr>			
			</table>
		<?php 
		$sumallamt += $sumamt;
		$rowsum = $row2 + $rowsum; 
		unset($sumamt);
		echo "<div style=\"margin-bottom:20px\" ></div>";
		} 
		?>
		
		
		<table width="950"  cellspacing="0" cellpadding="0"  align="center">
				<tr>
					<td align="center" colspan="2" width="200" bgcolor="#FFFFFF">							
						<hr width="100%;">
					</td>
				</tr>
				<tr>
					<td align="left"  height="18px" width="200" bgcolor="#FFFFFF">
							รวมทั้งหมด :  <font color="red"><b><?php echo $rowsum; ?></b></font> รายการ
					</td>
					<td bgcolor="" align="right" height="25px">
						<h2> รวมเงินทั้งหมด :  <font color="red"><b><?php echo number_format($sumallamt,2); ?></b></font> บาท<h2>
					</td>
				</tr>
		</table>
		
</body>

</html>