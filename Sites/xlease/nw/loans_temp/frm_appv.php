<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติผูกสัญญา</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<center><h2>(THCAP) อนุมัติผูกสัญญา</h2></center>

		<table width="85%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="10" align="left" height="25"><u><b>หมายเหตุ</b></u>
					<div><font color="red"> <span style="background-color:#C0FF3E;">&nbsp;&nbsp;&nbsp;</span> รายการสีเขียว คือ ข้อมูลที่มีผลการตรวจสอบล่าสุดถูกต้อง</font></div>
					<div style="padding-top:5px;"><font color="red"> <span style="background-color:#FF7256;">&nbsp;&nbsp;&nbsp;</span> รายการสีแดง คือ ข้อมูลที่มีผลการตรวจสอบล่าสุดไม่ถูกต้อง</font></div>
				</td>
			</tr>
		<table>
		<br>
<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>ประเภทการผูกสัญญา</th>
		<th>เลขที่สัญญา</th>
		<th>วันที่ทำสัญญา</th>
		<th>ประเภทสินเชื่อ</th>
		<th>วงเงินที่ปล่อย</th>
		<th>จำนวนเงินกู้</th>
		<th>ยอดจัด/ยอดลงทุน</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php
	//$where = "\"conRepeatDueDay\" is not null ";
	
	
	
	$query = pg_query("select * from public.\"thcap_contract_temp\" where \"Approved\" is null and \"editNumber\" = '0' order by \"doerStamp\" ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$contractAutoID = $result["autoID"];
		$contractID = $result["contractID"]; // เลขที่สัญญา
		$conType = $result["conType"]; // รหัสประเภทสินเชื่อ
		$conDate = $result["conDate"]; // วันที่ทำสัญญา
		$conLoanAmt = $result["conLoanAmt"]; // จำนวนเงินกู้
		$conCredit = $result["conCredit"]; // วงเงินสินเชื่อ
		$doerUser = $result["doerUser"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		$conRepeatDueDay = $result["conRepeatDueDay"]; // Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
		$conFinanceAmount = $result["conFinanceAmount"]; // ยอดจัด/ยอดลงทุน
		
		if($conLoanAmt != ""){$txtconLoanAmt = number_format($conLoanAmt,2);}else{$txtconLoanAmt = "--";}
		if($conCredit != ""){$txtconCredit = number_format($conCredit,2);}else{$txtconCredit = "--";}
		if($conFinanceAmount != ""){$txtconFinanceAmount = number_format($conFinanceAmount,2);}else{$txtconFinanceAmount = "--";}
		
		if($conRepeatDueDay == "")
		{
			$contractType = 1; // ถ้าเท่ากับ 1 แสดงว่ามาจากเมนู (THCAP) ผูกสัญญาวงเงินชั่วคราว
			$contractTypeText = "ผูกสัญญาวงเงิน";
		}
		else
		{
			$contractType = 2; // ถ้าเท่ากับ 2 แสดงว่ามาจากเมนู (THCAP) ผูกสัญญาเงินกู้ชั่วคราว
			$contractTypeText = "ผูกสัญญาเงินกู้";
		}
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerUser' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
			
			$Appquery = pg_query("select \"Approved\" from public.\"thcap_contract_check_temp\" where \"ID\"=$contractAutoID and \"appvStamp\" = (select max(\"appvStamp\") from thcap_contract_check_temp where \"ID\"=$contractAutoID)");
			$Approved = pg_fetch_result($Appquery ,0);
		
		//เงือนไขในการกำหนดแถบสีของข้อมูลที่ตรวจสอบแล้วถูกต้องและไม่ถูกต้อง และยังไม่ได้มีการตรวจสอบ		
		if($Approved!=""){
			if($Approved=="1"){
				echo "<tr bgcolor=\"#C0FF3E\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C0FF3E';\">";
			} elseif($Approved=="0"){
					echo "<tr bgcolor=\"#FF7256\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FF7256';\">";
				}
		}else {
					
				if($i%2==0){
					echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
				}else{
					echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
					}
			}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$contractTypeText</td>";
		echo "<td align=\"center\">$contractID</td>";
		echo "<td align=\"center\">$conDate</td>";
		echo "<td align=\"center\">$conType</td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconCredit</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconLoanAmt</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconFinanceAmount</b></font></td>";
		echo "<td align=\"center\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		if($contractType == 1)
		{
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_financial_amount.php?contractAutoID=$contractAutoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		}
		else
		{
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_loan.php?contractAutoID=$contractAutoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		}
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<div style="margin-top:50px;"></div>
<?php

$showall = 'yes';
include("frm_notapproved.php");
include("frm_historyapp_limit.php");

?>
</body>
</html>