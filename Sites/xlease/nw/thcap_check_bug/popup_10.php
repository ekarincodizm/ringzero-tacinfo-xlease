<?php
include("../../config/config.php");
$contractID = $_GET["conID"]; // เลขที่สัญญา

// หา ยอดจัด/ยอดลงทุน (ก่อนภาษี)
$qry_conFinAmtExtVat = pg_query("select \"conFinAmtExtVat\" from thcap_contract where \"contractID\" = '$contractID' ");
$conFinAmtExtVat = pg_result($qry_conFinAmtExtVat,0);

// หา ยอดค่าซาก
$qry_conResidualValue = pg_query("select \"conResidualValue\" from thcap_mg_contract_current where \"contractID\" = '$contractID' ");
$conResidualValue = pg_result($qry_conResidualValue,0);
if($conResidualValue == ""){$conResidualValue = "0.00";}
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
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.frm1.contractID.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ เลขที่สัญญา";
	}
	
	if (document.frm1.conFinAmtExtVat.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ ยอดจัด หรือเงินลงทุน (ก่อนภาษีมูลค่าเพิ่ม)";
	}
	
	if (document.frm1.conResidualValue1.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ ยอดเงินต้นคงเหลือในงวดสุดท้ายที่ต้องการ";
	}
	
	if (document.frm1.conResidualValue2.value==""){
		theMessage = theMessage + "\n -->  กรุณาระบุ ยอดค่าซาก";
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
	<h1>Generate EIR</h1>
	
	<form name="frm1" method="post" action="process_10.php">
		<table>
			<tr>
				<td align="right">เลขที่สัญญา : </td>
				<td align="left"><input type="textbox" name="contractID" size="25" value="<?php echo $contractID; ?>"><font color="#FF0000"> *</font></td>
			</tr>
			<tr>
				<td align="right">ยอดจัด หรือเงินลงทุน (ก่อนภาษีมูลค่าเพิ่ม) : </td>
				<td align="left"><input type="textbox" name="conFinAmtExtVat" size="25" value="<?php echo $conFinAmtExtVat; ?>"><font color="#FF0000"> *</font></td>
			</tr>
			<tr>
				<td align="right">ยอดเงินต้นคงเหลือในงวดสุดท้ายที่ต้องการ : </td>
				<td align="left"><input type="textbox" name="conResidualValue1" size="25" value="<?php echo $conResidualValue; ?>"><font color="#FF0000"> *</font></td>
			</tr>
			<tr>
				<td align="right">ยอดค่าซาก : </td>
				<td align="left"><input type="textbox" name="conResidualValue2" size="25" value="<?php echo $conResidualValue; ?>"><font color="#FF0000"> *</font></td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<br>
					<input type="submit" value="Generate EIR" onClick="return validate();">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" value="CLOSE" onClick="window.close();">
				</td>
			</tr>
		</table>
	</form>
</center>

</body>
</html>