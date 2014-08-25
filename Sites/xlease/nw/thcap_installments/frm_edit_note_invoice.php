<?php
include("../../config/config.php");

$contractID = $_GET["contractID"];
$noteDetail = $_GET["noteDetail"];

if($contractID == ""){$contractID = $_POST["contractID"];}
if($noteDetail == ""){$noteDetail = $_POST["noteDetail"];}

if($noteDetail == "")
{
	$qry_note_invoice = pg_query("select \"noteDetail\" from \"thcap_contract_note\" where \"contractID\" = '$contractID' and \"noteType\" = '1'
									and \"noteID\" = (select max(\"noteID\") from \"thcap_contract_note\" where \"contractID\" = '$contractID' and \"Approved\" = 'TRUE' ) ");
	$noteDetail = pg_fetch_result($qry_note_invoice,0);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขหมายเหตุการวางบิล/ใบแจ้งหนี้</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.frm1.noteDatail.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ หมายเหตุการวางบิล/ใบแจ้งหนี้";
	}
	
	// If no errors, submit the form
	if (theMessage == noErrors) {
		if(confirm('ยืนยันการแก้ไขหมายเหตุ'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<form name="frm1" method="post" action="process_edit_note_invoice.php">
<center><h2>แก้ไขหมายเหตุการวางบิล/ใบแจ้งหนี้</h2></center>
<center>
<table>
	<tr>
		<td><b>หมายเหตุการวางบิล/ใบแจ้งหนี้ :</b></td>
		<td><textarea name="noteDatail" id="noteDatail" rows="3" cols="70"><?php echo $noteDetail; ?></textarea></td>
	</tr>
</table>
<br><br>
<input type="hidden" name="contractID" id="contractID" value="<?php echo $contractID; ?>">
<input type="submit" name="add" value=" บันทึก " onclick="return validate();"> &nbsp;&nbsp;&nbsp; <input type="button" value="ยกเลิก/ปิด" onclick="javascript:window.close();">
</center>
</form>
</body>
</html>