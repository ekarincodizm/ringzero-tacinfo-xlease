<?php
include("../config/config.php");
$now_date = nowDate();//ดึง วันที่จาก server
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

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
</style>

</head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="ui-widget">

<fieldset><legend><B>บันทึกการใช้ Voucher - อนุมัติแล้วแต่ยังไม่จบทั้งหมด</B></legend>

<div style="float:left">
    <input type="button" name="btn1" id="btn1" value="อนุมัติแล้วแต่ยังไม่จบทั้งหมด" disabled>
    <input type="button" name="btn2" id="btn2" value="ค้นหาตามวันที่" onclick="window.location='fvoucher_list_date.php' ">
</div>
<div style="float:right"></div>
<div style="clear:both"></div>

<div id="divShow"></div>

</fieldset>

</div>

        </td>
    </tr>
</table>

<script type="text/javascript">
$(document).ready(function(){
    $('#divShow').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
    $('#divShow').load('fvoucher_list_panel.php?type=1');
});

function editfill(id){
    $('body').append('<div id="dialogedit"></div>');
    $('#dialogedit').load('fvoucher_in.php?type=1&id='+id);
    $('#dialogedit').dialog({
        title: 'รายการ '+id,
        resizable: false,
        modal: true,  
        width: 600,
        height: 400,
        close: function(ev, ui){
            $('#dialogedit').remove();
            //window.location.reload();
        }
    });
}
</script>

</body>
</html>