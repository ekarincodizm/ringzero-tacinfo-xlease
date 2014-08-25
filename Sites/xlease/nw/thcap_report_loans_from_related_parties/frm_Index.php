<?php
include("../../config/config.php");

$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server // วันเวลาปัจจุบัน
$nowYear = substr($nowDate,0,4); // ปีปัจจุบัน
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการกู้เงินจากกิจการที่เกี่ยวข้องกัน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		function s_report()
		{
			$("#payUser").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
			$("#payUser").load("payUser_or_purchaseUser.php?frame=payUser&year="+$("#year").val()+"&ticketStatus="+$("#ticketStatus").val());
			
			$("#purchaseUser").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
			$("#purchaseUser").load("payUser_or_purchaseUser.php?frame=purchaseUser&year="+$("#year").val()+"&ticketStatus="+$("#ticketStatus").val());
		}
	</script>
	
</head>
<body>

<center>
<h2>(THCAP) รายงานการกู้เงินจากกิจการที่เกี่ยวข้องกัน</h2>
</center>

<form name="frm1" method="post" action="frm_Index.php">
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>เงื่อนไข</B></legend>
				<table width="100%">
					<tr>
						<td align="center">
							<b>ปีที่เกิดของตั๋วเงิน :</b>
							<select id="year">
								<option value="all" selected>ทั้งหมด</option>
								<?php
								for($y=2010; $y<=$nowYear; $y++)
								{
									echo "<option value=\"$y\">$y</option>";
								}
								?>
							</select>
							
							&nbsp;&nbsp;&nbsp;
							
							<b>สถานะตั๋ว :</b>
							<select id="ticketStatus">
								<option value="all" selected>ทั้งหมด</option>
								<option value="closed">ปิดตั๋วแล้ว</option>
								<option value="notClose">ยังไม่ปิดตั๋ว</option>
							</select>
							
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
							<input type="button" value="ค้นหา" style="width:70px;height:30px;" onClick="s_report();">
						</td>
					</tr>
				</table>
			</fieldset>
			
			<br>
			<div id="payUser"></div>
			
			<br>
			<div id="purchaseUser"></div>
		</td>
	</tr>
</table>
</form>

</body>
</html>