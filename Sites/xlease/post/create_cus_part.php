<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#idno").autocomplete({
        source: "create_cus_part_autocomplete.php"
    });

    $('#btn1').click(function(){
        if($("#idno").val() != '')
            $("#panel").load("create_cus_part_panel.php?idno="+ $("#idno").val());
    });
    
});
</script>
    
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<div style="float:left">
<input type="button" name="btncreate" id="btncreate" value="สร้างใหม่" class="ui-button" onclick="window.location='create_cus_part.php'" disabled>
<input type="button" name="btnchange" id="btnchange" value="เปลี่ยนชื่อเข้าร่วม" class="ui-button" onclick="window.location='create_cus_part_change.php'">
</div>
<div style="float:right"><input type="button" value="Close" class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>สร้างลูกค้าเข้าร่วม</B></legend>

<div class="ui-widget" align="center">
<b>ค้นหา เลขที่สัญญา</b>
<input id="idno" name="idno" size="50" />
<input type="button" id="btn1" value="ค้นหา"/>
</div>

<div id="panel"></div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>