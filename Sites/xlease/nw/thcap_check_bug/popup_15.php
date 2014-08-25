<?php
include("../../config/config.php");

$abh_autoid = pg_escape_string($_GET["abh_autoid"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Generate EIR</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
	$(document).ready(function(){
		var abh_autoid = '<?php echo $abh_autoid; ?>';
		$("#panel").load("../accountEdit/frm_account_show.php?abh_autoid="+ abh_autoid );
	});

	function popU(U,N,T) {
		newWindow = window.open(U, N, T);
	}

	function validate() 
	{
		var theMessage = "Please complete the following: \n-----------------------------------\n";
		var noErrors = theMessage
		
		if (document.frm1.remark.value==""){
			theMessage = theMessage + "\n -->  กรุณาระบุ เหตุผลในการยกเลิกการตรวจสอบรายการ";
		}

		// If no errors, submit the form
		if (theMessage == noErrors) {
			return true;
		} 
		else
		{
			// If errors were found, show alert message
			alert(theMessage);
			return false;
		}
	}
</script>
</head>
<body>

<center>
	<h1>ยกเลิกการตรวจสอบรายการ</h1>
	
	<div id="panel"></div>
	
	<form name="frm1" method="post" action="process_15.php">
		<input type="hidden" name="abh_autoid" value="<?php echo $abh_autoid; ?>">
		<table>
			<tr>
				<td align="right"><b>เหตุผลในการยกเลิกการตรวจสอบรายการ : </b></td>
				<td align="left"><textarea col="50" rows="3" name="remark" id="remark"></textarea><font color="red"><b> *</b></font></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><br><input type="submit" value="บันทึก" onClick="return validate();" ></td>
			</tr>
		</table>
	</form>
</center>

</body>
</html>