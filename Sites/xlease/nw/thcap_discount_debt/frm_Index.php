<?php
include("../../config/config.php");
$includepage=0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ขอส่วนลด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#showdata").load("frm_histority.php");
	$("#contractID").autocomplete({
		source: "s_contractID.php",
        minLength:1
    });
	$('#btnsearch').click(function(){
		$("#panel").load("frm_dncnDetail.php?contractID="+ $("#contractID").val());
		$("#showdata").load("frm_histority.php?contractID="+ $("#contractID").val());
		if($("#contractID").val()==""){
			$("#panel1").show();
		}else{
			$("#panel1").hide();
		}
		
	});
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
 
</head>
<body>

<div style="text-align:center;"><h2>(THCAP) ขอส่วนลด</h2></div>
<div style="width:800px;text-align:right;margin:0 auto;"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>      
		<fieldset>
			<legend><B>ค้นหา</B></legend>
			<div align="center" style="width:800px;" id="divmain">
				<div style="float:center; width:800px;">
					เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว :
					<input type="text" name="contractID" id="contractID" value="<?php echo $contractID; ?>" size="70"> &nbsp
					<input type="button" id="btnsearch" value="ค้นหา">
				</div>
				
			</div>
		</fieldset>
	</td>
</tr>
</table>

<div id="panel1">
  <?php if($contractID==""){
   include('frm_histority_limit.php');
   //include('frm_histority.php');
  }?>
<!--<div style="margin:0px auto;width:1000px;"><?php //include('frm_approve.php'); //ข้อมูลอนุมัติ?></div>-->
</div>
<div id="panel" style="width:950px;margin:0 auto;">
</div>
<div id="showdata">

</div>
</body>
</html>