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
    
    $(window).bind("beforeunload",function(event){
        window.opener.$('div#div_admin_menu').load('list_admin_menu.php');
    });
    
    $("#panel").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $("#panel").load("approve_edit_panel.php");
});
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>อนุมัติแก้ไขประกัน</B></legend>
<div id="panel"></div>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>