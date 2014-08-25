<?php
include("../../config/config.php");

$createID = pg_escape_string($_GET['createID']);

// หาเลขที่สัญญา และหมายเหตุ
$qry_contract = pg_query("select \"contractID\", \"doerNote\" from \"thcap_create_seize_asset\" where \"createID\" = '$createID' ");
$contractID = pg_fetch_result($qry_contract,0);
$doerNote = pg_fetch_result($qry_contract,1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) Approve Create งานยึด</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
	function popU(U,N,T) {
		newWindow = window.open(U, N, T);
	}
	
	function chkData()
	{
		if(document.getElementById("RemarkAppv").value == '')
		{
			alert('กรุณาระบุ หมายเหตุ การอนุมัติ');
			return false;
		}
		else
		{
			return true;
		}
	}
</script>

<style type="text/css">
#warppage
{
width:800px;
margin-left:auto;
margin-right:auto;

min-height: 5em;
background: rgb(255, 255, 255);
padding: 5px;
border: rgb(128, 128, 128) solid 0.5px;
border-radius: .625em;
-moz-border-radius: .625em;
-webkit-border-radius: .625em;
}
/*
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
*/
</style>
</head>
<body>

<center>
	<h2>(THCAP) Approve Create งานยึด</h2>
	<a href="#" onclick="javascript:popU('../thcap_installments/frm_Index.php?idno=<?php echo "$contractID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')"><?php echo "<b><FONT COLOR=#0000FF><u>$contractID</u></FONT></b>"; ?></a>

	<form method="post" name='myfrm' action="process_appv_create_seize_asset.php">
		<hr width="80%" color="#CCCCCC"><br>
		<div style="margin-top:-20px;"></div>
		
		
		<?php include("../thcap/Data_contract_detail.php"); ?>
		<?php include("../thcap_installments/show_group_product.php"); ?>
		
		<div style="margin-bottom:15px;"></div>

		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFECEC">
			<tr>
				<td width="50%" align="right"><b>หมายเหตุ Create งานยึด : </b></td>
				<td width="50%" align="left"><textarea name="txtRemark" id="txtRemark" cols="35" rows="3" style="background-color:#CCCCCC;" readOnly><?php echo $doerNote; ?></textarea></td>
			</tr>
			<tr>
				<td width="50%" align="right"><b>หมายเหตุ การอนุมัติ : </b></td>
				<td width="50%" align="left"><textarea name="RemarkAppv" id="RemarkAppv" cols="35" rows="3"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="appv" type="submit" value="อนุมัติ" onClick="return chkData();" style="cursor:pointer;">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="unAppv" type="submit" value="ไม่อนุมัติ" onClick="return chkData();" style="cursor:pointer;">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" value="ออก" onClick="window.close();" style="cursor:pointer;" />
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="createID" value="<?php echo $createID; ?>">
	</form>
</center>

</body>
</html>