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
    $("#tb_no").focus();
    
    $("#tb_no").autocomplete({
        source: "edit_cheque_autocomplete.php",
        minLength:1
    });
    
    $('#btn_search').click(function(){
        $("#div_show").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        var s = $("#tb_no").val();
        var brokenstring=s.split("#");
        $("#div_show").load("edit_cheque_panel.php?ChequeNo="+ brokenstring[0] +"&PostID="+ brokenstring[1]);
    });
    
});
</script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Edit Cheque</B></legend>

<div class="ui-widget">

<div style="padding: 5px 0 10px 0">
<b>ระบุเลขที่เช็ค</b> : <input name="tb_no" id="tb_no" type="text" size="80">&nbsp;<input id="btn_search" type="button" value="ค้นหา">
</div>

<div id="div_show"></div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>