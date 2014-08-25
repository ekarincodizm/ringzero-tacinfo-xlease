<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) เพิ่มประเภทเอกสารส่งจดหมาย</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){

    $("#auto_id").autocomplete({
        source: "s_type.php",
        minLength:1
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_list.php?auto_id="+ $("#auto_id").val());
    });

});

</script>
<center><h2>(THCAP) เพิ่มประเภทเอกสารส่งจดหมาย</h2></center>
<body >
<fieldset><legend><B>ประเภทของรูปแบบจดหมาย</B></legend>
<div class="ui-widget" align="center">

<div style="margin:0; padding: 10px;">
<b>ค้นหา ชื่อประเภท :</b>&nbsp;
<input id="auto_id" name="auto_id" size="60" />&nbsp;
<input type="button" id="btn1" value="ค้นหา"/>
<input type="button" id="btnadd" value="เพิ่มประเภทเอกสารส่งจดหมาย" onclick="javascript:popU('frm_add_type.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')""/>
</div>
<div align="center" id="panel" style="padding-top: 10px;">
</div>
</fieldset>
<fieldset><legend><B>ประเภทเอกสารส่งจดหมายที่ใช้ในระบบ
</B></legend>
<div align="center" id="panel_all" style="padding-top: 10px;">
<?php include("frm_listdetail_insys.php")?>
</div>
</div>
</fieldset>
</body>