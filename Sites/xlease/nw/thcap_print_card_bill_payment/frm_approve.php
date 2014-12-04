<?php
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) อนุมัติพิมพ์ Card Bill Payment</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		function popU(U,N,T) {
			newWindow = window.open(U, N, T);
		}
	</script>
</head>

<body>
<center>
	<h1>(THCAP) อนุมัติพิมพ์ Card Bill Payment</h1>

	<table width="90%">
		<tr>
			<td>
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr bgcolor="#FFFFFF">
						<td colspan="7" align="left" style="font-weight:bold;">รายการที่รออนุมัติ</td>
					</tr>
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
						<th>ชื่อ-นามสกุลลูกค้า</th>
						<th>ยอดผ่อนขั้นต่ำ</th>
						<th>วันที่ครบกำหนดชำระงวดแรก</th>
						<th>จ่ายทุกวันที่</th>
						<th>ผู้ทำรายการ</th>
						<th>วันเวลาที่ทำรายการ</th>
						<th>ทำรายการ</th>
					</tr>
					<?php
					$qry_wait = pg_query("
											SELECT
												a.\"autoID\",
												a.\"CusFullName\",
												a.\"minPayment\",
												a.\"firstDueDate\",
												a.\"payDay\",
												b.\"fullname\",
												a.\"doerStamp\"
											FROM
												\"thcap_print_card_bill_payment\" a
											LEFT JOIN
												\"Vfuser\" b ON b.\"id_user\" = a.\"doerID\"
											WHERE
												a.\"appvStatus\" = '9'
											ORDER BY
												a.\"doerStamp\"
										");
					$i = 0;
					while($res_wait = pg_fetch_array($qry_wait))
					{
						$i++;
						$autoID = $res_wait["autoID"]; // ลำดับรายการ
						$CusFullName = $res_wait["CusFullName"]; // ชื่อลูกค้า
						$contractID = $res_wait["contractID"]; // เลขที่สัญญา
						$minPayment = $res_wait["minPayment"]; // ยอดผ่อนขั้นต่ำ
						$firstDueDate = $res_wait["firstDueDate"]; // วันที่ครบกำหนดชำระงวดแรก
						$payDay = $res_wait["payDay"]; // จ่ายทุกวันที่
						$fullname = $res_wait["fullname"]; // รหัสพนักงานที่ทำรายการ
						$doerStamp = $res_wait["doerStamp"]; // วันเวลาที่ทำรายการ
						
						if($i%2==0){
							echo "<tr class=\"odd\" align=center>";
						}else{
							echo "<tr class=\"even\" align=center>";
						}
						
						echo "<td align=\"left\">$CusFullName</td>";
						echo "<td align=\"right\">".number_format($minPayment,2)."</td>";
						echo "<td align=\"center\">$firstDueDate</td>";
						echo "<td align=\"center\">$payDay</td>";
						echo "<td align=\"left\">$fullname</td>";
						echo "<td align=\"center\">$doerStamp</td>";
						echo "<td align=\"center\"><img src=\"../thcap/images/edit.png\" height=\"19\" width=\"19\" style=\"cursor:pointer;\" onClick=\"javascript:popU('popup_approve.php?id=$autoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')\"></td>";
						
						echo "</tr>";
					}
					
					if($i == 0)
					{
						echo "<tr><td colspan=\"8\" align=\"center\">--ไม่พบข้อมูล--</td></tr>";
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td>
			<br/><br/>
			<?php include("frm_history_limit.php"); ?>
			</td>
		</tr>
	</table>
</center>
</body>
</html>