<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ยกเลิกสั่งจ่ายเช็ค</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#id").autocomplete({
        source: "s_chqcancel.php",
        minLength:2
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_detailCancel.php?id="+ $("#id").val());
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

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>
<fieldset><legend><B>ยกเลิกสั่งจ่ายเช็ค</B></legend>
<div class="ui-widget" align="center">
<div style="margin:0;padding-bottom:10px;">
<b>ค้นเลขที่เช็ค : </b><input type="text" id="id" name="id" size="40" />
<input type="button" id="btn1" value="ค้นหา"/>
</div>
 </fieldset>

        </td>
    </tr>
</table>

<div id="panel" style="padding-top: 10px;"></div>

</div>

</body>
</html>