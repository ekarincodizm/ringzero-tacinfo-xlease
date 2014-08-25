<?php 
include("../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$datepicker = nowDate();//ดึง วันที่จาก server
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    

<script type="text/javascript">
$(document).ready(function(){
    $('#btnsubmit').click(function(){
        $("#showpanel").empty();
        $("#showpanel").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#showpanel").load("frm_year_2009_panel.php?sort=custyear&datepicker="+ $("#datepicker").val());
    });

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
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div>
 
<div style="float:left">
<input name="button" type="button" onclick="window.location='frm_year_2009.php'" value="รับรู้ปี 2009 soy" disabled>
<input name="button" type="button" onclick="window.location='frm_run_2009.php'" value="Run รับรู้ปี 2009 soy">
</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>
 
<fieldset><legend><b>รับรู้ปี 2009 soy</b></legend>

<div style="margin:5px">
<b>วันที่รับรู้รายได้</b> <input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15"><input type="button" name="btnsubmit" id="btnsubmit" value="ค้นหา">
</div>

<div style="clear:both;"></div>

<div id="showpanel" style="margin:5px"></div>

</fieldset>

</div>

		</td>
	</tr>
</table>

</body>
</html>