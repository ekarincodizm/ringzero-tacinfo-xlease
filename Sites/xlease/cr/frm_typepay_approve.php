<?php
session_start();
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
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

<table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td>
			<div class="header"><h1>อนุมัติ TypePay</h1></div>
			<div class="wrapper">
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr bgcolor="#FFFFFF">
						<td colspan="11" align="left" style="font-weight:bold;">TypePay ที่รออนุมัติ</td>
					</tr>
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
						<td>รูปแบบ</td>
						<td>TypeID</td>
						<td>TName</td>
						<td>ผู้ทำรายการ</td>
						<td>วันเวลาทำรายการ</td>
						<td>รายละเอียด</td>
					</tr>
					<?php
					$qry_TypePay = pg_query("
												SELECT
													\"autoID\",
													\"TypeID\",
													\"TName\",
													\"ActionRequest\",
													\"fullname\",
													\"doerStamp\"
												FROM
													\"TypePay_Request\"
												LEFT JOIN
													\"Vfuser\" ON \"id_user\" = \"doerID\"
												WHERE
													\"appvStatus\" = '9'
												ORDER BY
													\"doerStamp\"
											");
					$i = 0;
					while($res_TypePay = pg_fetch_array($qry_TypePay))
					{
						$i++;
						$autoID = $res_TypePay["autoID"];
						$TypeID = $res_TypePay["TypeID"];
						$TName = $res_TypePay["TName"];
						$ActionRequest = $res_TypePay["ActionRequest"];
						$fullname = $res_TypePay["fullname"];
						$doerStamp = $res_TypePay["doerStamp"];
						
						if($ActionRequest == "I")
						{
							$txttype="ขอเพิ่มข้อมูล";
						}
						elseif($ActionRequest == "U")
						{
							$txttype="ขอแก้ไขข้อมูล";
						}
						else
						{
							$txttype="";
						}
						
						if($i%2==0){
							echo "<tr class=\"odd\" align=center>";
						}else{
							echo "<tr class=\"even\" align=center>";
						}
					?>
						<td align="center"><?php echo $txttype; ?></td>
						<td align="center"><?php echo $TypeID; ?></td>
						<td align="left"><?php echo $TName; ?></td>
						<td align="left"><?php echo $fullname; ?></td>
						<td align="center"><?php echo $doerStamp; ?></td>
						<td align="center"><span onclick="javascript:popU('frm_typepay_approve_detail.php?id=<?php echo $autoID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')" style="cursor: pointer;"><img src="full_page.png" height="20" width="20" border="0"></span></td>
					</tr>
					<?php
					} //end while
					if($i == 0){
						echo "<tr><td colspan=6 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
					}
					?>
				</table>
			</div>
		</td>
	</tr>
</table>

</body>
</html>