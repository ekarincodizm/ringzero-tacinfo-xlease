<?php
$showall=pg_escape_string($_GET['showall']);
if($showall==1){
	include("../../config/config.php");
	$limit="";
}else{
	$limit="limit (30)";
}
	$qhis = "select \"debtInvID\",\"contractID\",\"thcap_fullname\",\"debtDueDate\",\"addrSend\",\"sendduedate\",
			\"select_date\",\"selectname\",\"selectstatusname\"
			from \"Vthcap_send_invoice\" 
			where \"print_user\" is null and \"sendduedate\" <= current_date and \"status_sent\"='FALSE'
			order by \"select_date\" DESC $limit";
	$qrhis = pg_query($qhis);
	if($qrhis)
	{
		$rowhis = pg_num_rows($qrhis);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ใบแจ้งหนี้ที่ยกเลิกการส่ง</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
   <link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>	
	
	
<fieldset style="padding:15px;">
	<legend>
	<?php 
	if($showall==1){
		?>
		<b>ใบแจ้งหนี้ที่ยกเลิกการส่งทั้งหมด</b>
	<?php
	}else{
		?>
		<b>ใบแจ้งหนี้ที่ยกเลิกการส่ง 30 รายการล่าสุด ( <font color="blue"><a onclick="popU('frm_history_cancelsent_invoice.php?showall=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')" style="cursor:pointer;"><u> ทั้งหมด </u></a></font>)</b>
	<?php
	}
	?>
	</legend>
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    	<tr style="background-color:#CCCCCC;">
        	<th>รหัสใบแจ้งหนี้</th>
			<th>เลขที่สัญญา</th>
			<th>ชื่อผู้กู้หลัก</th>
			<th>วันที่กำหนดส่งใบแจ้งหนี้</th>
			<th>วันที่ครบกำหนด<br>ในใบแจ้งหนี้</th>
			<th width="200">สถานที่ส่งจดหมาย</th>
            <th>ผู้ยกเลิก</th>
			<th>วันเวลาที่ยกเลิก </th>
			<th>เหตุผลที่ยกเลิก</th>
        </tr>
        <?php
		$i = 0;
		if($rowhis==0)
		{
			echo "
				<tr class=\"odd\">
					<td colspan=\"9\" align=\"center\">********************************* ไม่มีข้อมูล *********************************</td>
				</tr>
			";
		}
		else
		{
			while($res = pg_fetch_array($qrhis))
			{
				$debtInvID=trim($res["debtInvID"]); // รหัสใบแจ้งหนี้
				$contractID=trim($res["contractID"]); // เลขที่สัญญา
				$thcap_fullname=trim($res["thcap_fullname"]); // ชื่อลูกค้า
				$debtDueDate=trim($res["debtDueDate"]); // กำหนดชำระเงิน
				$thcap_address=trim($res["addrSend"]); // ที่อยู่
				$sendduedate=trim($res["sendduedate"]); // วันที่กำหนดส่งใบแจ้งหนี้
				$select_date=trim($res["select_date"]); // วันที่ยกเลิก
				$selectname=trim($res["selectname"]); // ผู้ยกเลิก
				$selectstatusname=trim($res["selectstatusname"]); // เหตุผลที่ยกเลิก
				
				if($i%2==0){
					echo "<tr bgcolor=\"#EEEEEE\" align=center>";
				}else{
					echo "<tr bgcolor=\"#DDDDDD\" align=center>";
				}
				echo "
				<td valign=top><span onclick=\"javascript:popU('../thcap/Channel_detail_i.php?debtInvID=$debtInvID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\" style=\"cursor:pointer;\"><u>$debtInvID</u></span></td>
				<td valign=top><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$contractID</u></span></td>
				<td align=left valign=top>$thcap_fullname</td>
				<td valign=top>$sendduedate</td>
				<td valign=top>$debtDueDate</td>
				<td align=left valign=top>$thcap_address</td>
				<td align=left>$selectname</td>
				<td>$select_date</td>
				<td>$selectstatusname</td>
				</tr>
				";
				$i++;
			}	
		}
		?>
    </table>
	<br>
	<?php
	if($showall==1){
	?>
	<center><input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:70px;"></center>
	<?php
	}
	?>
</fieldset>

</body>
</html>