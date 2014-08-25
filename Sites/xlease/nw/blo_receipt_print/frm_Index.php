<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(BLO) พิมพ์ใบเสร็จรับเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#id").autocomplete({
        source: "s_blo.php?condition="+ $("#idcon").val(),
        minLength:2
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_Receipt_Detail.php?id="+ $("#id").val()+"&condition="+$("#idcon").val());
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

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>
<?php
	$con= pg_escape_string($_POST["idcon"]);
	if($con==""){
		$con=1;
	}
?>
<fieldset><legend><B>(BLO) พิมพ์ใบเสร็จรับเงิน</B></legend>
<div class="ui-widget" align="center">
<form method="post" name="form1" action="#">
<div style="margin:0;padding-bottom:10px;">
<b>ค้นจาก : </b>
<input type="radio" name="typesearch" id="search1" value="1" onclick="check_search()" checked <?php if($con=="1"){ echo "checked";}?>>สัญญาเลขที่
<input type="radio" name="typesearch" id="search2" value="2" onclick="check_search()" <?php if($con=="2"){ echo "checked";}?>>เลขที่ใบเสร็จ
<br>
<b>คำค้น : </b><input type="text" id="id" name="id" size="40" />
<input type="hidden" id="idcon" name="idcon" value="<?php echo $con;?>">&nbsp;
<input type="button" id="btn1" value="ค้นหา"/>
</div>
</form>
 </fieldset>

        </td>
    </tr>
</table>

<div id="panel" style="padding-top: 10px;"></div>


<!-- ประวัติการพิมพ์ 30 รายการล่าสุด -->

<?php
include("frm_reprint_limit.php");
?>


</div>

</body>
</html>