<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>อนุมัติแก้ไขรายละเอียดบิลขอสินเชื่อ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<form name="form1" method="post" action="process_fa.php" enctype="multipart/form-data">
<table width="900" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>       
		<div class="header"><h1></h1></div>
		<div class="wrapper">
			<div align="center"><h2>อนุมัติแก้ไขรายละเอียดบิลขอสินเชื่อ</h2></div>
			<div align="right"><input name="button" type="button" onclick="window.close();" value=" X ปิด " /></div>

			<div style="padding:5px;"></div>
			<div>
			<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#FFE4B5" align="center">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#FFE4B5" align="center">
				<td>เลขที่ใบแจ้งหนี้</td>
				<td>ชื่อผู้ขายบิล</td>
				<td>ชื่อลูกหนี้ของผู้ขายบิล</td>
				<td>วันที่ใบแจ้งหนี้</td>
				<td>ยอดใบแจ้งหนี้</td>
				<td>วันที่วางบิล</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td>ตรวจสอบและทำรายการอนุมัติ</td>
			</tr>

			<?php
				$qry=pg_query("SELECT * FROM \"vthcap_fa_prebill_temp\" 
				where \"statusApp\"='2' and \"prebillID\"::text=\"prebillIDMaster\" and \"edittime\" <> '0'order by \"addStamp\"");
				$numrows=pg_num_rows($qry);
				$i=0;
				$sum=0;
				while($result=pg_fetch_array($qry))
				{
					$userSalebill=$result["userSalebill"];
					$userSalebillName=$result["userSalebillName"];
					$userDebtor=$result["userDebtor"];
					$userDebtorName=$result["userDebtorName"];
					$dateInvoice=$result["dateInvoice"];
					$prebillIDMaster=$result["prebillIDMaster"];
					$numberInvoice=$result["numberInvoice"];
					$totalTaxInvoice=$result["totalTaxInvoice"];
					$dateBill=$result["dateBill"];
					$fullname=$result["addUserName"]; //รหัสพนักงานที่ทำรายการ
					$edittime=$result["edittime"]; //ครั้งที่แก้ไข
					$addStamp=$result["addStamp"]; //วันเวลาที่แก้ไข
					
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#FFEBCD\" align=center>";
					}else{
						echo "<tr bgcolor=\"#FDF5E6\" align=center>";
					}
					
					echo "<td valign=top>$numberInvoice</td>";
					
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
					checktypecus($cusType,$userSalebillName,$userSalebill);
					
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
					checktypecus($cusType,$userDebtorName,$userDebtor);
					
					echo "<td valign=top>$dateInvoice</td>
					<td align=right valign=top>".number_format($totalTaxInvoice,2)."</td>
					<td valign=top>$dateBill</td>
					<td valign=top>$fullname</td>
					<td valign=top>$addStamp</td>";
					
					//ตรวจสอบว่า user สามารถอนุมัติรายการได้หรือไม่
					$qrylevel=pg_query("SELECT ta_get_user_emplevel('$app_user')");
					list($emplevel)=pg_fetch_array($qrylevel);
					
					//กรณีผู้อนุมัติไม่ใช่คนเดียวกับผู้ทำรายการ หรือถ้าเป็นคนเดียวกัน ต้องมี  emplevel<=1 จึงสามารถอนุมัีติได้
					if(($addUser!=$app_user) || ($addUser==$app_user and $emplevel<=1)){
						echo"<td valign=top><span onclick=\"javascript:popU('frm_CompareEdit.php?prebillIDMaster=$prebillIDMaster&edittime=$edittime','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=800')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>";
					}else{
						echo "<td valign=top>ไม่ให้อนุมัติตนเอง</td>";
					}
					echo "</tr>";
				}
				if($numrows==0){
					echo "<tr><td colspan=9 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบรายการ-</b></td></tr>";
				}
				?>
			</table>
			</div>
		</div>
		
		<!--แสดงประวัติการอนุมัติ-->
		<div style="padding-top:20px;">
		<?php
		$txthead="แสดงประวัติการอนุมัติ 30 รายการล่าสุด";
		$limit="limit 30";
		include "frm_history_edit.php";
		?>
		</div>
	</td>
</tr>
</table>  
	 
</form>  
<?php
function checktypecus($cusType,$cusMain,$cusID){
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