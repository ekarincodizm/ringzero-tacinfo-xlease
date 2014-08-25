<?php
session_start();
include("../../../config/config.php");

$strSort = $_GET["sort"];
if($strSort == ""){
	$strSort = "recmenuid";
}
	$strOrder = $_GET["order"];
if($strOrder == ""){
	$strOrder = "ASC";
}
$sql = pg_query("SELECT * FROM thcap_financial_amount_add_temp where appstatus = '0' ");
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) อนุมัติเพิ่มวงเงินสัญญา</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script> 
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
    #color_hr
	{
	color:#999999;
	}  
</style>
</head>

<body style="background-color:#DDDDDD;">
<div style="margin-top:25px" align="center" ></div>
<form id="myform" name="myform" method="post">	
<table width="1000"  cellspacing="0" cellpadding="0"  align="center">	
<tr>
		<td>
<table width="300" frame="box" cellspacing="0" bgcolor="#8B8878" cellpadding="0"  align="left">	
<tr>
		<td align="center"><h2><b><font color="#FFFFFF">(THCAP) อนุมัติเพิ่มวงเงินสัญญา</font></b></h2></td>
		
</tr>
</table>
</td>
</tr>
<tr>
<td>		
<table width="1200" border="1" cellspacing="0" cellpadding="0"  align="center">	
	
<?php 
$row = pg_num_rows($sql);
if($row != 0){			?>			
		<tr>	
			<td width="100%">			
				<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr>
						<td colspan="9" align="center" bgcolor="#6C7B8B">
							<font color="white"><h2><b></h2></b></font>
						</td>
					</tr>
					<tr bgcolor="#9FB6CD" height="25px">
					
						<th width="">เลขที่วงเงิน</th>
						<th width="">ชื่อผู้กู้หลัก</th>
						<th width="">ยอดใช้วงเงินเดิม</th>
						<th width="">วงเงินเดิม</th>
						<th width="">วงเงินที่เพิ่ม</th>
						<th width="">วงเงินใหม่</th>
						<th width="">ค่าธรรมเนียมรวมภาษี</th>
						<th width="">หมายเหตุ</th>
						<th width="">ทำรายการ</th>
					</tr>	
			<?php 
				$i =0;
				
				
				
					
				while($result = pg_fetch_array($sql)){
					$contractID = $result["contractID"]; 
					$financial_amount_old = $result["financial_amount_old"]; //วงเงินเก่า
					$financial_amount_add = $result["financial_amount_add"]; //วงเงินที่ขอเพิ่ม
					$financial_amount_new = $result["financial_amount_new"]; //วงเงินใหม่
					$feeandvat = $result["feeandvat"]; //ค่าธรรมเนียมรวมภาษี
					$financial_amount_serial = $result["financial_amount_serial"]; //PK เอาไว้ใช้โยงไปหน้าดูหมายเหตุ

					$qry_fullnamecus = pg_query("SELECT  \"CusID\",thcap_fullname FROM \"vthcap_ContactCus_detail\" where  \"contractID\" = '$contractID' AND \"CusState\" = '0' ");
					list($cusid,$fullnamecus) = pg_fetch_array($qry_fullnamecus);
					
					//หาวงเงินสินเชื่อใช้ไป 		
						$qryref=pg_query("select a.\"contractID\" from \"vthcap_contract_creditRef_all\" a
						left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" where \"contractCredit\"='$contractID'");
						while($result=pg_fetch_array($qryref)){
							$contractID1 = $result["contractID"];

							//หากสัญญาที่ปิดบัญชีแล้วจะไม่นำมาคิด
							$qryconclose=pg_query("SELECT thcap_checkcontractcloseddate('$contractID1')");
							$reconclose=pg_fetch_array($qryconclose);
							$conclosestae=$reconclose["thcap_checkcontractcloseddate"];
							if($conclosestae == ""){
								$qry_getloan=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID1') ");
								list($loanbalanceamt) = pg_fetch_array($qry_getloan);
									
									$loanbalanceamtsum += $loanbalanceamt;
							}		
						}
						
						//path เริ่มที่ root สำหรับ link ไปหน้าตรวจสอบข้อมูลลูกค้า
						$cuscheckroot=redirect($_SERVER['PHP_SELF'],'nw/search_cusco');	
						
					$i++;
					if($i%2==0){
						echo "<tr bgcolor=#B9D3EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B9D3EE';\" align=center>";
					}else{
						echo "<tr bgcolor=#C6E2FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C6E2FF';\" align=center>";
					}
					echo "		<td>
									<span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
									<u>$contractID</u></font></span>
								</td>
								<td align=\"left\">".$fullnamecus."</td>
								<td align=\"right\">".number_format($loanbalanceamtsum,2)."</td>
								<td align=\"right\">".number_format($financial_amount_old,2)."</td>
								<td align=\"right\">".number_format($financial_amount_add,2)."</td>		
								<td align=\"right\">".number_format($financial_amount_new,2)."</td>
								<td align=\"right\">".number_format($feeandvat,2)."</td>							
								<td><img src=\"../images/detail.gif\" onclick=\"javascript:popU('note_popup.php?fapk=$financial_amount_serial&type=note','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=200')\" style=\"cursor: pointer\">
									</img></td>
								<td><input type=\"checkbox\" name=\"idapp[]\" id=\"idapp$i\" value=\"$financial_amount_serial\"></td>
						</tr>";
						unset($loanbalanceamtsum);
					}

?>					
					<tr bgcolor="#9FB6CD">
						<td align="left" colspan="7"  height="18px">
							 <font color="red"><b><?php echo $row; ?> รายการ </b></font> 
						</td>	
						<td align="center" height="18px">
							 <input type="button" onclick="app('notapp');" value="ไม่อนุมัติ">
						</td>							
						<td align="center" height="18px">
							 <input type="button" onclick="app('app');" value="อนุมัติ">
							 <input type="hidden" value="<?php echo $i; ?>" id="chkchoise">
						</td>		
					</tr>
<?php }else{ echo "<tr bgcolor=\"#B9D3EE\"><td align=\"center\" colspan=\"6\"><h1>ไม่มีรายการรออนุมัติ</h1><hr width=\"450\"></td></tr>";} ?>					
				</table>	
			</td>			
		</tr>			
	</table>
