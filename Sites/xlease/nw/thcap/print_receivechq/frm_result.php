<?php
include("../../../config/config.php");
$recnum = $_GET['recnum'];
$show = $_GET['show'];
if($show==1){
	$id = $_GET['id'];
	
	//ค้นหาเหตุผลในการพิมพ์
	$qryresult=pg_query("select \"revChqNum\",result from finance.thcap_receive_cheque_print_log where auto_id='$id'");
	list($recnum,$result)=pg_fetch_array($qryresult);
}
?>
<html>
<head><title>เหตุผลในการพิมพ์ใบรับเช็ค</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
$(document).ready(function(){
	$('#btnprint').click(function(){
        if($('#result').val()==""){
			alert("กรุณากรอกเหตุผลในการพิมพ์ครั้งนี้");
			$('#result').focus();
		}else{
			$.post("process_print.php",{
				method : "save" , 
				recnum : '<?php echo $recnum; ?>',
				result : $('#result').val()
			},
			function(data){
				if(data==1){
					alert("บันทึกข้อมูลเรียบร้อยแล้ว");
					popU('print_pdf.php?recnum=<?php echo $recnum; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');
					opener.location.reload(true);
					self.close();
				}else{
					alert("ไม่สามารถบันทึกข้อมูลได้ "+data);
				}
			});
		}
    });
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body onload="$('#result').focus();";>
<div align="center">
	<div style="padding:5px;"><h2>เหตุผลในการพิมพ์</h2><b>ใบรับเช็คเลขที่ : <font color="red"><?php echo $recnum; ?></font></b></div>
	<div><textarea cols="50" rows="5" name="result" id="result" <?php if($show==1){ echo "readonly"; } ?>><?php echo $result;?></textarea><font color="red"><b>*</b></font></div>
	<div style="padding:5px;"><?php if($show!=1){ ?><input type="button" id="btnprint" value="พิมพ์"><?php } ?><input type="button" value="ปิด" onclick="window.close();"></div>
</div>
</body>
</html>
		
		
		
	
