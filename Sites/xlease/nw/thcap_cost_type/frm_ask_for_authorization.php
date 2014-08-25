<?php ?>
<fieldset style="width:99%">
	<legend><font color="black"><b>รายการการขออนุมัติประเภทต้นทุนสัญญา</font></b></font></legend>
<br>
<table  align="center" width="99%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>ชื่อประเภทต้นทุน</th>
		<th>ประเภทต้นทุน</th>
		<th>ผู้ทำรายการ</th>
        <th>วันที่ทำรายการ</th>
		<th>ประเภทสินเชื่อ</th>
		<th>หมายเหตุ</th>	
		<th>สถานะ</th>
		
	</tr>
	<?php
	$query = pg_query("select * from \"thcap_cost_type_temp\" where \"approved\"='9' order by \"doerstamp\" asc");
	$i=0;
	$numrows = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{
		$i++;
		$autoid=$result["autoid"];
		$costname= $result["costname"];		
		$doerid = $result["doerid"];
		$doerstamp= $result["doerstamp"];
		$typeloansuse = $result["typeloansuse"];
		$note = $result["note"];
		$resultappv= $result["approved"];
		$countedit=$result["edit_last_autoid"];
		$status_costtype=$result["status_costtype"];
		
		if($status_costtype=='0'){$status_costtype='ไม่ระบุ';}
		elseif($status_costtype=='1'){$status_costtype='ต้นทุนเริ่มแรก';}
		elseif($status_costtype=='2'){$status_costtype='ต้นทุนดำเนินการ';}
		if($countedit=='0'){ 
			$edit="เพิ่มใหม่";	 }
		else{
			$edit="แก้ไข";}
		
		$typeloan = substr($typeloansuse,1,strlen($typeloansuse)-2); 
		if($typeloan==""){$typeloan="ทุกประเภทสินเชื่อ";}
		//$typeloan = //str_replace(","," ",$rest);
		//ชื่อคนทำรายการ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnamedoerid=$nameuser["fullname"];
		if($i%2==0){
					echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
				}else{
					echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
					}
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$costname</td>";	
		echo "<td align=\"center\">$status_costtype</td>";	
		echo "<td align=\"center\">$fullnamedoerid</td>";
		echo "<td align=\"center\">$doerstamp</td>";
		echo "<td align=\"center\">$typeloan</td>";
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_note.php?autoid=$autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		//echo "<td align=\"center\"><a onclick=\"javascript: showall($autoid)\" style=\"cursor:pointer;\"><font //color=\"#0000FF\"><u>แก้ไข</u></font></a></td>";
		echo "<td align=\"center\">$edit</td>";
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#79BCFF\" height=25><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
	
</table>
</fieldset>