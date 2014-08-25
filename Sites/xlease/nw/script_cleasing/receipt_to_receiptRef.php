<?php
session_start();
include('../../config/config.php');

$chk = $_POST['chk'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('number_running').focus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+Cleasing+</h1>
	</div>
		<form action="receipt_to_receiptRef.php" method="post">
	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
		<table width="785" border="1" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#FFFFFF">
			<td align="center" width="780">
				<div style="padding:50px;">
				<input type="hidden" name="chk" value="clean_start">
					<input type="submit" value=" Run script! " style="height:150px;width:250px;" onclick="JavaScript:if(confirm('Confirm ?')==true){return true;}else{ return false;}">
				</div>
			</td>
		</tr>		
	</table>
</form>	
</div>	
<?php
if($chk == 'clean_start'){
?>	

	<table width="850" frame="box" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;" align="center">
		<tr align="center">
		<td  align="center" width="100%">
			<textarea rows="10" style="width:95%;">
<?php
	pg_query("BEGIN");
	$status = 0;
		$sql= pg_query("SELECT a.\"taxinvoiceID\",b.\"receiptID\",b.\"debtID\" FROM \"thcap_temp_taxinvoice_otherpay\" a inner join \"thcap_temp_receipt_otherpay\" b
											ON a.\"debtID\" = b.\"debtID\" ");
			while($result = pg_fetch_array($sql)){
				 $receiptID = $result['receiptID'];
				 $taxinvoiceID = $result['taxinvoiceID'];
					
				$sqlup = "UPDATE thcap_temp_taxinvoice_details SET \"receiptRef\"='$receiptID' WHERE \"taxinvoiceID\"='$taxinvoiceID'";
				$resqlip =pg_query($sqlup);
				if($sqlup){ echo "success : ".$sqlup."\n";}else{$status++; echo "Error : ".$sqlup."<p>";};
			}
?>
			</textarea>
			
<?php			
	if($status==0){
	pg_query("COMMIT");
	echo "<script type='text/javascript'>alert('Success')</script>";	
	}else{
	pg_query("ROLLBACK");
	echo "<script type='text/javascript'>alert('Error')</script>";	
	}
	


?>		
				
			</td>
		</tr>		
<?php } ?>	
		</table>
		
	
</div>
</body>
</html>
