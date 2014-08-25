<?php
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"]; // id ของ user ที่กำลังใช้งานอยู่ในขณะนั้น

$ConID = pg_escape_string($_GET['ConID']);
$criteria = pg_escape_string($_GET['criteria']); // Criteria ที่ใช้ค้นหาข้อมูล

if(empty($ConID)){
	$ConID = pg_escape_string($_POST['ConID']);
	$criteria = pg_escape_string($_POST['criteria']); // Criteria ที่ใช้ค้นหาข้อมูล
}

// กำหนดค่าตัวเลือกที่ใช้บ่อย
$fav_column = "TCS_Search";
if($criteria == "Default")
{
	SetFavoriteToTable($fav_column,$id_user,1);
}
elseif($criteria == "Asset10") 
{
	SetFavoriteToTable($fav_column,$id_user,2);
}
elseif($criteria == "PrimaryCus") 
{
	SetFavoriteToTable($fav_column,$id_user,3);
}

$contractID = $ConID;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) Create งานยึด</title>
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
	<h2>(THCAP) Create งานยึด</h2>
	<a href="#" onclick="javascript:popU('../thcap_installments/frm_Index.php?idno=<?php echo "$ConID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')"><?php echo "<b><FONT COLOR=#0000FF><u>$ConID</u></FONT></b>"; ?></a>

	<form method="post" name='myfrm' action="Process_create_seize_asset.php">
		<hr width="80%" color="#CCCCCC"><br>
		<div style="margin-top:-20px;"></div>
		
		
		<?php include("../thcap/Data_contract_detail.php"); ?>
		<?php include("../thcap_installments/show_group_product.php"); ?>
		
		<div style="margin-bottom:15px;"></div>
					
		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFECEC">
			<tr>
				<td width="50%" align="right"><b>หมายเหตุ : </b></td>
				<td width="50%" align="left"><textarea name="txtRemark" id="txtRemark" cols="35" rows="4"></textarea></td>
			</tr>
			<tr>
				<td width="50%" align="right"><input type="submit" value="ยืนยัน Create งานยึด" id="submitButton">&nbsp;&nbsp;&nbsp;</td>
				<td width="50%" align="left">&nbsp;&nbsp;&nbsp;<input type="button" value="กลับไปหน้าค้นหา" onclick="window.location='frm_Index.php'"></td>
			</tr>
		</table>
		
		<input type="hidden" name="ConID" value="<?php echo $ConID; ?>">
	</form>
</center>

</body>
</html>