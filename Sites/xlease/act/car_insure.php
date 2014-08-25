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
var myarray;
var mystring;

$(document).ready(function(){
    $("#idno").autocomplete({
        source: "car_insure_list.php",
        minLength:1
    });

    $('#btn1').click(function(){
        mystring = $('#idno').val();
        myarray = mystring.split("|");
        $("#panel").empty();
        $("#panel").load("car_insure_panel.php?id="+ myarray[0]);
    });
});
</script>

    
</head>
<body id="mm">

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">
<input type="button" value="ประกันภัยภาคบังคับ (พรบ.)" class="ui-button" onclick="javascript:location='car_insure.php';" disabled><input type="button" value="ประกันภัยภาคสมัครใจ" class="ui-button" onclick="javascript:location='car_insure_un.php';">
</div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ประวัติซื้อประกันของรถ - ประกันภัยภาคบังคับ (พรบ.)</B></legend>
<div class="ui-widget" align="center">

<div><b>ทะเบียน/เลขตัวถัง/เลขที่สัญญา/ชื่อสกุล</b> <input id="idno" name="idno" size="80" /> <input type="button" id="btn1" value="ค้นหา"/></div>

<div id="panel"></div>

</div>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>