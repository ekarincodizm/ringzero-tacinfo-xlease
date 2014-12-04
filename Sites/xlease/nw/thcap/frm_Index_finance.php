<?php
session_start();
include("../../config/config.php");

//user ที่ทำรายการ
$user_id = $_SESSION["av_iduser"];
$app=2; //กรณีเป็นการเงินอนุมัติ

//หา emplevel ของพนักงาน
$qrylevel="SELECT ta_get_user_emplevel('$user_id')";
if($reslevel=pg_query($qrylevel)){
	list($emplevel)=pg_fetch_array($reslevel);
}

// กำหนดชื่อเมนู
if($action_menu == "see"){$titleMenu = "(THCAP) ดูรายการเงินโอน(การเงิน)";}
else{$titleMenu = "(THCAP) ยืนยันรายการเงินโอน(การเงิน)";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $titleMenu; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>
    
</head>
<body id="mm">

<table width="980" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B><?php echo $titleMenu; ?></B></legend>
				<div align="center">
					<div class="ui-widget">
						<div id="panel">
							<?php
								include  "frm_show.php";
							?>
						</div>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>

</body>
</html>