<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#divshow').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $('#divshow').load('fvoucher_receipt_list_show.php');
});

function add_rc(id,bt){
    $('body').append('<div id="dialogedit"></div>');
    $('#dialogedit').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $('#dialogedit').load('fvoucher_receipt_add.php?id='+id+'&bt='+bt);
    $('#dialogedit').dialog({
        title: 'บันทึกบัญชี VC ID : '+id,
        resizable: false,
        modal: true,  
        width: 700,
        height: 250,
        close: function(ev, ui){
            $('#dialogedit').remove();
        }
    });
}
</script>

<style type="text/css">
.ui-widget{
    font-family:tahoma;
    font-size:13px;
}
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
.BoxYellow {
    margin: 0 auto;
    padding:5px 5px 5px 5px;
    font-size: 12px;
    font-weight: bold;
    color: #666666;
    text-align: center;
    line-height: 20px;
    BORDER-RIGHT: #FCC403 1px solid; BORDER-TOP: #FCC403 1px solid; BORDER-LEFT: #FCC403 1px solid; WIDTH: 500px; BORDER-BOTTOM: #FCC403 1px solid; HEIGHT: auto; BACKGROUND-COLOR: #FFFFD5
}
.odd{
    background-color:#FFFFFF;
    font-size:12px
}
.even{
    background-color:#DFEFFF;
    font-size:12px
}
.btline{
    font-weight: bold;
    border-style: dashed; border-width: 1px; border-color:#000000
}
</style>

</head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<div class="ui-widget">

<fieldset><legend><B>Voucher - ผู้รับเงินตาม Voucher</B></legend>

<div id="divshow"></div>

</fieldset>

</div>

        </td>
    </tr>
</table>

</body>
</html>