<?php
if($limit==""){
	include("../../config/config.php");
	$txthead="ประวัติการอนุมัติทั้งหมด";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ประวัติการอนุมัติแก้ไขบิลขอสินเชื่อ</title>
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
	<td colspan="11" align="left" style="font-weight:bold;"><?php echo $txthead;?> <?php if($limit=="limit 30"){?>(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history_edit.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=650')"><u>ทั้งหมด</u></a>)<?php } ?></td>
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
	\"userSalebill\", \"userSalebillName\", \"userDebtor\", \"userDebtorName\",
	\"addUserName\" as \"fullname\", \"addStamp\",\"edittime\",
	\"statusApp\",\"statusAppName\" as \"txtapp\",\"appUserName\" as \"appUser\", \"appStamp\"
	  FROM \"vthcap_fa_prebill_temp\" 
	  where \"statusApp\"<>'2' and \"prebillIDMaster\"=\"prebillID\"::text and \"edittime\"<>'0' order by \"appStamp\" DESC $limit");
	$numrows=pg_num_rows($qry);
	$i=0;
	$sum=0;
	while($result=pg_fetch_array($qry)){
		$prebillIDMaster2=$result["prebillIDMaster"];
		$numberInvoice=$result["numberInvoice"];
		$userSalebill=$result["userSalebill"];
		$userSalebillName=$result["userSalebillName"];
		$userDebtor=$result["userDebtor"];
		$userDebtorName=$result["userDebtorName"];
		$fullname=$result["fullname"];
		$addStamp=$result["addStamp"];
		$statusApp=$result["statusApp"];
		$appStamp=$result["appStamp"];
		$appUser=$result["appUser"];
		$txtapp=$result["txtapp"];
		$edittime=$result["edittime"];
				
		$i+=1;
		if($i%2==0){
			echo "<tr bgcolor=\"#F9F9F9\" align=center>";
		}else{
			echo "<tr bgcolor=\"#F3F3F3\" align=center>";
		}
			
		echo "<td>$numberInvoice</td>";
		
		// หาประเภทลูกค้า ผู้ขายบิล
		$qry_cusName = pg_query("select \"corpID\" from \"th_corp\" where \"corpID\"::text = '$userSalebill' ");
		$row_cusName = pg_num_rows($qry_cusName);
		if($row_cusName == "1")
		{
			$cusType = "2"; // ลูกค้านิติบุคคล
		}
		else
		{
			$cusType = "1"; // ถ้าไม่ใช่นิติบุคคล จะถือว่าเป็นบุคคลธรรมดา
		}
		//เพิ่ม การ link ข้อมูล ชื่อผู้ขายบิล
		checktypecuslink($cusType,$userSalebillName,$userSalebill);

		// หาประเภทลูกค้า ลูกหนี้
		$qry_cusName = pg_query("select \"corpID\" from \"th_corp\" where \"corpID\"::text = '$userDebtor' ");
		$row_cusName = pg_num_rows($qry_cusName);
		if($row_cusName == "1")
		{
			$cusType = "2"; // ลูกค้านิติบุคคล
		}
		else
		{
			$cusType = "1"; // ถ้าไม่ใช่นิติบุคคล จะถือว่าเป็นบุคคลธรรมดา
		}
		//เพิ่ม การ link ข้อมูล ชื่อลูกหนี้ของผู้ขายบิล
		checktypecuslink($cusType,$userDebtorName,$userDebtor);
		
		echo "<td align=\"left\">$fullname</td>
		<td>$addStamp</td>
		<td align=left>$appUser</td>
		<td>$appStamp</td>
		<td>$txtapp</td>";
		echo "<td><span onclick=\"javascript:popU('frm_DetailEdit.php?prebillIDMaster=$prebillIDMaster2&edittime=$edittime','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr><td colspan=9 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบประวัติ-</b></td></tr>";
	}
	?>

</table> 
<?php
function checktypecuslink($cusType,$cusMain,$cusID){
	if($cusType == 1)
	{
		echo "<td align=\"left\"><a onclick=\"javascript:popU('../manageCustomer/showdetail2.php?CusID=$cusID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$cusMain</u></font></a></td>";	
	}
	elseif($cusType == 2)
	{
		$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusID' ");
		$corp_regis = pg_fetch_result($qry_corp_regis,0);
		echo "<td align=\"left\"><a onclick=\"javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$cusMain</u></font></a></td>";	
	}
	else{echo "<td>$cusMain</td>";}
}
?>    
</body>
</html>