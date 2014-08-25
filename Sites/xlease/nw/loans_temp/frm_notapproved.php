<?php
	$cur_path_ins = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments');	
	$iduser = $_SESSION["av_iduser"];	
	//ตรวจสอบ level ของ ผู้ใช้งาน
	$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
	$leveluser = pg_fetch_array($query_leveluser);
	$emplevel=$leveluser["emplevel"];
?>
</style>

<center>
<fieldset style="width:95%">
	<legend>
		<font color="black"><b>รายการไม่อนุมัติที่ยังไม่มีผลการอนุมัติ</b></font>
	</legend>
<table id="tb_noapproved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>ประเภทการผูกสัญญา</th>
		<th>เลขที่สัญญา</th>
        <th>วันที่ทำสัญญา</th>
		<th class="sortable" onclick="sort_table('tb_noapproved',5);">ประเภทสินเชื่อ</th>
		<th>วงเงินที่ปล่อย</th>
		<th>จำนวนเงินกู้</th>
		<th>ยอดจัด</th>
		<th>ผู้ทำรายการ</th>
		<th class="sortable" onclick="sort_table('tb_noapproved',10);">วันเวลาที่ทำรายการ</th>		
        <th>ผู้ทำรายการตรวจสอบ</th>
        <th>วันเวลาที่ตรวจสอบ</th>
		<th>รายละเอียด</th>
		<th>ยกเลิกรายการ</th>
	</tr>
	<?php
	$query = pg_query("select * from \"thcap_contract_temp\" where \"autoID\" in (
			select max(\"autoID\") as \"autoID\" from \"thcap_contract_temp\" where \"contractID\" in (select \"contractID\"  from \"thcap_contract_temp\"  where \"Approved\"= false 
			and \"contractID\" not in (select \"contractID\" from \"thcap_contract_temp\" where \"Approved\" is null and \"editNumber\" = '0' )) group by \"contractID\" 
			) and \"Approved\" <> true and \"isForbidtoAlert\" =0 and \"contractID\" not in(select \"contractID\" from \"thcap_contract\")  order by \"appvStamp\" DESC ");
	
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$autoIDCheck= $result["autoIDCheck"];
		$contractAutoID = $result["autoID"];
		$contractID = $result["contractID"]; // เลขที่สัญญา
		$conType = $result["conType"]; // รหัสประเภทสินเชื่อ
		$conLoanAmt = $result["conLoanAmt"]; // จำนวนเงินกู้
		$conCredit = $result["conCredit"]; // วงเงินสินเชื่อ
		$conDate = $result["conDate"];	// วันที่ทำสัญญา
		if($conDate=="")
		{
			$conDate = "--";
		}
		$doerUser = $result["doerUser"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		$conRepeatDueDay = $result["conRepeatDueDay"]; // Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
		$Approved = $result["Approved"];

	
		$appvStamp = $result["appvStamp"]; // วันเวลาที่ทำรายการอนุมัติ		
		$appvUser = $result["appvUser"]; //ไอดีผู้อนุมัติ
		
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
		$approve_name = "";
		if($appvUser!="")
		{	//ตรวจสอบ level ของผู้ใช้งานว่า สามารถมองเห็นชื่อผู้ตรวจสอบหรื่อไม่
			if($emplevel<=1 or $appvUser==$iduser){
			$qry_appv_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$appvUser'");
			$rs_appv_name = pg_fetch_array($qry_appv_name);
			$approve_name = $rs_appv_name["fullname"];
			} else {
			$approve_name = "T".($appvUser+2556); 
			}
		}
		else
		{
			$approve_name = "--";
		}		
		
		if($i%2==0){
			echo "<tr bgcolor=\"#EED5D2\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED5D2';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$contractTypeText</td>";
		echo "<td align=\"center\">";	
		echo $contractID;			
		echo "</td>";
		echo "<td align=\"center\">$conDate</td>";
		echo "<td align=\"center\">$conType</td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconCredit</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$txtconLoanAmt</b></font></td>";
		echo "<td align=\"right\"><font color=\"red\"><b>$conFinanceAmount</b></font></td>";
		//ให้แสดง ผู้ทำรายการ ,วันเวลาที่ทำรายการ  กรณีที่ไม่คลิกจาก ตรวจสอบผูกสัญญา	
		if($namemenu!="check") {
		echo "<td align=\"center\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		}
		echo "<td align=\"center\">$approve_name</td>";
		echo "<td align=\"center\">$appvStamp</td>";
		
		if($namemenu=="check") {
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_contract_check/frm_note_contract_check.php?autoIDCheck=$autoIDCheck','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=350')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>หมายเหตุ</u></font></a></td>";
		}
		else{
			if($contractType == 1)
			{   
				echo "<td align=\"center\"><a onclick=\"javascript:popU('../loans_temp/frm_appv_financial_amount.php?contractAutoID=$contractAutoID&lonly=true&namemenu=$namemenu','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
			}
			else
			{
				echo "<td align=\"center\"><a onclick=\"javascript:popU('../loans_temp/frm_appv_loan.php?contractAutoID=$contractAutoID&lonly=true&AppvStatus=0&StampAppv=$appvStamp&namemenu=$namemenu','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
			   
		    }
			//ปุ่มทำการยกเลิกรายการ
			$nameform="form".$i;//ชื่อ form เพื่อไม่ให้ ชื่อ ซ้ำกัน
			echo "<form name=\"$nameform\" method=\"post\" action=\"process_cancelDelete.php\">";
			echo "<input type=\"hidden\" name=\"contempID\" id=\"contempID\" value=\"$contractAutoID\">";
			echo "<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"if(confirm('ยืนยันการยกเลิกรายการ') == true)
			{ document.forms['$nameform'].submit();return false;}\" src=\"../thcap/images/del.png\" width=\"20px;\" height=\"20px;\"></td>";
			echo "</form>";
		}
		echo "</tr>";
		}
	
	if($numrows==0){
		echo "<tr bgcolor=\"#CDC5BF\" height=50><td colspan=\"14\" align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#CDC5BF\" height=30><td colspan=\"14\"><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	$i=0;
	?>
</table>
</fieldset>
</center>
<br>