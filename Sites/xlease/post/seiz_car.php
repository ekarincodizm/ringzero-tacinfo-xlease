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
    $("#idno").autocomplete({
        source: "s_idno_regis.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").empty();
        $("#panel").load("seiz_car_panel.php?idno="+ $("#idno").val());
    });
});
</script>

    
</head>
<body id="mm">

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">
<input type="button" value="ยึดรถเข้าบริษัท" class="ui-button" onclick="javascript:location='seiz_car.php';" disabled><input type="button" value="คืนรถยึดให้ลูกค้า" class="ui-button" onclick="javascript:location='seiz_car_return.php';">
</div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>บันทึกการยึดรถ - ยึดรถเข้าบริษัท</B></legend>
<div class="ui-widget" align="center">

<div><b>เลขที่สัญญา/ทะเบียน</b> <input id="idno" name="idno" size="50" /> <input type="button" id="btn1" value="ค้นหา"/></div>

<div id="panel"></div>

</div>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>