<?php ?>

<table  align="center" width="99%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้รับ</th>
		<th>ผู้ทำรายการ</th>
        <th>วันที่ทำรายการ</th>	
		<th>ใส่ข้อมูล</th>
	</tr>
	<?php
	$query = pg_query("select auto_id as \"id0\",auto_id as \"id\",\"contractID\",\"cusName\" as \"cusname\",\"sendDate\", \"id_user\"	
	from \"thcap_letter_send\" where auto_id in (select auto_id from \"thcap_letter_send\" where type_send='E' and \"emsnumber\"  is  null) 
	 and type_send='E' and \"emsnumber\"  is null and \"sendDate\" >= '2013-10-01' union all
	 select a.auto_id as \"id0\",\"sendID\" as \"id\",\"contractID\",\"receiveName\" as \"cusname\",\"sendDate\",b.\"id_user\"
	 from \"thcap_letter_detail\" a left join \"thcap_letter_send\" b on b.auto_id=a.\"sendID\"

	 where \"sendID\" in (select \"sendID\" from \"thcap_letter_detail\"  where type_send='E' and \"emsnumber\"  is  null ) 
	 and a.type_send='E' and a.\"emsnumber\"  is  null  and \"sendDate\" >= '2013-10-01'  order by \"sendDate\" asc");
	$i=0;
	$numrows = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{	
		$autoid0=$result["id0"];
		$autoid=$result["id"];
		$contractID=$result["contractID"];
		$cusName=$result["cusname"];
		$id_user=$result["id_user"];
		$sendDate=$result["sendDate"];
		//
		if($autoid0==$autoid){
			$type=0;
		}
		else{
			//$autoid=$autoid0;
			$type=1;
		}
		//ชื่อคนทำรายการ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$id_user' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnamedoerid=$nameuser["fullname"];
		$i++;
		if($i%2==0){
			echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
		}else{
			echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
		}
				
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$contractID</td>";				
		echo "<td align=\"left\">$cusName</td>";
		echo "<td align=\"center\">$fullnamedoerid</td>";
		echo "<td align=\"center\">$sendDate</td>";			
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_ems.php?id=$autoid&id0=$autoid0&type=$type','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ใส่ข้อมูล</u></font></a></td>";
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=6 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#79BCFF\" height=25><td colspan=6><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
	
</table>