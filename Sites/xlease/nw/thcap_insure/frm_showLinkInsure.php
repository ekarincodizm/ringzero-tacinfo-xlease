<?php
include("../../config/config.php");
$contractID=$_GET["contractID"];
$insureNum=$_GET["insureNum"];

if($insureNum=="-"){ 
	$insureNum2=""; 
}else{
	$insureNum2="and \"insureNum\" ='$insureNum'"; 
}

?>
<table width="60%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F3CC43">	
	<tr style="font-weight:bold;" valign="middle" bgcolor="#F3CC43" align="center">
		<td>สถานะรายการ</td>
		<td>เลขที่สัญญา</td>
		<td>เลขที่กรมธรรม์</td>
		<td>ผู้ทำรายการ</td>
		<td>วันเวลาที่ทำรายการ</td>
		<td>ผู้อนุมัติ</td>
		<td>วันเวลาที่อนุมัติ</td>
		<td>ใบคำขอ</td>
	</tr>
			
	<?php
	$qryrequest=pg_query("SELECT a.auto_id,a.\"ContractID\", b.\"fullname\" as addUser, a.\"addStamp\",a.\"statusInsure\",
	c.\"fullname\" as appUser,\"appStamp\",\"statusApprove\",\"checkchipID\",a.\"insureNum\"
	FROM thcap_insure_temp a
	left join \"Vfuser\" b on a.\"addUser\"= b.\"id_user\"
	left join \"Vfuser\" c on a.\"appUser\"= c.\"id_user\"
	where \"ContractID\" = '$contractID' and \"statusApprove\"='1' and a.\"statusInsure\" <> '2' order by \"edittime\" ");
			
	$numrequest=pg_num_rows($qryrequest);
			
	while($resrequest=pg_fetch_array($qryrequest)){
		list($auto_id,$ContractID,$addUser,$addStamp,$statusInsure,$appUser,$appStamp,$statusApprove,$checkchipID,$inNum)=$resrequest;
				
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
		<td><?php echo $inNum; ?></td>
		<td align="left"><?php echo $addUser; ?></td>
		<td><?php echo $addStamp; ?></td>
		<td><?php echo $appUser; ?></td>
		<td><?php echo $appStamp; ?></td>
		<td align=center><a href="pdf_request.php?auto_id=<?php echo $auto_id;?>" target="_blank">ใบคำขอ</a></td>						
		</tr>
		<?php
	} //end while
	if($numrequest == 0){
		echo "<tr><td colspan=8 align=center height=50 bgcolor=\"#FFFFFF\"><b>- ไม่พบข้อมูล -</b></td></tr>";
	}else{
	?>
	<tr align="center">
	<td colspan="8" bgcolor="#F4FFF4" height="80">
	<b>เลขที่กรมธรรม์ : </b><input type="text" name="insurenum" id="insurenum" size="30">
	<?php
	if($statusInsure=="1"){
	?>
	<b>เลขที่กรมธรรม์เดิม : </b><input type="text" name="oldinsure" id="oldinsure" size="30"><input type="hidden" name="statusid" id="statusid" value="<?php echo $statusInsure;?>">
	<?php
	}
	?>
	</td>
	</tr>
	
</table>
<div align="center" style="padding:20px;"><input type="button" value=" บันทึก " id="submitButton"><input type="button" value=" ยกเลิก "></div>			
<?php } ?>

<script language=javascript>
$(document).ready(function(){  
	$('#submitButton').click(function(){
		if($("#insurenum").val()=="" || $("#insurenum").val()=="-" || $("#insurenum").val()=="--"){
			alert("กรุณาระบุเลขที่กรมธรรม์");
			$("#insurenum").focus();
			return false;
		}
		if($("#statusid").val()==1){
			if($("#oldinsure").val()=="" || $("#oldinsure").val()=="-" || $("#oldinsure").val()=="--"){
				alert("กรุณาระบุเลขที่กรมธรรม์เดิม");
				$("#oldinsure").focus();
				return false;
			}
		}
		$.post("process_insure.php",{
			cmd : "addLink",
			auto_id :'<?php echo $auto_id;?>',
			insurenum : $("#insurenum").val(), 
			oldinsure : $("#oldinsure").val(), 
		},
		function(data){
			if(data == "1"){
				alert("บันทึกรายการเรียบร้อย");
				location='frm_LinkInsure.php';
			}else if(data == "2"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				$("#submitButton").attr('disabled', false);
			}
			
		});
	});
});

</script>