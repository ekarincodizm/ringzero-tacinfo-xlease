<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) FA อนุมัติบิลขอสินเชื่อ</title>
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
			<div align="center"><h2>(THCAP) FA อนุมัติบิลขอสินเชื่อ</h2></div>
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
				<td>ตรวจสอบและทำรายการอนุมัติ</td>
			</tr>

			<?php
				//แสดงเฉพาะรายการที่
				$qry=pg_query("SELECT \"numberInvoice\",\"prebillIDMaster\",
				\"userSalebillName\" as \"userSalebill\",\"userDebtorName\" as \"userDebtor\",				
				\"totalTaxInvoice\",\"dateInvoice\",\"dateBill\",\"addUserName\",\"edittime\"
				FROM \"vthcap_fa_prebill_temp\" 
				where \"prebillIDMaster\"=\"auto_id\"::text AND \"statusApp\"='2' AND \"edittime\"='0' order by \"dateBill\"");
				$numrows=pg_num_rows($qry);
				$i=0;
				$sum=0;
				while($result=pg_fetch_array($qry)){
					$userSalebill=$result["userSalebill"];
					$userDebtor=$result["userDebtor"];			
					$dateInvoice=$result["dateInvoice"];
					$prebillIDMaster=$result["prebillIDMaster"];
					$numberInvoice=$result["numberInvoice"];
					$totalTaxInvoice=$result["totalTaxInvoice"];
					$dateBill=$result["dateBill"];
					$fullname=$result["addUserName"]; //รหัสพนักงานที่ทำรายการ
					$edittime=$result["edittime"]; //ครั้งที่ทำรายการ 
					
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#FFEBCD\" align=center>";
					}else{
						echo "<tr bgcolor=\"#FDF5E6\" align=center>";
					}
						
					echo "<td valign=top>$numberInvoice</td>
						<td align=\"left\" valign=top>$userSalebill</td>
						<td align=left valign=top>$userDebtor</td>
						<td valign=top>$dateInvoice</td>
						<td align=right valign=top>".number_format($totalTaxInvoice,2)."</td>
						<td valign=top>$dateBill</td>
						<td valign=top>$fullname</td>
					";
					
					//ตรวจสอบว่า user สามารถอนุมัติรายการได้หรือไม่
					$qrylevel=pg_query("SELECT ta_get_user_emplevel('$app_user')");
					list($emplevel)=pg_fetch_array($qrylevel);
					
					//กรณีผู้อนุมัติไม่ใช่คนเดียวกับผู้ทำรายการ หรือถ้าเป็นคนเดียวกัน ต้องมี  emplevel<=1 จึงสามารถอนุมัีติได้
					if(($addUser!=$app_user) || ($addUser==$app_user and $emplevel<=1)){
						echo"<td valign=top><span onclick=\"javascript:popU('frm_ShowApprove.php?prebillIDMaster=$prebillIDMaster&edittime=$edittime&stsprocess=I','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>";
					}else{
						echo "<td valign=top>ไม่ให้อนุมัติตนเอง</td>";
					}
					echo "</tr>";
				}
				if($numrows==0){
					echo "<tr><td colspan=8 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบรายการ-</b></td></tr>";
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
		include "frm_history.php";
		?>
		</div>
	</td>
</tr>
</table>  
	 
</form>      
</body>
</html>