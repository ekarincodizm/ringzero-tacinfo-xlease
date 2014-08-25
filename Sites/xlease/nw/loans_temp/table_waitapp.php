<div style="margin-top:10px;"></div>
<center><font size="5px;">รายการรออนุมัติ</font></center>
<br>
<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>ประเภทการผูกสัญญา</th>
		<th>เลขที่สัญญา</th>
		<th>ประเภทสินเชื่อ</th>
		<th>วงเงินที่ปล่อย</th>
		<th>จำนวนเงินกู้</th>
        <th>ยอดจัด</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>รายละเอียด</th>
		<th>ผลการอนุมัติ</th>
	</tr>
	<?php
	$query = pg_query("select * from public.\"thcap_contract_temp\" where \"Approved\" is null and \"editNumber\" = '0' and $where order by \"doerStamp\" DESC limit 30");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$contractAutoID = $result["autoID"];
		$contractID = $result["contractID"]; // เลขที่สัญญา
		$conType = $result["conType"]; // รหัสประเภทสินเชื่อ
		$conLoanAmt = $result["conLoanAmt"]; // จำนวนเงินกู้
		$conCredit = $result["conCredit"]; // วงเงินสินเชื่อ
		$doerUser = $result["doerUser"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		$conRepeatDueDay = $result["conRepeatDueDay"]; // Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
		$conFinanceAmount = $result['conFinanceAmount']; //ยอดจัด
		
		if($conLoanAmt != ""){$txtconLoanAmt = number_format($conLoanAmt,2);}else{$txtconLoanAmt = "--";}
		if($conCredit != ""){$txtconCredit = number_format($conCredit,2);}else{$txtconCredit = "--";}
		if($conFinanceAmount != ""){$conFinanceAmount = number_format($conFinanceAmount,2);}else{$conFinanceAmount = "--";}
		
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
		
		if($i%2==0){
			echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
		}else{
			echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$contractTypeText</td>";
		echo "<td align=\"center\">$contractID</td>";
		echo "<td align=\"center\">$conType</td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconCredit</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconLoanAmt</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$conFinanceAmount</b></font></td>";
		echo "<td align=\"center\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";		
		if($contractType == 1)
		{
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_financial_amount.php?contractAutoID=$contractAutoID&lonly=true','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		}
		else
		{
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_loan.php?contractAutoID=$contractAutoID&lonly=true','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		}
		echo "<td align=\"center\">รออนุมัติ</td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<div style="margin-top:10px;"></div>
<?php
include("frm_historyapp_limit.php");
?>