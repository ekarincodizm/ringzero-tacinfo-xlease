<?php 
$rootpath = redirect($_SERVER['PHP_SELF'],'');
?>
<fieldset>	
	<legend><font color="black"><b>
			ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('<?php echo $rootpath.'nw/thcap_appv_cancel_nt/frm_historityall.php';?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>		
	</legend>	
<br>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>		
		<th>เลขที่สัญญา</th>
		<th>วันที่ออก NT</th>
		<th>เลขที่ NT</th>
		<th>วันที่ขอยกเลิก NT</th>
		<th>จำนวนเงินรวม</th>
		<th>ผู้ทำรายการ </th>	
		<th>วันเวลาที่ทำรายการ</th>
		<th>ผลการทำรายการ </th>	
	</tr>
	<?php	
	$i=0;
	$costold="";	
	$query = pg_query("SELECT a.\"auto_id\" as \"auto_id\",a.\"NT_ID\" as \"NT_ID\",a.\"NT_enddate\"::date as \"NT_enddate\",a.\"appvid\" as \"appvid\",a.\"appvstamp\" as \"appvstamp\",b.\"NT_Date\"::date as \"NT_Date\",c.\"amountpay_all\" as \"amountpay_all\",b.\"contractID\" as \"contractID\" ,a.\"status\" as \"status\"
	from \"thcap_cancel_nt_temp\" a
	left join \"thcap_history_nt\" b on a.\"NT_ID\"=b.\"NT_ID\"
	left join \"thcap_pdf_nt\" c on a.\"NT_ID\"=c.\"NT_ID\"
	where a.\"status\" <>'9' order by a.\"appvstamp\" desc limit 30");
	$numrows = pg_num_rows($query);
	while(($res_main = pg_fetch_array($query)))
	{
		$auto_id = $res_main["auto_id"];
			$contractID = $res_main["contractID"];
			$NT_Date = $res_main["NT_Date"];
			$NT_ID = $res_main["NT_ID"];
			$NT_enddate = $res_main["NT_enddate"];
			$amountpay_all = $res_main["amountpay_all"];			
			$doerid = $res_main["appvid"];		
			$doerstamp = $res_main["appvstamp"];
			$status = $res_main["status"];
			if($status=='1'){$status="อนุมัติ";}
			else if($status=='0'){$status="ไม่อนุมัติ";}
			$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerid' ");
			$fullnameuser = pg_fetch_array($query_fullnameuser);
			$empfullname=$fullnameuser["fullname"];
			
			$i+=1;
			if($i%2==0)
			{
				echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
			}
			else
			{
				echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
			}
			echo "<td align=\"center\">$i</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";
			echo "<td align=\"center\">$NT_Date</td>";
			echo "<td align=\"center\">$NT_ID</td>";
			echo "<td align=\"center\">$NT_enddate</td>";
			echo "<td align=\"right\">".number_format($amountpay_all,2)."</td>";
			echo "<td align=\"center\">$empfullname</td>";	
			echo "<td align=\"center\">$doerstamp</td>";
			echo "<td align=\"center\">$status</td>";
			echo "</tr>";
	  
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>
</fieldset>