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
    $("#tbsearch").autocomplete({
        source: "zcar-list.php"
    });

    $('#btnshow').click(function(){
        $("#btnshow").attr('disabled', true);
        $('#panel').empty();
        $("#panel").text('กำลังค้นหาข้อมูล ....');
        mystring = $('#tbsearch').val();
        myarray = mystring.split("|");
        var cregis = encodeURIComponent ( myarray[0] );
        $("#panel").load("zcar-panel.php?regis="+ cregis);
        $("#btnshow").attr('disabled', false);
    });
});
</script>
    
</head>
<body>

<?php include("menu.php"); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<!--
<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>
-->
<fieldset><legend><B>ใส่ข้อมูลการชำระเงิน</B></legend>

<div class="ui-widget" align="center">
<b>เลขทะเบียน</b>
<input type="text" id="tbsearch" name="tbsearch" size="50">
<input type="button" id="btnshow" value="เริ่มค้น"/>

<div id="panel"></div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>