<?php ?>
<center><fieldset style="width:80%">
	<legend><font color="black"><b>ประเภทต้นทุนสัญญาที่ใช้ปัจจุบัน</font></b></font></legend>
	<table align="center" width="98%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>ชื่อประเภทต้นทุน</th>
		<th>ประเภทต้นทุน</th>
		<th>ประเภทสินเชื่อ</th>
		<th>หมายเหตุ</th>
		<th>แก้ไข</th>
	</tr>
	<?php	
	$i=0;
	$costold="";
	$query = pg_query("select * from \"thcap_cost_type\"order by \"costtype\" asc");
	$numrows = pg_num_rows($query);
	while(($result = pg_fetch_array($query)))
	{
		$costname= $result["costname"];
		$costtype= $result["costtype"];
		$typeloansuse = $result["typeloansuse"];
		$note = $result["note"];
		$status_costtype = $result["status_costtype"];
		if($status_costtype=='0'){$status_costtype='ไม่ระบุ';}
		elseif($status_costtype=='1'){$status_costtype='ต้นทุนเริ่มแรก';}
		elseif($status_costtype=='2'){$status_costtype='ต้นทุนดำเนินการ';}
		$typeloan = substr($typeloansuse,1,strlen($typeloansuse)-2); 
		if($typeloan==""){$typeloan="ทุกประเภทสินเชื่อ";}
		if($i%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		$i++;
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$costname</td>";
		echo "<td align=\"center\">$status_costtype</td>";
		echo "<td align=\"center\">$typeloan</td>";
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_note.php?autoid=$costtype&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		echo "<td align=\"center\"><input type=\"button\" value=\"แก้ไข\" onclick=\"javascript:popU('frm_addtypeloan.php?autoid=$costtype','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700');\"></td>";
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=10 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=10><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>	
</table>
</fieldset>	
</center>