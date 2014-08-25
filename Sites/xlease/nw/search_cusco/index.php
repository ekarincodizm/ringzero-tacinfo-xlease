<?php
include("../../config/config.php");
$cussearch = pg_escape_string($_GET['cusid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ตรวจสอบข้อมูลลูกค้า</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#sname").autocomplete({		
        source: "s_cusco.php",
        minLength:2
    });

    $('#btn1').click(function(){
		var aaaa = $("#sname").val();
        var brokenstring=aaaa.split("#");
		
		$("#panelcus").text('กำลังค้นหาข้อมูลลูกค้า  โปรดรอซักครู่....');
		$("#panelcus").load("Data_cus.php?CusID="+ brokenstring[0]);
		$("#panelcusrelative").text('กำลังค้นหาข้อมูลบุคคลที่เกี่ยวข้อง โปรดรอซักครู่....');
        $("#panelcusrelative").load("Data_relative.php?CusID="+ brokenstring[0]);
		$("#panel").text('กำลังค้นหาข้อมูล โปรดรอซักครู่....');
		$("#panel").load("frm_cusco_detail.php?CusID="+ brokenstring[0],function(){
			var cus_id = $('#h_cus_id').val();
			var cus_name = $('#h_fname').val();
			cus_name = cus_name.replace(' ','_');
			var old_title = document.title;
			document.title = old_title+' / '+cus_id+'-'+cus_name;
		});
		
		$("#panelthcap").text('กำลังค้นหาข้อมูล โปรดรอซักครู่....');
		$("#panelthcap").load("frm_thcap_detail.php?CusID="+ brokenstring[0]);
		
    });
	
	
<?php if($cussearch != ""){ ?>
		var cus_search_full = '<?php echo $cussearch; ?>';
		var cus_search = cus_search_full.split('#');
		$("#panelcus").text('กำลังค้นหาข้อมูลลูกค้า  โปรดรอซักครู่....');
		$("#panelcus").load("Data_cus.php?CusID="+cus_search[0]);
		$("#panelcusrelative").text('กำลังค้นหาข้อมูลบุคคลที่เกี่ยวข้อง โปรดรอซักครู่....');
		$("#panelcusrelative").load("Data_relative.php?CusID="+cus_search[0]);
		$("#panel").text('กำลังค้นหาข้อมูล โปรดรอซักครู่....');
        $("#panel").load("frm_cusco_detail.php?CusID="+cus_search[0]);
		$("#panelthcap").text('กำลังค้นหาข้อมูล โปรดรอซักครู่....');
		$("#panelthcap").load("frm_thcap_detail.php?CusID="+cus_search[0]);
		
<?php } ?>		

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

<fieldset><legend><B>ตรวจสอบข้อมูลลูกค้า</B></legend>
<div class="ui-widget" align="center">
*กรุณาใส่ชื่อลูกค้า เพื่อค้นหาสัญญาที่เกี่ยวข้องทั้งหมด
<div style="margin:0;padding-bottom:10px;">
<b>ตรวจสอบข้อมูลลูกค้า จาก ชื่อ/สกุล เลขบัตรประชาชน หนังสือเดินทาง รหัสลูกค้า</b>&nbsp;<br>
<input id="sname" name="sname" size="60" value="<?php echo $cussearch; ?>" />&nbsp;
<input type="button" id="btn1" value="ค้นหา"/>
</div>
 </fieldset>

        </td>
    </tr>
</table>
<div id="panelcus" style="padding-top: 0px; position:relative;" align="center"></div>
<div style="padding:0px;"></div>
<div id="panelcusrelative" style="padding-top: 0px;" align="center"></div>
<div style="padding:0px;"></div>
<div id="panel" style="padding-top: 10px;" align="center"></div>
<!-- thcap -->
<div style="padding:0px;"></div>
<div id="panelthcap" style="padding-top: 10px;" align="center"></div>

</div>

</body>
</html>