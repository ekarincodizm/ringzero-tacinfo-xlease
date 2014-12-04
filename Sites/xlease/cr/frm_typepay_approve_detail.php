<?php
session_start();
include("../config/config.php");

$autoID = pg_escape_string($_GET['id']);

$id_user = $_SESSION["av_iduser"]; // รับ id ผู้ใช้
$logs_any_time = nowDateTime(); // วันที่ปัจจุบัน
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติ TypePay</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

	<?php
	// หาข้อมูลใหม่
	$qry_TypePay_new = pg_query("
									SELECT
										\"TypeID\",
										\"TName\",
										\"UseVat\",
										\"TypeRec\",
										\"TypeDep\",
										\"ActionRequest\",
										\"fullname\",
										\"doerStamp\",
										\"doerNote\"
									FROM
										\"TypePay_Request\"
									LEFT JOIN
										\"Vfuser\" ON \"id_user\" = \"doerID\"
									WHERE
										\"autoID\" = '$autoID'
								");
	$TypeID_new = pg_fetch_result($qry_TypePay_new,0);
	$TName_new = pg_fetch_result($qry_TypePay_new,1);
	$UseVat_new = pg_fetch_result($qry_TypePay_new,2);
	$TypeRec_new = pg_fetch_result($qry_TypePay_new,3);
	$TypeDep_new = pg_fetch_result($qry_TypePay_new,4);
	$ActionRequest = pg_fetch_result($qry_TypePay_new,5);
	$fullname = pg_fetch_result($qry_TypePay_new,6);
	$doerStamp = pg_fetch_result($qry_TypePay_new,7);
	$doerNote = pg_fetch_result($qry_TypePay_new,8);
	
	// หาข้อมูลเก่า
	$qry_TypePay_old = pg_query("
									SELECT
										\"TypeID\",
										\"TName\",
										\"UseVat\",
										\"TypeRec\",
										\"TypeDep\"
									FROM
										\"TypePay\"
									WHERE
										\"TypeID\" = '$TypeID_new'
								");
	$TypeID_old = pg_fetch_result($qry_TypePay_old,0);
	$TName_old = pg_fetch_result($qry_TypePay_old,1);
	$UseVat_old = pg_fetch_result($qry_TypePay_old,2);
	$TypeRec_old = pg_fetch_result($qry_TypePay_old,3);
	$TypeDep_old = pg_fetch_result($qry_TypePay_old,4);
	
	if($ActionRequest == "I")
	{
		$ActionRequestText = "ขอเพิ่มข้อมูล";
	}
	elseif($ActionRequest == "U")
	{
		$ActionRequestText = "ขอแก้ไขข้อมูล";
	}
	?>
	<form method="post" action="process_typepay_approve.php">
		<table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<tr>
				<td>
					<div class="header"><h1>อนุมัติ TypePay</h1></div>
					<div class="wrapper">
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
							<tr>
								<td width="50%">
									<fieldset><legend><B>ข้อมูลเก่า</B></legend>
										<table>
											<tr>
												<td align="right">TypeID : </td>
												<td align="left"><?php echo $TypeID_old; ?></td>
											</tr>
											<tr>
												<td align="right">TName : </td>
												<td align="left"><?php echo $TName_old; ?></td>
											</tr>
											<tr>
												<td align="right">UseVat : </td>
												<td align="left"><?php echo $UseVat_old; ?></td>
											</tr>
											<tr>
												<td align="right">ประเภทใบเสร็จ : </td>
												<td align="left"><?php echo $TypeRec_old; ?></td>
											</tr>
											<tr>
												<td align="right">ฝ่ายที่แสดง : </td>
												<td align="left"><?php echo $TypeDep_old; ?></td>
											</tr>
										</table>
									</fieldset>
								</td>
								<td width="50%">
									<fieldset><legend><B>ข้อมูลใหม่</B></legend>
										<table>
											<tr>
												<td align="right">TypeID : </td>
												<td align="left"><?php echo $TypeID_new; ?></td>
											</tr>
											<tr>
												<td align="right">TName : </td>
												<td align="left"><?php echo $TName_new; ?></td>
											</tr>
											<tr>
												<td align="right">UseVat : </td>
												<td align="left"><?php echo $UseVat_new; ?></td>
											</tr>
											<tr>
												<td align="right">ประเภทใบเสร็จ : </td>
												<td align="left"><?php echo $TypeRec_new; ?></td>
											</tr>
											<tr>
												<td align="right">ฝ่ายที่แสดง : </td>
												<td align="left"><?php echo $TypeDep_new; ?></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<table>
										<tr>
											<td align="right">ประเภทการทำรายการ : </td>
											<td align="left"><?php echo $ActionRequestText; ?></td>
										</tr>
										<tr>
											<td align="right">ผู้ทำรายการ : </td>
											<td align="left"><?php echo $fullname; ?></td>
										</tr>
										<tr>
											<td align="right">วันเวลาที่ทำรายการ : </td>
											<td align="left"><?php echo $doerStamp; ?></td>
										</tr>
										<tr>
											<td align="right">หมายเหตุการอนุมัติ : </td>
											<td align="left"><textarea name="appvNote" cols="50"></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="hidden" name="autoID" value="<?php echo $autoID; ?>" />
									<input type="submit" name="btn_appv" id="btn_appv" value="อนุมัติ" style="cursor:pointer;" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="submit" name="btn_unappv" id="btn_unappv" value="ไม่อนุมัติ" style="cursor:pointer;" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" value="ปิด" style="cursor:pointer;" onClick="window.close();" />
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</form>

</body>
</html>