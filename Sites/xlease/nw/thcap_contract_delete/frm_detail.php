<?php
include("../../config/config.php");
$contractID = $_GET["conid"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">   
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
<script type="text/javascript">
</script> 
</head>
<body>
<form name="frm" action="process.php" method="post">
	<input type="hidden" name="conid" value="<?php echo $contractID; ?>">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td colspan="2">
				<?php require_once("../thcap/Data_contract_detail.php"); ?>
			</td>
		</tr>
		<tr>
			<td><div style="padding-top:10px;"></div></td>
		</tr>
		<tr align="center">
			<td>
				<table width="60%" border="0" cellspacing="2" cellpadding="2" align="center">
					<tr align="left">
						<td>
							<b>เหตุผลที่ต้องการลบ</b>
						</td>
					</tr>
					<tr align="center">
						<td>
							<textarea name="note" cols="100%" rows="5"></textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr align="center">
			<td>
				<table width="60%" border="0" cellspacing="2" cellpadding="2" align="center">
					<tr align="center">
						<td>
							<input type="submit" value=" ลบสัญญานี้ " onclick="if(confirm('ยืนยันการลบเลขที่สัญญา')==true){ return true;}else{ return false;}" style="width:100px;height:35px;">
						</td>
						
					</tr>
				</table>
			</td>
		</tr>			
	</table>
</form>	
</body>
</html>