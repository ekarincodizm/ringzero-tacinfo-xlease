<?php
include("../../config/config.php");
$contractID=$_GET["contractID"]; //เลขที่สัญญา
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>คำนวณเบี้ยปรับ เลขที่สัญญา <?php echo $contractID;?></title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
$(document).ready(function(){
	$("#caldate ").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });

	//เริ่มคำนวณ
	$('#startcal').click(function(){
		$.post('api.php',{
			cmd:'calleasefine',
			caldate: $('#caldate').val(),
            contractID: '<?php echo $contractID;?>'
        },
		function(data){
			if(data=='ERROR'){
				alert(data);
			}else{
				var txtval;
				var newTextBoxDiv = $('#panel');
			
				txtval="เบี้ยปรับ  <font color=red>"+data+"</font> บาท คิดถึงวันที่   <font color=red>"+$('#caldate').val()+"</font>";
				newTextBoxDiv.html(txtval);
			}
		});
	});
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>
</head>
<body>
<div style="text-align:center;">
<div><h2>คำนวณเบี้ยปรับ เลขที่สัญญา <?php echo $contractID;?></h2></div>
<hr color="#CDB7B5">
<div>วันที่จะคำนวณ :<input type="text" name="caldate" id="caldate" size="10" value="<?php echo nowDate();?>" style="text-align: center;" readonly>&nbsp;&nbsp;<input type="button" id="startcal" value="เริ่มคำนวณ"></div>

<hr color="#CDB7B5">
<div id="panel" style="padding:20px;font-weight:bold;">&nbsp;</div>
<div><input type="button" value="X ปิด" onclick="window.close();"></div>
<div>

</body>
</html>