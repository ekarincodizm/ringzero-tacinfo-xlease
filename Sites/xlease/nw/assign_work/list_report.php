<div id="list">
	<fieldset>
	<legend><b>รายการการสั่งงานตรวจสอบ-วางบิลเก็บช็ค</b></legend>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F2F5A9">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td><a href="frm_Report.php?chksh=<?php echo $chksh;echo $sortText; ?>&condate=<?php echo $condate;?>&sort=AssignNo&order=<?php echo $NewStrorder;?>"><font color="black"><u>เลขทีสั่งงาน</u></font></td>
				<td><a href="frm_Report.php?chksh=<?php echo $chksh;echo $sortText; ?>&condate=<?php echo $condate;?>&sort=AssignDate&order=<?php echo $NewStrorder;?>"><font color="black"><u>วันที่สั่งงาน</u></font></td>
				<td>เรื่อง</td>
				<td>ผู้สั่งงาน</td>
				<td><a href="frm_Report.php?chksh=<?php echo $chksh;echo $sortText; ?>&condate=<?php echo $condate;?>&sort=DoerID&order=<?php echo $NewStrorder;?>"><font color="black"><u>ผู้ทำรายการ</u></font></td>
				<td><a href="frm_Report.php?chksh=<?php echo $chksh;echo $sortText; ?>&condate=<?php echo $condate;?>&sort=DoerStamp&order=<?php echo $NewStrorder;?>"><font color="black"><u>วันที่ทำรายการ</u></font></td>
				<td>ดูรายละเอียด</td>
			<?php	if($user_emlevel<=1){
						echo "<td>ยกเลิก</td>";
					}
			?>
			<tr>
				<?php
				if($Strsort=="DoerID"){
					if($conditiondate!=""){
						$qry_detail = pg_query("select * from assign_work_detail where \"WorkStatus\" = '1' and $conditiondate order by (select fullname from \"Vfuser\" where id_user = '$Strsort3' ) $Strorder");
					}else {
						$qry_detail = pg_query("select * from assign_work_detail where \"WorkStatus\" = '1' order by (select fullname from \"Vfuser\" where id_user = '$Strsort3' ) $Strorder");
					}
				}else {
					if($conditiondate!=""){
						$qry_detail = pg_query("select * from assign_work_detail where \"WorkStatus\" = '1' and $conditiondate order by \"$Strsort\" $Strorder");
					}else {
						$qry_detail = pg_query("select * from assign_work_detail where \"WorkStatus\" = '1' order by \"$Strsort\" $Strorder");
					}
				}
					$nub=0;
					while($res = pg_fetch_array($qry_detail)){
						$nub++;
						$AssignNo = $res['AssignNo'];
						$AssignDate = $res['AssignDate'];
						$str = substr($res["Subject"],1,count($res["Subject"])-2);
						$Subject = explode(",",$str);
						$AssignName = $res['AssignName'];
						$DoerID = $res['DoerID'];
						$DoerStamp = $res['DoerStamp'];
						
						//หาชื่อเรื่อง
						for($i=0;$i<sizeof($Subject);$i++){
							if($Subject[$i]==1){
								$subname = "รับเช็ค";
							} else if($Subject[$i]==2){
								$subname = "เอกสารรับกลับ";
							}else if($Subject[$i]==3){
								$subname = "ตรวจรับ/นับสินค้าบริการ";
							}else {
								$subname = "ไม่ระบุเรื่อง";
							}
	
							if($i==0){
								$allSubname = $subname;
							}else{
								$allSubname = $allSubname." , ".$subname;
							}
						}
	
						//ชื่อผู้ทำรายการ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$DoerID' ");
						$DoerName=pg_fetch_result($qry_doername,0);
						//สลับสีแถว
						if($nub%2==0){
						echo "<tr class=\"odd\" align=center>";
						} else {
						echo "<tr class=\"even\" align=center>";
						}
							echo "<td>$AssignNo</td>";
							echo "<td>$AssignDate</td>";
							echo "<td>$allSubname</td>";
							echo "<td>$AssignName</td>";
							echo "<td>$DoerName</td>";
							echo "<td>$DoerStamp</td>";
							echo "<td><a onclick=\"javascript:popU('detail_report.php?AssignNo=$AssignNo','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1280,height=720')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></td>";
						if($user_emlevel<=1){
							echo "<td><img src=\"images/del.png\" height=\"20\" width=\"20\" style=\"cursor:pointer;\" onclick=\"javascript:popU('detail_report.php?AssignNo=$AssignNo&cancle=Y','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1280,height=720')\"style=\"cursor:pointer\" \"></td>";
						}
						echo "</tr>";
					} // end while
					if($nub == 0){
						echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
						}
				?>
							<tr bgcolor="#6699FF">
								<td colspan="12" align="left"><b>รายการทั้งหมด <?php echo $nub;?> รายการ<b></td>
							</tr>
			</table>
	</fieldset>
</div>


