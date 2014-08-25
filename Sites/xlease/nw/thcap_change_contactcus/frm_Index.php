<?php
session_start();
include("../../config/config.php");
$add_user=$_SESSION["av_iduser"];
$contractID2=$_GET["contractID"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) เปลี่ยนลำดับคนในสัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

   
   

<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

$(document).ready(function(){	
	$("#contractID").autocomplete({
        source: "s_idall.php",
        minLength:2
    });
	
	$('#btn1').click(function(){
		$('#panel').load('frm_edit.php?contractID='+$("#contractID").val());
	});
	
	$('#btncancel').click(function(){
		window.location='frm_Index.php';
	});
});


</script>
    
</head>
<body onload="$('#contractID').focus();">

<div style="text-align:center;"><h2>(THCAP) เปลี่ยนลำดับคนในสัญญา 	</h2></div>
<div style="width:800px;margin:0 auto;">
	<fieldset><legend><B>ค้นหาเลขที่สัญญา</B></legend>
		<div style="text-align:center;">
			<b>เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว </b>
			<input type="text" id="contractID" name="contractID" value="<?php echo $contractID2;?>" size="60">
			<input type="button" name="btn1" id="btn1" value="   ค้นหา   "><input type="button" name="btncancel" id="btncancel" value="   กลับหน้าแรก   ">
		</div>
	</fieldset>

	<div style="margin-top:25px;" id="panel">
		<?php
		if($contractID2!=""){
			include("frm_edit.php");
		}else{	
			?>
			<!--รายการที่รออนุมัติ-->
			<div>
				<?php
				include("frm_waitapp.php");
				?>
			</div>
			<!--ประวัติการอนุมัติ-->
			<div>
				<?php
				$limit="limit 30";
				$txthead="ประวัติการอนุมัติ 30 รายการล่าสุด";
				include("frm_history.php");
				?>
			</div>
		<?php
		}
		?>
	</div>
</div>
</body>
</html>