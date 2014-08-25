<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
/*
var refreshId1 = setInterval(function(){
    $('#div_refresh').load('frm_Approve.php');
}, 5000); //Refresh
*/
</script>

</head>
<body>
<div id="div_refresh">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติค่าเบี้ย</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<th>สถานะรายการ</th>
				<th>เลขที่โฉนด</th>
				<th>เลขที่สัญญา</th>
				<th>เบี้ยรวม</th>
				<th>ผู้ทำรายการ</th>
				<th>วันเวลาที่ทำรายการ</th>	
				<th>รายละเอียด</th>	
				<td width="60">อนุมัติ</td>
				<td>ไม่อนุมัติ</td>
			</tr>
			<?php
			$qrychip=pg_query("SELECT auto_id, \"refDeedContract\", b.\"fullname\" as addUser, \"addStamp\", \"totalChip\",\"statusInsure\" 
					FROM thcap_insure_checkchip a
					left join \"Vfuser\" b on a.\"addUser\"= b.\"id_user\"
					where \"statusApp\" = '2' order by auto_id");
			$numrowchip=pg_num_rows($qrychip);
			while($reschip=pg_fetch_array($qrychip)){
				list($auto_id,$refDeedContract,$addUser,$addStamp,$totalChip,$statusInsure)=$reschip;
				
				if($statusInsure=="0"){
					$txtinsure="ประกันใหม่";
							
					//ค้นหาเลขที่โฉนด
					$qrynumdeed=pg_query("SELECT \"numDeed\" FROM nw_securities where \"securID\"='$refDeedContract'");
					list($numDeed)=pg_fetch_array($qrynumdeed);
							
					$contractID="-";
				}else{
					$txtinsure="ต่ออายุ";
					$numDeed="-";
					$contractID=$refDeedContract;
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $txtinsure; ?></td>
				<td align="left"><?php echo $numDeed; ?></td>
				<td align="center"><?php echo $contractID; ?></td>
				<td align="right"><?php echo number_format($totalChip,2); ?></td>
				<td align="left"><?php echo $addUser; ?></td>
				<td><?php echo $addStamp; ?></td>
				<td>
					<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('show_detailchkChip.php?auto_id=<?php echo $auto_id; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')" style="cursor: pointer;">
				</td>
				<td><span style="cursor:pointer;" onclick="if(confirm('ยืนยันการอนุมัติ!!')){location.href='process_approve.php?auto_id=<?php echo $auto_id; ?>&stsapp=1&val=2';}"><u>อนุมัติ</u></span></td>
				<td><span style="cursor:pointer;" onclick="javascript:popU('process_approve.php?auto_id=<?php echo $auto_id; ?>&stsapp=0&val=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=250')" style="cursor: pointer;"><u>ไม่อนุมัติ</u></span></td>
				
				
			</tr>
			<?php
			} //end while
			if($numrowchip == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table><br><br>


<!--อนุมัติห้องชุด-->
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติใบคำขอ</td>
			</tr>
			
			<tr style="font-weight:bold;" valign="middle" bgcolor="#F3CC43" align="center">
				<td>สถานะรายการ</td>
				<td>เลขที่สัญญา</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td>รายละเอียด</td>
				<td width="60">อนุมัติ</td>
				<td>ไม่อนุมัติ</td>
			</tr>
			
			<?php
			$qryrequest=pg_query("SELECT a.auto_id,a.\"ContractID\", b.\"fullname\" as addUser, a.\"addStamp\",a.\"statusInsure\",\"checkchipID\"
					FROM thcap_insure_temp a
					left join \"Vfuser\" b on a.\"addUser\"= b.\"id_user\"
					where \"statusApprove\" = '2' order by auto_id");
			$numrequest=pg_num_rows($qryrequest);
			while($resrequest=pg_fetch_array($qryrequest)){
				list($auto_id2,$ContractID2,$addUser2,$addStamp2,$statusInsure2,$checkchipID)=$resrequest;
				
				if($statusInsure2=="0"){
					$txtinsure="ประกันใหม่";
				}else if($statusInsure2=="1"){
					$txtinsure="ต่ออายุ";
				}else if($statusInsure2=="2"){
					$txtinsure="แก้ไขข้อมูลให้ตรงกรมธรรม์";
				}else if($statusInsure2=="3"){
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
				<td><?php echo $ContractID2; ?></td>
				<td align="left"><?php echo $addUser2; ?></td>
				<td><?php echo $addStamp2; ?></td>
				<td>
					<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('frm_showRequest.php?auto_id=<?php echo $auto_id2; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=850')" style="cursor: pointer;">
				</td>
				<td><span style="cursor:pointer;" onclick="if(confirm('ยืนยันการอนุมัติ!!')){location.href='process_approve.php?method=addreq&stsapp2=1&auto_id=<?php echo $auto_id2?>&stsinsure=<?php echo $statusInsure2;?>&val=2';}"><u>อนุมัติ</u></span></td>
				<td><span style="cursor:pointer;" onclick="javascript:popU('process_approve.php?method=addreq&auto_id=<?php echo $auto_id2; ?>&stsapp2=0&val=1&checkchipID=<?php echo $checkchipID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=250')" style="cursor: pointer;"><u>ไม่อนุมัติ</u></span></td>
				
				
			</tr>
			<?php
			} //end while
			if($numrequest == 0){
				echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
</div>
</body>
</html>