<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["contractID"]); // เลขที่สัญญา

$doerID = "000";
$doerRemark = "ขอปิดสัญญาอัตโนมัติ";

$qry_doerName = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
$doerName = pg_fetch_result($qry_doerName,0);

// หาวันที่ปิดสัญญา
$qry_contractcloseddate = pg_query("select \"thcap_checkcontractcloseddate\"('$contractID')");
$contractcloseddate = pg_fetch_result($qry_contractcloseddate,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<title>ยืนยันปิดสัญญา</title>
	<script language=javascript>
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
		
		function validate()
		{
			if(document.getElementById("appvRemark").value == '')
			{
				alert('กรณุระบุ หมายเหตุการอนุมัติปิดสัญญา');
				return false;
			}
			else
			{
				return true;
			}
		}
	</script>
</head>
<body>
	<center>
		<form method="post" action="process_approve.php">
			<div  align="center"><h2>ยืนยันปิดสัญญา</h2></div>
			<div id="panel" style="padding-top: 10px;">
				<table>
					<tr>
						<td align="right"><b>เลขที่สัญญา :</b></td>
						<td align="left"><font color="#0000FF" style="cursor:pointer;" onClick="popU('../thcap_installments/frm_Index.php?idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=650')"><u><?php echo $contractID; ?></u></font></td>
					</tr>
					<tr>
						<td align="right"><b>วันที่ปิดสัญญา :</b></td>
						<td align="left"><?php echo $contractcloseddate; ?></td>
					</tr>
					<tr>
						<td align="right"><b>ผู้ขอปิดสัญญา :</b></td>
						<td align="left"><?php echo $doerName; ?></td>
					</tr>
					<tr>
						<td align="right"><b>หมายเหตุการขอปิดสัญญา :</b></td>
						<td align="left"><?php echo $doerRemark; ?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>หมายเหตุการอนุมัติปิดสัญญา :</b></td>
						<td align="left"><textarea name="appvRemark" id="appvRemark"></textarea></td>
					</tr>
					<tr>
						<td align="center" colspan="2">
							<br/>
							<input type="hidden" name="contractID" value="<?php echo $contractID; ?>" />
							<input type="hidden" name="doerID" value="<?php echo $doerID; ?>" />
							<input type="hidden" name="doerRemark" value="<?php echo $doerRemark; ?>" />
							<input type="hidden" name="contractcloseddate" value="<?php echo $contractcloseddate; ?>" />
							<input type="submit" style="cursor:pointer;" value="ยืนยันปิดสัญญา" onClick="return validate();" />
							<input type="button" style="cursor:pointer;" value="CLOSE" onClick="window.close();" />
						</td>
					</tr>
				</table>
			</div>
		</form>
	</center>
</body>
</html>