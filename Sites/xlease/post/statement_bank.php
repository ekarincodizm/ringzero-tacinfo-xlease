<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#btn00').click(function(){
        $("#btn00").attr('disabled', true);
        $("#panel").text('กำลังค้นหาข้อมูล ....');
        $("#panel").load("panel-statement-bank.php?datepicker="+ $("#datepicker").val() );
        $("#btn00").attr('disabled', false);
    });
    
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
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

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

<fieldset><legend><B>Statement Bank ด้านรับ</B></legend>

<div align="center">

<div class="ui-widget">
<p align="center">
<label for="birds"><b>วันที่</b></label>
<input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15">&nbsp;&nbsp;
<input type="button" id="btn00" value="เริ่มค้น"/></p>
<div id="panel"></div>
</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>