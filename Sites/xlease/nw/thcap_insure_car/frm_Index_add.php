<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) เพิ่มประกันภัย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#contractID").autocomplete({
				source: "s_contractID_car.php",
				minLength:1,
				delay:1000
			});
		});
		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
		
		function searchContract()
		{
			$('#showContract').html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
			$("#showContract").load("frm_contractDetail.php?contractID="+$("#contractID").val());
		}
	</script>
 
</head>
<body>
	<div style="text-align:center;"><h2>(THCAP) เพิ่มประกันภัย</h2></div>
	<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td align="center">
				<fieldset><legend><B>ค้นหาเลขที่สัญญา</B></legend>
					เลขที่สัญญา-เลขตัวถัง-ชื่อลูกค้า-ทะเบียนรถ :
					<input type="text" name="contractID" id="contractID" value="<?php echo $contractID; ?>" size="70"> &nbsp
					<input type="button" id="btnsearch" value="ค้นหา" style="cursor:pointer;" onClick="searchContract();" />
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<div id="showContract"></div>
			</td>
		</tr>
	</table>
</body>
</html>