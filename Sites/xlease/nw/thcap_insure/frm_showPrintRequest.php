<?php
include("../../config/config.php");
$nowdate=$_GET['startDate'];

$method=$_GET["method"];
$id=$_GET["auto_id"];
if($method=="noapp"){
	$upchk="UPDATE thcap_insure_temp
	SET \"statusApprove\"='3' WHERE auto_id=$id";
	if($reschk=pg_query($upchk)){
	}else{
		$status++;
	}
}
?>


	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">	
		<tr style="font-weight:bold;" valign="middle" bgcolor="#F3CC43" align="center">
			<td>สถานะรายการ</td>
			<td>เลขที่สัญญา</td>
			<td>ผู้ทำรายการ</td>
			<td>วันเวลาที่ทำรายการ</td>
			<td>สถานะอนุมัติ</td>
			<td>ผู้อนุมัติ</td>
			<td>วันเวลาที่อนุมัติ</td>
			<td>รายละเอียด</td>
			<td></td>
		</tr>
			
			<?php
			$qryrequest=pg_query("SELECT a.auto_id,a.\"ContractID\", b.\"fullname\" as addUser, a.\"addStamp\",a.\"statusInsure\",
			c.\"fullname\" as appUser,\"appStamp\",\"statusApprove\",\"checkchipID\"
					FROM thcap_insure_temp a
					left join \"Vfuser\" b on a.\"addUser\"= b.\"id_user\"
					left join \"Vfuser\" c on a.\"appUser\"= c.\"id_user\"
					left join \"thcap_insure_main\" d on a.\"auto_id\"=d.\"auto_tempID\"
					where date(\"addStamp\")='$nowdate' and \"statusApprove\" <> '3' and (d.\"Active\" = 'TRUE' or d.\"Active\" is null) order by auto_id");
			$numrequest=pg_num_rows($qryrequest);
			while($resrequest=pg_fetch_array($qryrequest)){
				list($auto_id,$ContractID,$addUser,$addStamp,$statusInsure,$appUser,$appStamp,$statusApprove,$checkchipID)=$resrequest;
				
				if($statusInsure=="0"){
					$txtinsure="ประกันใหม่";
				}else if($statusInsure=="1"){
					$txtinsure="ต่ออายุ";
				}else if($statusInsure=="2"){
					$txtinsure="แก้ไขข้อมูลให้ตรงกรมธรรม์";
				}else if($statusInsure=="3"){
					$txtinsure="แก้ไขข้อมูลโดยการสลักหลัง";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#FAFFEA align=center>";
				}else{
					echo "<tr bgcolor=#F3FFCE align=center>";
				}
				
			?>
				<td><?php echo $txtinsure; ?></td>
				<td><?php echo $ContractID; ?></td>
				<td align="left"><?php echo $addUser; ?></td>
				<td><?php echo $addStamp; ?></td>
				<?php
				//สถานะอนุมัติ
				if($statusApprove=="0"){
					$txtapp="ไม่อนุมัติ";
				}else if($statusApprove=="1"){
					$txtapp="อนุมัติ";
				}else{
					$txtapp="รออนุมัติ";
				}
				if($statusApprove==0){
					$method="noapp";
				}else{
					$method="1";
				}
				?>
				<td align="center"><?php echo $txtapp;?></td>
				<td><?php echo $appUser; ?></td>
				<td><?php echo $appStamp; ?></td>
				<td>
					<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('frm_showRequest.php?auto_id=<?php echo $auto_id; ?>&method=<?php echo $method;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=850')" style="cursor: pointer;">
				</td>
				<?php
				if($statusApprove==0){ //ให้รับทราบเพื่ีอให้หายไปจากหน้านี้
					echo "<td align=center><span style=\"cursor:pointer;\" onclick=\"if(confirm('ยืนยันการรับทราบ!!')){location.href='frm_PrintRequest.php?auto_id=$auto_id&method=$method'}\"><u>รับทราบ</u></span></td>";
				}else if($statusApprove==1){ //ให้สามารถเลือกแจ้งงานต่อได้
						echo "<td align=center><a href=\"pdf_request.php?auto_id=$auto_id\" target=\"_blank\">พิมพ์คำขอ</a></td>";					
				}else{ //ไม่สามารถทำอะไรได้รออนุมัติข้อมูลก่อน
					echo "<td align=center>-</td>";
				}
				?>
			</tr>
			<?php
			} //end while
			if($numrequest == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
