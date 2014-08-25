<?php ?>
<fieldset>
	<legend><font color="black"><b>
			ประวัติการทำรายการ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historityall.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>		
	</legend>
<br>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>วันที่ของยอดยกมา </th>
		<th>เลขบัญชี</th>
		<th>ชื่อบัญชี</th>
		<th>ยอดยกปี 2555 </th>
		<th>ผู้ทำรายการ</th>
        <th>วันที่ทำรายการ</th>
	</tr>
	<?php	
	$i=0;
	$costold="";	
	$query = pg_query("select \"ledger_stamp\"::date,\"accBookserial\",\"ledger_balance\",\"doerid\",\"doerstamp\" from account.\"thcap_ledger_detail\" 
	where \"ledger_stamp\"::date='2012-12-31' and \"is_ledgerstatus\"='1' 
	order by  \"doerstamp\" desc limit 30 ");
	$numrows = pg_num_rows($query);
	while(($result = pg_fetch_array($query)))
	{
		//$autoid=$result["auto_id"];
		$ledgerstamp= $result["ledger_stamp"];				
		$accBookserial= $result["accBookserial"];		
		$ledgerbalance = $result["ledger_balance"];
		$doerid= $result["doerid"];
		$doerstamp= $result["doerstamp"];		
		$ledgerbalance=number_format($ledgerbalance,2);
		//ผู้ทำรายการ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnamedoerid=$nameuser["fullname"];
		//หาเลขบัญชี และ ชื่อบัญชี
		$query_accbook = pg_query("select \"accBookID\",\"accBookName\" from account.\"all_accBook\" 
		where \"accBookserial\"='$accBookserial'");
		$numrows_accbook = pg_num_rows($query_accbook);
		//ตรวจสอบก่อนว่ามี บัญชีนั้น จริง
		//$numrows_accbook=0
		if($numrows_accbook==0){
			break;
		}
		else{	
			$result_accbook = pg_fetch_array($query_accbook);
			$accBookID=$result_accbook["accBookID"]; 
			$accBookName=$result_accbook["accBookName"];
			if($i%2==0){
				echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
			}else{
				echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
			}
			$i++;
			echo "<td align=\"center\">$i</td>";
			echo "<td align=\"center\">$ledgerstamp</td>";	
			echo "<td align=\"center\">$accBookID</td>";	
			echo "<td align=\"left\">$accBookName</td>";				
			echo "<td align=\"center\">$ledgerbalance</td>";
			echo "<td align=\"center\">$fullnamedoerid</td>";
			echo "<td align=\"center\">$doerstamp</td>";
		}
	}
	if($numrows_accbook==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>มีบางรายการไมีมี ข้อมูลเลขที่บัญชี อยู่ในระบบ</b></td><tr>";
	}
	else if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=7><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>
</fieldset>