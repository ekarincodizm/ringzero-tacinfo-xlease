<?php
set_time_limit(0);
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ตัดใบเสร็จค่าใช้จ่ายเก่า</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

	<style type="text/css">
		.odd{
			background-color:#EDF8FE;
			font-size:12px
		}
		.even{
			background-color:#D5EFFD;
			font-size:12px
		}
	</style>
	
	<script language=javascript>
		function searchData()
		{
			$('#panel').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด..."/>');
			$("#panel").load('frm_select_old_receipt.php?receiptTypeID='+$("#receiptType").val()+'&receiptTypeName='+$("#receiptType option:selected").text());
		}
	</script>
</head>
<body>

<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td>       
		<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
		<div style="clear:both;"></div>

		<fieldset><legend><B>ตัดใบเสร็จค่าใช้จ่ายเก่า</B></legend>
		<div align="center" style="padding:20px">
			<div class="ui-widget">
				<table width="100%" border="0" cellSpacing="1" cellPadding="3">
					<tr style="font-weight:bold;" valign="top"align="center" >
						<td align="right" width="50%">
							<select id="receiptType">
								<?php
								$qry_TypePay = pg_query("
															SELECT
																\"TypeID\",
																\"TName\"
															FROM
																\"TypePay\"
															WHERE
																\"TypeID\" IN(
																	'101', -- ค่าภาษีรถยนต์
																	'105', -- ตรวจมิเตอร์
																	'106', -- ปรับมิเตอร์
																	'107', -- แจ้งแก๊ส
																	'112', -- ค่าป้ายมิเตอร์(สูญหาย)
																	'120', -- ค่าคัดป้ายเหล็ก
																	'121', -- ค่าคัดป้ายภาษี
																	'122', -- ค่าคัดป้ายมิเตอร์
																	'123', -- ค่าเปลี่ยนเครื่อง
																	'140', -- ปรับภาษี
																	'186', -- รับแทนต่อภาษีวงกลม
																	'187', -- รับแทนตรวจมิเตอร์
																	'188', -- รับแทนปรับมิเตอร์
																	'189', -- รับแทนปรับภาษี
																	'190', -- รับแทนเปลี่ยนเครื่อง
																	'195', -- แจ้งใช้แก๊ส LPG
																	'196', -- แจ้งใช้แก๊ส NGV
																	'322', -- รับแทนค่าคัดป้ายมิเตอร์
																	'323', -- รับแทนค่าคัดป้ายภาษี
																	'324', -- รับแทนค่าคัดป้ายเหล็ก
																	'325', -- รับแทนค่าคัดป้ายทะเบียนย่อ
																	'338', -- รับแทนค่าแจ้งใช้ NGV
																	'354', -- รับแทนปรับแจ้งก๊าสช้า
																	'363' -- ค่าคัดป้ายเหล็กชำรุด
																)
															ORDER BY
																\"TName\"
														");
								while($res_TypePay = pg_fetch_array($qry_TypePay))
								{
									$TypeID = $res_TypePay["TypeID"]; // รหัสค่าใช้จ่าย
									$TName = $res_TypePay["TName"]; // ชื่อค่าใช้จ่าย
									
									echo "<option value=\"$TypeID\">$TName</option>";
								}
								?>
							</select>
						</td>
						<td align="left" width="50%">
							<input type="button" value="ค้นหา" style="cursor:pointer;" onClick="searchData();"/>
						</td>
					</tr>
				</table>
			</div>
		</div>
		</fieldset>
		<div id="panel" style="padding-top: 10px;"></div>
    </td>
</tr>
</table>

</body>
</html>