<?php
include("../../../config/config.php");
$readonly = $_GET["readonly"]; //ตัวแปรที่มาจากหน้าดูข้อมูลสำหรับกำหนดว่าตารางรออนุมัตินั้น เมื่อกดตรวจสอบจะสามารถอนุมัติได้หรือไม่
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติย้ายเงินข้ามสัญญา</title>
	
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

</head>

<body>
<?php if($readonly == 'show'){ ?>
	<fieldset>
		<legend>
			<font size="3px;" color="black">
				<b>
					รายการรออนุมัติ
				</b>			
			</font>
		</legend>
<?php }else{ ?>
	<center><h2>(THCAP) อนุมัติย้ายเงินข้ามสัญญา</h2></center>
	<br>
<?php } ?>

<table align="center" width="95%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>ย้ายจากสัญญา</th>
		<th>จากประเภทเงิน</th>
		<th>ให้กับสัญญา</th>
		<th>ประเภทเงินที่รับ</th>
		<th>วันที่ย้ายเงิน</th>
		<th>จำนวนเงิน</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>ตรวจสอบ</th>
	</tr>
	<?php
	$query = pg_query("	select a.*,b.\"BAccount\" as \"begintxt\",c.\"BAccount\" as \"endtxt\"
						from public.\"thcap_transfermoney_c2c_temp\" a 
						left join \"BankInt\" b on a.\"begin_trans_type\" = b.\"BID\"
						left join \"BankInt\" c on a.\"end_trans_type\" = c.\"BID\"
						where \"appstatus\" = '2' order by \"tm_pk\" ");
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
		
		$begin_trans_type_text = $result["begintxt"]; // ประเภทการโอนต้นทาง
		$end_trans_type_text = $result["endtxt"]; // ประเภทการโอนปลายทาง
		
		// if($begin_trans_type == "997")
		// {
			// $begin_trans_type_text = "เงินค้ำประกันการชำระหนี้";
		// }
		// elseif($begin_trans_type == "998")
		// {
			// $begin_trans_type_text = "เงินพักรอตัดรายการ";
		// }
		
		// if($end_trans_type == "997")
		// {
			// $end_trans_type_text = "เงินค้ำประกันการชำระหนี้";
		// }
		// elseif($end_trans_type == "998")
		// {
			// $end_trans_type_text = "เงินพักรอตัดรายการ";
		// }
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\"><a style=\"cursor:pointer;\" onClick=\"javascript:popU('../../thcap_installments/frm_Index.php?idno=$begin_conid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\"><FONT COLOR=#0000FF><u>$begin_conid</u></FONT></a></td>";
		echo "<td align=\"center\">$begin_trans_type_text</td>";
		echo "<td align=\"center\"><a style=\"cursor:pointer;\" onClick=\"javascript:popU('../../thcap_installments/frm_Index.php?idno=$end_conid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\"><FONT COLOR=#0000FF><u>$end_conid</u></FONT></a></td>";
		echo "<td align=\"center\">$end_trans_type_text</td>";
		echo "<td align=\"center\">$changeMoneyDate</td>";
		echo "<td align=\"right\">".number_format($end_trans_money,2)."</th>";
		echo "<td align=\"left\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_detail.php?tm_pk=$tm_pk&show=$readonly','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=500')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=10 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=10><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<?php if($readonly == 'show'){ ?>
	</fieldset>	
<?php } ?>

<!-- ประวัติการทำรายการอนุมัติย้อนหลัง 30 รายการล่าสุด -->
<div style="padding-top:50px;"></div>
<fieldset>
	<legend>
		<font size="3px;" color="black">
			<b>
				ประวัติการอนุมัติ 30 รายการล่าสุด (<font color="blue"><a onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" style="cursor:pointer;"><u>ทั้งหมด</u></a></font>)
			</b>
		</font>
	</legend>
			
			<table align="center" width="95%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
				<tr bgcolor="#FFFFFF">
					<td colspan="14">
						<b><u>หมายเหตุ</u></b><br>
						- <font color="red"><span style="background-color:#FFCCFF;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ รายการที่ใบเสร็จถูกยกเลิกแล้ว</font>
					</td>
				</tr>			
				<tr align="center" bgcolor="#B5B5B5">
					<th>รายการที่</th>
					<th>ย้ายจากสัญญา</th>
					<th>จากประเภทเงิน</th>
					<th>ให้กับสัญญา</th>
					<th>ประเภทเงินที่รับ</th>
					<th>วันที่ย้ายเงิน</th>
					<th>จำนวนเงิน</th>
					<th>ใบเสร็จ</th>
					<th>ผู้ทำรายการ</th>
					<th>วันเวลาที่ทำรายการ</th>
					<th>ตรวจสอบ</th>
					<th>ผู้อนุมัติ</th>
					<th>วันเวลาที่อนุมัติ</th>
					<th>สถานะ</th>
				</tr>
				<?php
				$query = pg_query("	select a.*,b.\"BAccount\" as \"begintxt\",c.\"BAccount\" as \"endtxt\" 
									from public.\"thcap_transfermoney_c2c_temp\" a 
									left join \"BankInt\" b on a.\"begin_trans_type\" = b.\"BID\"
									left join \"BankInt\" c on a.\"end_trans_type\" = c.\"BID\" 
									where a.\"appstatus\" != '2' 
									order by a.\"appvStamp\" DESC limit 30");
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
					
					
					$begin_trans_type_text = $result["begintxt"]; // ประเภทการโอนต้นทาง
					$end_trans_type_text = $result["endtxt"]; // ประเภทการโอนปลายทาง
		
					
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
</fieldset>



</body>
</html>