<?php
session_start();
include("../../config/config.php");

?>
<html>
<head>
<title>รายงานประเมินการทำงาน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
    
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div align="right"><input type="button" value="  Close  " onClick="javascript:window.close();"></div> 
				<fieldset><legend><B>รายงานประเมินการทำงาน</B></legend>
					<div align="center" style="padding:50px 0px 10px 0px;"><input type="button" value="ตรวจสอบการเปิดเมนูรายพนักงาน" style="width: 250px; height:50px" onClick="javascript:popU('report_performance.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=740')"></div>
					<div align="center" style="padding:0px 0px 50px 0px;"><input type="button" value="ตรวจสอบการทำรายการรายพนักงาน" style="width: 250px; height:50px" onClick="javascript:popU('report_action.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=740')"></div>				
				</fieldset>
			</div>
        </td>
    </tr>
	
</table>          

</body>
</html>