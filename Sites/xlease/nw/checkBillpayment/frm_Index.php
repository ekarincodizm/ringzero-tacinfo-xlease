<?php
include("../../config/config.php");
$datepicker = $_POST['datepicker'];
if($datepicker==""){
	$datepicker=nowDate();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});
</script>
    
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

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>ตรวจสอบเงินโอน-Billpayment</B></legend>
				<div align="center">
					<div class="ui-widget">
						<form method="post" action="frm_Index.php">
						<p align="center">
							<label for="birds"><b>วันที่</b></label>
							<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15">
							<input type="submit" value="เริ่มค้น"/>
						</p>
						</form>
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