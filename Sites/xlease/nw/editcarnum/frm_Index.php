<?php
session_start();
include("../../config/config.php");
$add_user=$_SESSION["av_iduser"];
$idno2=$_GET["idno"];
if($idno2!=""){
	//ตรวจสอบว่ารายการนี้รออนุมัติอยู่หรือไม่
	$qrychk=pg_query("select * from \"Carnum_Temp\" where \"IDNO\"='$idno2' and \"appStatus\"='2'");
	if(pg_num_rows($qrychk)>0){
		echo "<center><h2>รายการนี้กำลังรออนุมัติ กรุณาทำรายการหลังจากได้รับการอนุมัติแล้ว<h2></center>";
		exit();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขตัวถังรถยนต์</title>
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
	$("#idno").autocomplete({
        source: "s_idno.php",
        minLength:2
    });
	
	$('#btn1').click(function(){
		$('#panel').load('frm_edit.php?idno='+$("#idno").val());
	});
});


</script>
    
</head>
<body onload="$('#idno').focus();">

<div style="text-align:center;"><h2>ขอแก้ไขตัวถังรถยนต์</h2></div>
<div style="width:800px;margin:0 auto;">
	<fieldset><legend><B>ค้นหาเลขที่สัญญา</B></legend>
		<div style="text-align:center;">
			<b>IDNO,ชื่อ/สกุล,ทะเบียน,Ref1,Ref2</b>
			<input type="text" id="idno" name="idno" value="<?php echo $idno2;?>" size="60">
			<input type="button" name="btn1" id="btn1" value="   ค้นหา   " tabindex="1">
		</div>
	</fieldset>

	<div style="margin-top:25px;" id="panel">
		<?php
		if($idno2!=""){
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