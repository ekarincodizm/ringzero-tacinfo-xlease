<?php
$realpath = redirect($_SERVER['PHP_SELF'],'nw/manageCustomer/');
$ref_path = redirect($_SERVER['PHP_SELF'],'nw/search_cusco/');
$qry_temp=pg_query("select \"CustempID\",edittime,b.\"fullname\" as add_user,a.\"add_date\",c.\"fullname\" as app_user,a.\"app_date\",a.\"statusapp\" from \"Customer_Temp\" a 
			left join \"Vfuser\" b on a.\"add_user\"=b.\"id_user\"
			left join \"Vfuser\" c on a.\"app_user\"=c.\"id_user\"
			WHERE \"CusID\" = '$CusID' order by a.\"edittime\" DESC");
			$numrows=pg_num_rows($qry_temp);

			if($hidden==1){
			$hiddenT="hidden";
			} else {
			$hiddenT="";
			}
?>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<html>
<body>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;" <?php echo $hiddenT; ?>>ประวัติการแก้ไขข้อมูลลูกค้า</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>ครั้งที่</td>
				<td>ประเภทการขออนุมัติ</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td>ผู้อนุมัติรายการ</td>
				<td>วันเวลาอนุมัติรายการ</td>
				<td>ผลการแก้ไข</td>
				<td>ดูข้อมูล</td>
			</tr>
			<?php
			
			while($res_fr=pg_fetch_array($qry_temp)){
				$CustempID=$res_fr["CustempID"];
				$edittime=$res_fr["edittime"];
				$add_user = $res_fr["add_user"]; 
				$add_date = $res_fr["add_date"];
				$app_user = $res_fr["app_user"];
				$app_date = $res_fr["app_date"]; 
				$statusapp = $res_fr["statusapp"];
				
				if($edittime ==0){
					$txttype="ขอเพิ่มข้อมูล";
				}else{
					$txttype="ขอแก้ไขข้อมูล";
				}
				
				
				if($statusapp=="0"){ //กรณีเป็นการเพิ่มข้อมูล
					$txtapp="ไม่อนุมัติ";
				}else if($statusapp=="1"){
					$txtapp="อนุมัติ";
				}else if($statusapp=="2"){
					$txtapp="รออนุมัติ";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $edittime; ?></td>
				<td><?php echo $txttype; ?></td>
				<td align="left"><?php echo $add_user; ?></td>
				<td><?php echo $add_date; ?></td>
				<td align="left"><?php echo $app_user;?></td>
				<td><?php echo $app_date;?></td>
				<td><?php echo $txtapp;?></td>
				<td><span onclick="javascript:popU('<?php echo $realpath ?>showalldetail.php?CustempID=<?php echo $CustempID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
			</tr>
			<?php
			} //end while
			if($numrows == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			<tr><td colspan="8"><font color="red" size="2"><b>* <U>ผู้อนุมัติและวันเวลาอนุมัติเท่ากับค่าว่าง</U> หมายถึง ความผิดพลาดของระบบในการบันทึกข้อมูล แต่ไม่มีผลกระทบกับข้อมูล</b></font></td></tr>
			</table>
</body>
</html>