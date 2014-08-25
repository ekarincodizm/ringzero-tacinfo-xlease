<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แก้ไขรายละเอียดบิลขอสินเชื่อ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#prebill").autocomplete({
        source: "s_bill.php",
        minLength:1
    });

    $('#btn1').click(function(){	
		if($("#prebill").val()==""){
			alert("กรุณาระบุคำค้น");
		}else{
			var aaaa = $("#prebill").val();
			var brokenstring=aaaa.split("#");
			$("#panel").load("frm_EditBill.php?prebillID="+ brokenstring[0]);
		}    
    });   	
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
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
<table width="850" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
		<div style="clear:both; "></div>
		<div align="center"><h2>(THCAP) แก้ไขรายละเอียดบิลขอสินเชื่อ</h2></div>

		<fieldset><legend><B>ค้นหาข้อมูลเพื่อแก้ไข</B></legend>
		<div class="ui-widget" align="center">
			<div style="padding-left:80px;">ค้นได้จาก ผู้ขายบิล, ลูกหนี้, วันที่ใบแจ้งหนี้, เลขที่ใบแจ้งหนี้</div>
			<div style="margin:0">
				<b>ค้นหารหัสบิล</b>&nbsp;
				<input id="prebill" name="prebill" size="60" />&nbsp;
				<input type="button" id="btn1" value="ค้นหา"/>
			</div>
			
		</div>
		</fieldset>
	</td>
</tr>
</table>
<div id="panel" style="padding-top: 10px;"></div>
</body>
</html>