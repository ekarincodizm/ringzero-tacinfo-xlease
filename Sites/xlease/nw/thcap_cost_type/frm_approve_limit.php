<?php ?>
<fieldset>
	<?php if($menu=="appv"){?>
	<!--(THCAP) อนุมัติประเภทต้นทุนเริ่มแรก-->
	<legend><font color="black"><b>
			ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('../thcap_cost_type/frm_historityall.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>		
	</legend>	
	<?php } else {?>
	<!--(THCAP) จัดการประเภทต้นทุนเริ่มแรก -->
	<legend><font color="black"><b>
			ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historityall.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>		
	</legend>
	<?php }?>
<br>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>ชื่อประเภทต้นทุน</th>
		<th>ประเภทต้นทุน</th>
		<th>ผู้ทำรายการ</th>
        <th>วันที่ทำรายการ</th>
		<th>ประเภทสินเชื่อ</th>
		<th>ผู้ทำการอนุมัติ</th>
        <th>วันที่ทำการอนุมัติ</th>
		<th>หมายเหตุ</th>			
		<th>สถานะ</th>
		<th>ผลการอนุมัติ</th>
	</tr>
	<?php	
	$i=0;
	$costold="";	
	$query = pg_query("select * from \"thcap_cost_type_temp\" 
	where \"approved\"!='9' order by  \"appvstamp\" desc limit 30 ");
	$numrows = pg_num_rows($query);
	while(($result = pg_fetch_array($query)))
	{
		$autoid=$result["autoid"];
		$costname= $result["costname"];	
		$costtype= $result["costtype"];
		$doerid = $result["doerid"];
		$doerstamp= $result["doerstamp"];
		$appvid= $result["appvid"];
		$appvstamp= $result["appvstamp"];
		$typeloansuse = $result["typeloansuse"];
		$note = $result["note"];
		$resultappv= $result["approved"];
		$countedit=$result["edit_last_autoid"];
		$status_costtype=$result["status_costtype"];
		if($status_costtype=='0'){$status_costtype='ไม่ระบุ';}
		elseif($status_costtype=='1'){$status_costtype='ต้นทุนเริ่มแรก';}
		elseif($status_costtype=='2'){$status_costtype='ต้นทุนดำเนินการ';}
		$typeloan = substr($typeloansuse,1,strlen($typeloansuse)-2); 
		if($typeloan==""){$typeloan="ทุกประเภทสินเชื่อ";}		
		//ชื่อคนทำรายการ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnamedoerid=$nameuser["fullname"];
		//ชื่อผู้ทำการอนุมัติ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnameappvid=$nameuser["fullname"];
		if($countedit=='0'){ 
			$edit="เพิ่มใหม่";	 }
		else{
			$edit="แก้ไข";}
		if($resultappv=='1'){$resultappv="อนุมัติ";}
		else if($resultappv=='0'){$resultappv="ไม่อนุมัติ";}
		if($i%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		$i++;
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$costname</td>";	
		echo "<td align=\"center\">$status_costtype</td>";			
		echo "<td align=\"center\">$fullnamedoerid</td>";
		echo "<td align=\"center\">$doerstamp</td>";
		echo "<td align=\"center\">$typeloan</td>";
		echo "<td align=\"center\">$fullnameappvid</td>";
		echo "<td align=\"center\">$appvstamp</td>";
		if($menu=="appv"){
			echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('../thcap_cost_type/frm_note.php?autoid=$autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		
		}
		else{		
			echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_note.php?autoid=$autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		}
		echo "<td align=\"center\">$edit</td>";
		echo "<td align=\"center\">$resultappv</td>";
	  
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=11><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>
</fieldset>