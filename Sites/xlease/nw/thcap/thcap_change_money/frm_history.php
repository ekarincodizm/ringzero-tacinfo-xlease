<?php
include("../../../config/config.php");


//รับค่าการเรียงข้อมูลในตารางหากมีการกด sort ข้อมูล

$strSort = $_GET["sort"];
if($strSort == "")
{
	$strSort = "appvStamp"; // default ไว้ที่วันเวลาที่ทำรายการ
}

$strOrder = $_GET["order"];
if($strOrder == "")
{
	$strOrder = "DESC"; // default ไว้ที่ มากไปน้อย
}

$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติย้ายเงินข้ามสัญญา-ประวัติ</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
	
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

			<center><h2>ประวัติการทำรายการอนุมัติย้ายเงินข้ามสัญญา</h2></center>
			<table align="center" width="95%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
				<tr bgcolor="#FFFFFF">
					<td colspan="14">
						<b><u>หมายเหตุ</u></b><br>
						- <font color="red"><span style="background-color:#FFCCFF;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ รายการที่ใบเสร็จถูกยกเลิกแล้ว</font>
					</td>
				</tr>	
				<tr align="center" bgcolor="#B5B5B5">
					<th>รายการที่</th>
					<th><a href='frm_history.php?sort=begin_conid&order=<?php echo $strNewOrder ?>'><u>ย้ายจากสัญญา</u></a></th>
					<th><a href='frm_history.php?sort=begin_trans_type&order=<?php echo $strNewOrder ?>'><u>จากประเภทเงิน</u></a></th>
					<th><a href='frm_history.php?sort=end_conid&order=<?php echo $strNewOrder ?>'><u>ให้กับสัญญา</u></a></th>
					<th><a href='frm_history.php?sort=end_trans_type&order=<?php echo $strNewOrder ?>'><u>ประเภทเงินที่รับ</u></a></th>
					<th><a href='frm_history.php?sort=changeMoneyDate&order=<?php echo $strNewOrder ?>'><u>วันที่ย้ายเงิน</u></a></th>
					<th><a href='frm_history.php?sort=end_trans_money&order=<?php echo $strNewOrder ?>'><u>จำนวนเงิน</u></a></th>
					<th><a href='frm_history.php?sort=receiptID&order=<?php echo $strNewOrder ?>'><u>ใบเสร็จ</u></a></th>
					<th><a href='frm_history.php?sort=doerID&order=<?php echo $strNewOrder ?>'><u>ผู้ทำรายการ</u></a></th>
					<th><a href='frm_history.php?sort=doerStamp&order=<?php echo $strNewOrder ?>'><u>วันเวลาที่ทำรายการ</u></a></th>
					<th>ตรวจสอบ</th>
					<th><a href='frm_history.php?sort=appvID&order=<?php echo $strNewOrder ?>'><u>ผู้อนุมัติ</u></a></th>
					<th><a href='frm_history.php?sort=appvStamp&order=<?php echo $strNewOrder ?>'><u>วันเวลาที่อนุมัติ</u></a></th>
					<th><a href='frm_history.php?sort=appstatus&order=<?php echo $strNewOrder ?>'><u>สถานะ</u></a></th>
				</tr>
				<?php
				$query = pg_query("select * from public.\"thcap_transfermoney_c2c_temp\" where \"appstatus\" != '2' order by \"$strSort\" $strOrder ");
				$numrows = pg_num_rows($query);
				$i=0;
				while($result = pg_fetch_array($query))
				{
					$i++;
					$tm_pk = $result["tm_pk"]; // PK
					$begin_conid = $result["begin_conid"]; // เลขที่สัญญาต้นทาง
					$begin_trans_type = $result["begin_trans_type"]; // ประเภทเงินต้นทาง
					$end_conid = $result["end_conid"]; // รหัสสัญญาปลายทาง
					$end_trans_type = $result["end_trans_type"]; // รหัสประเภทการโอนปลายทาง
					$end_trans_money = $result["end_trans_money"]; // จำนวนเงินที่รับของสัญญาปลายทาง
					$all_trans_money = $result["all_trans_money"]; // จำนวนเงินทั้งหมดที่โอนจากต้นทาง
					$masterID = $result["masterID"]; // รายการที่ทำพร้อมกัน
					$doerID = $result["doerID"]; // รหัสผู้ขอย้ายเงิน
					$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
					$changeMoneyDate = $result["changeMoneyDate"]; // วันที่ย้ายเงิน
					$appstatus = $result["appstatus"]; // สถานะการอนุมัติ
					$appvID = $result["appvID"]; // รหัสผู้อนุมัติ
					$appvStamp = $result["appvStamp"]; // วันเวลาที่ทำการอนุมัติ
					$receiptID = $result["receiptID"]; // รหัสใบเสร็จ
					
					//ตรวจสอบว่าใบเสร็จถูกยกเลิกหรือยัง
					$chk_receipt = pg_query("SELECT * FROM \"thcap_temp_receipt_otherpay_cancel\" WHERE \"receiptID\" = '$receiptID' ");
					$chk_rows = pg_num_rows($chk_receipt);
					
					if($begin_trans_type == "997")
					{
						$begin_trans_type_text = "เงินค้ำประกันการชำระหนี้";
					}
					elseif($begin_trans_type == "998")
					{
						$begin_trans_type_text = "เงินพักรอตัดรายการ";
					}
					
					if($end_trans_type == "997")
					{
						$end_trans_type_text = "เงินค้ำประกันการชำระหนี้";
					}
					elseif($end_trans_type == "998")
					{
						$end_trans_type_text = "เงินพักรอตัดรายการ";
					}
					
					//เปรียบเทียบสถานะการอนุมัติ
					if($appstatus == '0'){					
						$txtstatus = 'ไม่อนุมัติ';
					}else if($appstatus == '1'){
						$txtstatus = 'อนุมัติ';
					}
					
					$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
					while($result_name = pg_fetch_array($qry_name))
					{
						$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
					}
					
					//หาชื่อผู้อนุมัติ
					$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvID' ");
					while($result_name = pg_fetch_array($qry_name))
					{
						$appfullname = $result_name["fullname"]; // ชื่อผู้อนุมัติ
					}
					
					//หากเป้นใบเสร็จที่ถูกยกเลิกแล้วให้เป้นแถบสีชมพูอ่อน
					IF($chk_rows > 0){
						echo "<tr bgcolor=#FFCCFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFCCFF';\" align=center>";						
					}else{
						if($i%2==0){
							echo "<tr bgcolor=#CFCFCF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#CFCFCF';\" align=center>";
						}else{
							echo "<tr bgcolor=#E8E8E8 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E8E8E8';\" align=center>";
						}
					
					}
					
					echo "<td align=\"center\">$i</td>";
					echo "<td align=\"center\"><a style=\"cursor:pointer;\" onClick=\"javascript:popU('../../thcap_installments/frm_Index.php?idno=$begin_conid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\"><FONT COLOR=#0000FF><u>$begin_conid</u></FONT></a></td>";
					echo "<td align=\"center\">$begin_trans_type_text</td>";
					echo "<td align=\"center\"><a style=\"cursor:pointer;\" onClick=\"javascript:popU('../../thcap_installments/frm_Index.php?idno=$end_conid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\"><FONT COLOR=#0000FF><u>$end_conid</u></FONT></a></td>";
					echo "<td align=\"center\">$end_trans_type_text</td>";
					echo "<td align=\"center\">$changeMoneyDate</td>";
					echo "<td align=\"right\">".number_format($end_trans_money,2)."</th>";
					echo "<td align=\"center\"><a onclick=\"javascript:popU('../Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"blue\"><u>$receiptID</u></font></a></td>";
					echo "<td align=\"left\">$fullname</td>";
					echo "<td align=\"center\">$doerStamp</td>";
					echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_detail.php?tm_pk=$tm_pk&show=show','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
					echo "<td align=\"center\">$appfullname</td>";
					echo "<td align=\"center\">$appvStamp</td>";
					echo "<td align=\"center\">$txtstatus</td>";
					echo "</tr>";
				}
				if($numrows==0){
					echo "<tr bgcolor=#CFCFCF height=50><td colspan=14 align=center><b>ไม่พบรายการ</b></td><tr>";
				}else{
					echo "<tr bgcolor=\"#B5B5B5\" height=30><td colspan=14><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
				}
				?>
			</table>
			<br>
			<center><input type="button" value=" ปิด " style="width:100px;height:50px;" onclick="window.close();"></center>