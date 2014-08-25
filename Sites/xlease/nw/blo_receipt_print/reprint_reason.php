<?php
session_start();
include("../../config/config.php");
$id = pg_escape_string($_REQUEST["receiptid"]);
$type = pg_escape_string($_GET["t"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function checkdata(id,type){
	
	if(document.getElementById('reason_rep').value==""){
		alert("กรุณาระบุเหตุผล");
		document.getElementById('reason_rep').focus();
		
	}else{		
		window.open('print_receipt_pdf.php?receiptid='+id+'&type='+type+'&reason='+document.getElementById('reason_rep').value,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800');	
		opener.location.reload(true);
		window.close();
	}
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">


			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr>
				<td align="center"><b>ระบุเหตุผลที่พิมพ์ใบเสร็จ(Reprint)</b></td>
			</tr>
			<tr>
				<td align="center"><textarea name="reason_rep" id="reason_rep" cols="55" rows="4"></textarea></td>
			</tr>
			<tr><td align="center">

				<input type="button" id="b1" value="ตกลง" onclick="checkdata('<?php echo $id;?>','<?php echo $type;?>');">
                <input type="reset" value="ยกเลิก" onclick="document.getElementById('reason_rep').value=''" >
			</td></tr>
			</table>
</td>
</tr>
</table>

</body>
</html>