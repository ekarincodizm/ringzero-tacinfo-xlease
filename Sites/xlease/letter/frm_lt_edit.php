<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#sname").autocomplete({
        source: "s_name.php",
        minLength:2
    });

    $('#btn1').click(function(){
	   $("#panel").load("frm_lt_list_edit.php?idno="+ $("#sname").val());
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

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left">
<input type="button" value="ทำรายการส่งจดหมาย" onclick="window.location='frm_lt.php'">
<input type="button" value="รายงานส่งจดหมาย" onclick="window.location='frm_lt_report.php'">
<input type="button" value="แก้ไขที่อยู่ส่งจดหมาย" onclick="window.location='frm_lt_edit.php'" disabled>
</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>

<fieldset><legend><B>แก้ไขที่อยู่ส่งจดหมาย</B></legend>

<div class="ui-widget" align="center">

<div style="margin:0">
<b>ค้นหา IDNO, ชื่อ/สกุล, ทะเบียน</b>&nbsp;
<input id="sname" name="sname" size="60" />&nbsp;
<input type="button" id="btn1" value="ค้นหา"/>
</div>

<div id="panel" style="padding-top: 10px;"></div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>