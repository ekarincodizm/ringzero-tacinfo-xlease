<?php
include("../../config/config.php");
$realpath = redirect($_SERVER['PHP_SELF'],'nw/thcap');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
    
    $('#btn1').click(function(){
        $("#btn1").attr('disabled', true);
        $("#panel").text('กำลังค้นหาข้อมูล ....');
        $("#panel").load("frm_lt_report_list.php?date="+ $("#datepicker").val()+"&sendID="+$("#sendID").val());
        $("#btn1").attr('disabled', false);
    });
    
    $("#sname").autocomplete({
        source: "s_name_dt.php",
        minLength:2
    });

    $('#btn2').click(function(){
        $("#panel").load("frm_lt_report_list_dt.php?dt="+ $("#sname").val()+"&sendID="+$("#sendID").val()+"&realpath="+'<?php echo $realpath;?>');
    });
    
});
function check_search(){
	if(document.getElementById("search2").checked){
		document.getElementById("datepicker").disabled =false;
		document.getElementById("sname").value ='';
		document.getElementById("btn1").disabled =false;
		document.getElementById("sname").disabled = true;
		document.getElementById("btn2").disabled = true;
	}else if(document.getElementById("search1").checked){
		document.getElementById("datepicker").disabled =true;
		document.getElementById("btn1").disabled =true;
		document.getElementById("sname").disabled = false;
		document.getElementById("datepicker").value = '';
		document.getElementById("btn2").disabled = false;
	}
}
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>

<fieldset><legend><B>(THCAP) รายงานส่งจดหมาย</B></legend>

<div class="ui-widget" align="center">
<div style="float:left; margin:0"><b>รูปแบบจดหมาย :</b>
<select id="sendID">
<option value="">ทั้งหมด</option>
<?php
//ดึงภัยเพิ่มพิเศษขึ้นมาจากฐานข้อมูล
	$qryspecial=pg_query("SELECT auto_id, \"sendName\" FROM thcap_letter_head");
	$numspec=pg_num_rows($qryspecial);
	while($resspec=pg_fetch_array($qryspecial)){
		list($sendId,$sendName)=$resspec;
		echo "<option value=\"$sendId\">$sendName</option>";		
	}	
?>
</select>
</div>

<div style="float:right; padding-left:50px;">
<input type="radio" name="search" id="search1" value="1" onclick="check_search()" checked ><b>ค้นหาจาก เลขที่สัญญา, ชื่อ/สกุล</b>
<input type="text" id="sname" name="sname" value="" size="30" style="text-align: left;">&nbsp;
<input type="button" name="btn2" id="btn2" value="เริ่มค้น"/>
</div>

<div style="float:right; margin:0">
<input type="radio" name="search" id="search2" value="2" onclick="check_search()"><b>ค้นหาจากวันที่</b>&nbsp;
<input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15" style="text-align: center;" disabled>&nbsp;
<input type="button" name="btn1" id="btn1" value="เริ่มค้น" disabled />
</div>
<div style="clear:both;"></div>
<div style="text-align:left;padding-top:10px;"><font color="red"><b>* หมายเหตุ</b></font> การเลือกรูปแบบจดหมาย เป็นเพียงการเลือกแสดงรายการเฉพาะที่เลือกเท่านั้น หากสั่งพิมพ์จดหมาย รายการที่พิมพ์คือรายการทั้งหมดที่เคยส่งไป</div>
<div id="panel" style="padding-top: 10px;"></div>
</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>