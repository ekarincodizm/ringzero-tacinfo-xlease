<?php
if($limit==""){
	include("../../config/config.php");
	$txthead="ประวัติการอนุมัติทั้งหมด";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ประวัติการอนุมัติบิลขอสินเชื่อ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<!--แสดงประวัติการอนุมัติ -->
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F4F4F4" align="center">
<tr bgcolor="#FFFFFF">
	<td colspan="11" align="left" style="font-weight:bold;"><?php echo $txthead;?> <?php if($limit=="limit 30"){?>(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=650')"><u>ทั้งหมด</u></a>)<?php } ?></td>
</tr>
<tr style="font-weight:bold;" valign="middle" bgcolor="#D4D4D4" align="center">
	<td>ใบแจ้งหนี้</td>
	<td>ชื่อผู้ขายบิล</td>
	<td>ชื่อลูกหนี้ของผู้ขายบิล</td>
	<td>ผู้ทำรายการ</td>
	<td>วันเวลาที่ทำรายการ</td>
	<td>ผู้อนุมัติ</td>
	<td>วันเวลาที่อนุมัติ</td>
	<td>ผลการอนุมัติ</td>
	<td>รายละเอียด</td>
</tr>


<?php
	$qry=pg_query("SELECT \"prebillIDMaster\",\"numberInvoice\", 
	\"userSalebillName\" as \"userSalebill\",\"userDebtorName\" as \"userDebtor\",
	\"addUserName\" as \"fullname\", \"addStamp\",\"edittime\",
	\"statusApp\",\"statusAppName\" as \"txtapp\",\"appUserName\" as \"appUser\", \"appStamp\"
	  FROM \"vthcap_fa_prebill_temp\" 
	  where \"statusApp\"<>'2' and \"prebillIDMaster\"=\"prebillID\"::text and \"edittime\"='0' order by \"appStamp\" DESC $limit");
	$numrows=pg_num_rows($qry);
	$i=0;
	$sum=0;
	while($result=pg_fetch_array($qry)){
		$prebillIDMaster2=$result["prebillIDMaster"];
		$numberInvoice=$result["numberInvoice"];
		$userSalebill=$result["userSalebill"];
		$userDebtor=$result["userDebtor"];
		$fullname=$result["fullname"];
		$addStamp=$result["addStamp"];
		$statusApp=$result["statusApp"];
		$appStamp=$result["appStamp"];
		$appUser=$result["appUser"];
		$txtapp=$result["txtapp"];
		$edittime=$result["edittime"]; //ครั้งที่แก้ไขข้อมูล ถ้าเป็นประวัติการเพิ่มจะ = 0 เสมอ
				
		$i+=1;
		if($i%2==0){
			echo "<tr bgcolor=\"#F9F9F9\" align=center>";
		}else{
			echo "<tr bgcolor=\"#F3F3F3\" align=center>";
		}
			
		echo "<td>$numberInvoice</td>
			<td align=left>$userSalebill</td>
			<td align=\"left\">$userDebtor</td>
			<td align=\"left\">$fullname</td>
			<td>$addStamp</td>
			<td align=left>$appUser</td>
			<td>$appStamp</td>
			<td>$txtapp</td>";
		echo "<td><span onclick=\"javascript:popU('fa_bill_detail.php?prebillIDMaster=$prebillIDMaster2&statusApp=$statusApp&edittime=0','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr><td colspan=8 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบประวัติ-</b></td></tr>";
	}
	?>

</table>    
</body>
</html>