</td>		
</tr>
</table>	
  </div>

</form>
<div style="margin-top:50px" align="center" ></div>
<?php $sql1 = pg_query("SELECT * FROM thcap_financial_amount_add_temp where appstatus != '0' order by add_date DESC limit 30 "); ?>
<table width="1200"  cellspacing="0" cellpadding="0"  border="0" align="center">	
	<tr>
		<td>
			<table width="300" frame="box" cellspacing="0" bgcolor="#8B8878" cellpadding="0"  align="left">	
				<tr>
					<td align="center"><h2><b><font color="#FFFFFF">ประวัติการอนุมัติ 30 รายการล่าสุด</font></b></h2></td>	
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>		
			<table width="1200" border="1" cellspacing="0" cellpadding="0"  align="center">	
				
			<?php 
			$row1 = pg_num_rows($sql1);
			if($row1 != 0){			?>			
					<tr>	
						<td width="100%">			
							<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
								<tr>
									<td colspan="10" align="center" bgcolor="#6C7B8B">
										<font color="white"><h2><b></h2></b></font>
									</td>
								</tr>
								<tr bgcolor="#9FB6CD" height="25px">
								
									<th width="">เลขที่วงเงิน</th>
									<th width="">ชื่อผู้กู้หลัก</th>
									<th width="">ยอดใช้วงเงินเดิม</th>
									<th width="">วงเงินเดิม</th>
									<th width="">วงเงินที่เพิ่ม</th>
									<th width="">วงเงินใหม่</th>
									<th width="">ค่าธรรมเนียมรวมภาษี</th>
									<th width="">หมายเหตุ</th>
									<th width="">สถานะ</th>
									<th width="">เหตุผล</th>
								</tr>	
						<?php 
							$i =0;
							
							
							
								
							while($result1 = pg_fetch_array($sql1)){
								$contractID = $result1["contractID"]; 
								$financial_amount_old = $result1["financial_amount_old"]; //วงเงินเก่า
								$financial_amount_add = $result1["financial_amount_add"]; //วงเงินที่ขอเพิ่ม
								$financial_amount_new = $result1["financial_amount_new"]; //วงเงินใหม่
								$feeandvat = $result1["feeandvat"]; //ค่าธรรมเนียมรวมภาษี
								$financial_amount_serial = $result1["financial_amount_serial"]; //PK เอาไว้ใช้โยงไปหน้าดูหมายเหตุ
								$appstatus = $result1["appstatus"]; //สภานะการอนุมัติ
								if($appstatus == '1'){
									$state = 'อนุมัติ';
									$popnotapp = "-";
								}else if($appstatus == '2'){
									$state = 'ไม่อนุมัติ';
									$popnotapp = "<img src=\"../images/detail.gif\" onclick=\"javascript:popU('note_popup.php?fapk=$financial_amount_serial&type=notapp','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=200')\" style=\"cursor: pointer\"></img>";								
								}

								$qry_fullnamecus = pg_query("SELECT  \"CusID\",thcap_fullname FROM \"vthcap_ContactCus_detail\" where  \"contractID\" = '$contractID' AND \"CusState\" = '0' ");
								list($cusid,$fullnamecus) = pg_fetch_array($qry_fullnamecus);
								
								//หาวงเงินสินเชื่อใช้ไป 		
									$qryref=pg_query("select a.\"contractID\" from \"vthcap_contract_creditRef_all\" a
									left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" where \"contractCredit\"='$contractID'");
									while($result=pg_fetch_array($qryref)){
										$contractID1 = $result["contractID"];

										//หากสัญญาที่ปิดบัญชีแล้วจะไม่นำมาคิด
										$qryconclose=pg_query("SELECT thcap_checkcontractcloseddate('$contractID1')");
										$reconclose=pg_fetch_array($qryconclose);
										$conclosestae=$reconclose["thcap_checkcontractcloseddate"];
										if($conclosestae == ""){
											$qry_getloan=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID1') ");
											list($loanbalanceamt) = pg_fetch_array($qry_getloan);
												
												$loanbalanceamtsum += $loanbalanceamt;
										}		
									}
									
									//path เริ่มที่ root สำหรับ link ไปหน้าตรวจสอบข้อมูลลูกค้า
									$cuscheckroot=redirect($_SERVER['PHP_SELF'],'nw/search_cusco');	
									
								$i++;
								if($i%2==0){
									echo "<tr bgcolor=#B9D3EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B9D3EE';\" align=center>";
								}else{
									echo "<tr bgcolor=#C6E2FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C6E2FF';\" align=center>";
								}
								echo "		<td>
												<span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
												<u>$contractID</u></font></span>
											</td>
											<td align=\"left\">".$fullnamecus."</td>
											<td align=\"right\">".number_format($loanbalanceamtsum,2)."</td>
											<td align=\"right\">".number_format($financial_amount_old,2)."</td>
											<td align=\"right\">".number_format($financial_amount_add,2)."</td>		
											<td align=\"right\">".number_format($financial_amount_new,2)."</td>
											<td align=\"right\">".number_format($feeandvat,2)."</td>							
											<td><img src=\"../images/detail.gif\" onclick=\"javascript:popU('note_popup.php?fapk=$financial_amount_serial&type=note','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=200')\" style=\"cursor: pointer\">
												</img></td>
											<td>$state</td>
											<td align=\"center\">$popnotapp</td>
									</tr>";
									unset($loanbalanceamtsum);
								}

			?>					
								<tr bgcolor="#9FB6CD">
									<td align="left" colspan="10"  height="18px">
										 <font color="red"><b><?php echo $row1; ?> รายการ </b></font> 
									</td>											
								</tr>
			<?php }else{ echo "<tr bgcolor=\"#B9D3EE\"><td align=\"center\" colspan=\"6\"><h1>ไม่มีรายการรออนุมัติ</h1><hr width=\"450\"></td></tr>";} ?>					
							</table>	
						</td>			
					</tr>			
				</table>
		</td>		
	</tr>
</table>

</body>	
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function app(frm)
{

var con = $("#chkchoise").val();
var appid = "";
var numchk;
numchk = 0;

	for(var num = 1;num<=con;num++){	
		if(document.getElementById("idapp"+num).checked){
			numchk += 1;
			if(appid == ""){
				appid = document.getElementById("idapp"+num).value;
			}else{
				appid += '@'+document.getElementById("idapp"+num).value;
			}	
		}			
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(frm == 'notapp'){
			$('body').append('<div id="dialog"></div>');
			$('#dialog').load('app_popup.php?appid='+appid+'&apptype=notapp');
			$('#dialog').dialog({
				title: 'ยืนยันการอนุมัติ ',
				resizable: false,
				modal: true,  
				width: 500,
				height: 300,
				close: function(ev, ui){
					$('#dialog').remove();
				}
			});	
		}else{
			$('body').append('<div id="dialog"></div>');
			$('#dialog').load('app_popup.php?appid='+appid+'&apptype=app');
			$('#dialog').dialog({
				title: 'ยืนยันการอนุมัติ ',
				resizable: false,
				modal: true,  
				width: 500,
				height: 300,
				close: function(ev, ui){
					$('#dialog').remove();
				}
			});	

		}		
	}	
}

</script>
</html>