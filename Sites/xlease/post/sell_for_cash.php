<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#birds").autocomplete({
        source: "s_idno.php",
        minLength:2
    });

    $('#btn00').click(function(){
        $("#panel").load("panel-user.php?idno="+ $("#birds").val());
    });
    
    $('#birds').keyup(function(){
        $("#panel").load("panel-user.php?idno="+ $("#birds").val());
    });
    
    $('#btn1').click(function(){
        if($("#birds").val() != '')
            top.location.href="sell_for_cash_add.php?idno="+ $("#birds").val();
    });

});




</script>
    
</head>
<body id="mm">

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

<fieldset><legend><B>ขายสด รถยึด</B></legend>

<div align="center">

<div class="ui-widget">
<p><label for="birds"><b>ค้นหา เลขที่สัญญา</b></label>
<input id="birds" name="birds" size="60" /><input type="button" id="btn00" value="ค้นหา"/></p>
<div id="panel"></div>
<div><br /><input type="button" id="btn1" class="ui-button" value="ยืนยันการขายให้คนใหม่"/></div>
</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>