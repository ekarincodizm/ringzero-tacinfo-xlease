<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติเพิ่มเงินเข้าระบบเข้าร่วม</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script src="../<?php echo $lo_ext_current_temp ?>scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="../<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function show_p(id,f_d){
var h ;
if(f_d=='')h=355;else h=270;
	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('add_money_app_popup.php?id='+id+'&f_d='+f_d);
    $('#dialog').dialog({
        title: 'รายละเอียด ',
        resizable: true,
        modal: true,  
        width: 650,
        height: h,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
</script>

</head>
<body>
    <style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>อนุมัติเพิ่มเงินเข้าระบบเข้าร่วม</h1></div>
		<div class="wrapper">
			<div>
			<?php 
				//รายการรออนุมัติ
				include "join_add_money_approve_wait.php"; 
			?>
			</div>
            <div>
				<?php 
				$status="1";
				include "join_add_money_approve_history.php"; 
				?>
			</div>
		</div>
	</td>
</tr>
</table>

</body>
</html>