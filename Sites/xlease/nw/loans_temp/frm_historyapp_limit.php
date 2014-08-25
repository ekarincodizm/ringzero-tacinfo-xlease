<?php
	$cur_path_ins = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments');
	if($showall != 'yes'){
		$where = "and ".$where;
	}	
	$iduser = $_SESSION["av_iduser"];
	
	//ตรวจสอบ level ของ ผู้ใช้งาน
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];
?>
<script type="text/javascript" src="scripts/jquery.tableSort.js"></script>
<script type="text/javascript">
function sort_table(tbid,col){
	$('#'+tbid).sortTable({
		onCol: col,
		keepRelationships: true
	}); 
}
</script>
<style type="text/css">
.sortable {
	color: #ff7800;
	cursor:pointer;
	text-decoration:underline;
}
</style>
<center>
<fieldset style="width:95%">
	<legend>
	<?php if($namemenu=="check") {?>
		<font color="black"><b>
			ประวัติการตรวจสอบ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('../loans_temp/frm_historyapp.php?showall=<?php echo $showall; ?>&credit=<?php echo $credit; ?>&namemenu=<?php echo $namemenu; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>
		<?php }else {?>
		<font color="black"><b>
			ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historyapp.php?showall=<?php echo $showall; ?>&credit=<?php echo $credit; ?>&namemenu=<?php echo $namemenu; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>
		<?php }?>
	</legend>
<br>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>ประเภทการผูกสัญญา</th>
		<th>เลขที่สัญญา</th>
        <th>วันที่ทำสัญญา</th>
		<th class="sortable" onclick="sort_table('tb_approved',5);">ประเภทสินเชื่อ</th>
		<th>วงเงินที่ปล่อย</th>
		<th>จำนวนเงินกู้</th>
		<th>ยอดจัด</th>
		<?php if($namemenu=="check") {}  else {?>
				<th>ผู้ทำรายการ</th>
		<?php } ?>
		<?php if($namemenu=="check") {}  else {?>
				<th class="sortable" onclick="sort_table('tb_approved',10);">วันเวลาที่ทำรายการ</th>
		<?php } ?>
        <th>ผู้ทำรายการตรวจสอบ</th>
        <th>วันเวลาที่ตรวจสอบ</th>
		<?php if($namemenu=="check") {?>
				<th>หมายเหตุ</th>
		<?php } else {?>		
				<th>รายละเอียด</th>
		<?php } ?>
		<?php if($namemenu=="check") {?>
				<th>ผลการตรวจสอบ</th>
		<?php } else {?>		
				<th>ผลการอนุมัติ</th>
		<?php } ?>
	</tr>
	<?php
	if($namemenu=="check") {
		$query = pg_query("select b.*,a.\"Approved\" as \"ApprovedCheck\" ,a.\"appvID\" as \"appvIDCheck\",a.\"appvStamp\" as \"appvStampCheck\" ,a.\"autoID\" as \"autoIDCheck\" 
				from \"thcap_contract_check_temp\" a, \"thcap_contract_temp\" b where a.\"ID\" = b.\"autoID\" order by a.\"autoID\" DESC limit 30");
	}
	else
	{
		$query = pg_query("select * from public.\"thcap_contract_temp\" where \"Approved\" is not null and \"editNumber\" = '0' $where order by \"appvStamp\" DESC limit 30");
	}
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

		if($namemenu=="check") {
			$appvUser = $result["appvIDCheck"]; //ไอดีผู้อนุมัติ
			$appvStamp = $result["appvStampCheck"]; // วันเวลาที่ทำรายการอนุมัติ	
		}
		else{
		$appvStamp = $result["appvStamp"]; // วันเวลาที่ทำรายการอนุมัติ		
		$appvUser = $result["appvUser"]; //ไอดีผู้อนุมัติ
		}
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
		
		if($namemenu=="check")
		{
			$ApprovedCheck = $result["ApprovedCheck"];
			if($ApprovedCheck == '0')
			{
				echo "<tr bgcolor=\"#FFBBBB\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFBBBB';\">";
			}
			else
			{
				
				if($i%2==0){
					echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
				}else{
					echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
				}
			}
		}
		else
		{
			if($Approved == 'f')
			{
				echo "<tr bgcolor=\"#FFBBBB\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFBBBB';\">";
			}
			else
			{
				
				if($i%2==0){
					echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
				}else{
					echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
				}
			}
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$contractTypeText</td>";
		echo "<td align=\"center\">";
		if($Approved=='t')
		{
			echo "<a onclick=\"javascript:popU('$cur_path_ins/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a>";	
		}
			else
		{
			echo $contractID;
		}
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
				if($Approved == 't'){
					echo "<td align=\"center\"><a onclick=\"javascript:popU('../loans_temp/frm_appv_loan.php?contractAutoID=$contractAutoID&lonly=true&AppvStatus=1&StampAppv=$appvStamp&namemenu=$namemenu','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
				}else if($Approved == 'f'){
					echo "<td align=\"center\"><a onclick=\"javascript:popU('../loans_temp/frm_appv_loan.php?contractAutoID=$contractAutoID&lonly=true&AppvStatus=0&StampAppv=$appvStamp&namemenu=$namemenu','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
			    }
		    }
		}
		if($namemenu=="check") {
			$ApprovedCheck = $result["ApprovedCheck"];
			if($ApprovedCheck == '1'){
				$appstate = 'ถูกต้อง';
			}else if($ApprovedCheck == '0'){
				$appstate = 'ไม่ถูกต้อง';
			}
		}
		else{
			if($Approved == 't'){
				$appstate = 'อนุมัติ';
			}else if($Approved == 'f'){
				$appstate = 'ไม่อนุมัติ';
			}
		}
		echo "<td align=\"center\">$appstate</td>";
		echo "</tr>";
		}
	
	if($numrows==0){
		echo "<tr bgcolor=\"#CDC5BF\" height=50><td colspan=\"14\" align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#CDC5BF\" height=30><td colspan=\"14\"><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</fieldset>
</center>