<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
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
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) อนุมัติแก้ไขที่อยู่สัญญา </td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>ผู้ขอแก้ไขรายการ</td>
				<td>วันเวลาแก้ไขรายการ</td>
				<td></td>
			</tr>
			<?php
			$qry_temp=pg_query("select \"tempID\",\"contractID\",fullname,\"addStamp\" from \"thcap_addrContractID_temp\" a
			left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\" where \"statusApp\" ='2' and \"addsType\"='3' and \"withContractEdit\" is null order by \"addStamp\" asc");
			$nub=pg_num_rows($qry_temp);
			while($res=pg_fetch_array($qry_temp)){
				$tempID=$res["tempID"];
				$contractID=$res["contractID"];
				$addStamp=$res["addStamp"];
				$fullname=$res["fullname"];
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td align="center"><?php echo $addStamp; ?></td>
				<td>
					<span onclick="javascript:popU('frm_EditAddress_compare.php?tempID=<?php echo $tempID; ?>&contractID=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
				</td>
				
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

<br><br>
<table width="800" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F4F4F4" align="center"  style="margin-top:1px">
<tr bgcolor="#FFFFFF">
	<td colspan="11" style="font-weight:bold;">ประวัติการอนุมัติ 30 รายการล่าสุด  (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historyapp_edit.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </td>
</tr>
<tr style="font-weight:bold;" valign="middle" bgcolor="#D4D4D4" align="center">
	<td>เลขที่สัญญา</td>
	<td>ผู้ขอแก้ไข</td>
	<td>วันเวลาขอแก้ไข</td>
	<td>ผู้ทำรายการอนุมัติ</td>
	<td>วันเวลาทำรายการอนุมัติ</td>
	<td>ผลการอนุมัติ</td>
	<td>ตรวจสอบการเปลี่ยนแปลง</td>
</tr>


<?php
	$qry=pg_query("SELECT \"tempID\", \"contractID\", b.\"fullname\", \"addStamp\",c.\"fullname\" as \"appUser\",\"appStamp\",\"statusApp\"
	  FROM \"thcap_addrContractID_temp\" a
	  left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
	  left join \"Vfuser\" c on a.\"appUser\"=c.\"id_user\"
	  where \"statusApp\" in('0','1') and \"addsType\"='3' order by \"appStamp\" DESC limit(30);");
	$numrows=pg_num_rows($qry);
	$i=0;
	$sum=0;
	while($result=pg_fetch_array($qry)){
		$tempID=$result["tempID"];
		$contractID=$result["contractID"];
		$fullname=$result["fullname"];
		$addStamp=$result["addStamp"];
		$appUser=$result["appUser"];
		$appStamp=$result["appStamp"];
		$statusApp=$result["statusApp"];
				
		if($statusApp=="0"){
			$txtapp="ไม่อนุมัติ";
		}else{
			$txtapp="อนุมัติ";
		}
		$i+=1;
		if($i%2==0){
			echo "<tr bgcolor=\"#F9F9F9\" align=center>";
		}else{
			echo "<tr bgcolor=\"#F3F3F3\" align=center>";
		}
			
		echo "
			<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td>
			<td align=left>$fullname</td>
			<td align=\"center\">$addStamp</td>
			<td align=\"left\">$appUser</td>
			<td align=\"center\">$appStamp</td>
			<td align=\"center\">$txtapp</td>
			<td align=\"center\"><span onclick=\"javascript:popU('frm_profileAddress_compare.php?tempID=$tempID&contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>
		</tr>
		";		
	}
	if($numrows==0){
		echo "<tr><td colspan=7 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบประวัติการแก้ไข-</b></td></tr>";
	}
	?>
</table>

</body>
</html>