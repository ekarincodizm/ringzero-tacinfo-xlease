<?php
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) พิมพ์ใบลดหนี้</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
	//autocomplete การค้นหา
	$("#txtsearch").autocomplete({
        source: "../thcap_dncn/s_dncn.php",
        minLength:1
    });
	//เมื่อกด ค้นหา
	$("#buttonsearch").click(function(){
		$("#panel").load("../thcap_dncn/frm_reprint.php?dcNoteID="+$("#txtsearch").val());
	});	
});
function check_search(){
	if(document.getElementById("search1").checked){
		document.getElementById("idcon").value ='1';
	}else if(document.getElementById("search2").checked){
		document.getElementById("idcon").value ='2';
		
	}
	document.form1.submit();
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
</head>

<div style="text-align:center;"><h2>(THCAP) พิมพ์ใบลดหนี้</h2>
<body bgcolor="<?php echo $bgcolor_body; ?>">
<fieldset><legend><B>ค้นหา</B></legend>
<form method="post" name="form1" action="#">
<div style="margin:0;padding-bottom:10px;">
<table align="center" width="99%" border="0">
	<tr>		
		<td align="center">ค้นหาจากรหัส CreditNote / เลขที่สัญญา / ชื่อนามสกุลผู้กู้หลัก-ร่วม / เลขบัตร</td>
	</tr>
	<tr>
		<td align="center" colspan="2"><b>ค้นหา :</b>
		<input type="text" name="txtsearch" id="txtsearch" size="80"><input type="button" id="buttonsearch" value="ค้นหา">
		</td>
	</tr>
</table>
<!--พื้นที่ที่แสดง ข้อมูลจากการค้นหา-->
<div id="panel">
</div>
<!--จบพื้นที่ที่แสดง ข้อมูลจากการค้นหา-->
</div>	
</fieldset> 
</div>
<?php  //แสดงรายการ  ประวัติ 30 รายการ ล่าสุด ในการขอ reprint 
include("frm_history_limit.php");
?>
</body>