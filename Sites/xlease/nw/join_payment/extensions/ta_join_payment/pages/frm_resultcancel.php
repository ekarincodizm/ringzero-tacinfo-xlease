<?php
session_start();
include("../../../../../config/config.php");
$id=trim($_GET["id"]);

//ค้นหาข้อมูลที่แก้ไข
$qrydata=pg_query("select \"result\" from \"thcap_ContactCus_Temp\" where auto_id='$id'");
list($result)=pg_fetch_array($qrydata);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เหตุผลที่ต้องการยกเลิก</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
$(document).ready(function(){
	$('#save').click(function(){
		if($('#result').val()==""){
			alert("กรุณาระบุเหตุผลที่ต้องการยกเลิก");
			$('#result').focus();
		}else{
			if(confirm('ยืนยันการยกเลิกรายการนี้')==true){
				$.post('process_cancel.php',{
					method:'request',
					id: '<?php echo $id;?>',
					result: $('#result').val()
				},
				function(data){
					if(data==1){  //กรณีมีรายการรออนุมัติอยู่ 
						alert("รายการนี้กำลังรออนุมัติอยู่ จะสามารถทำรายการได้หลังจากอนุมัติแล้ว");
						RefreshMe();
					}else if(data==2){ //กรณีรายการถูกยกเลิกแล้ว
						alert("รายการนี้ได้รับการยกเลิกไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ");
						RefreshMe();
					}else if(data==3){ //กรณีบันทึกสำเร็จ
						alert("บันทึกการขอยกเลิกเรียบร้อยแล้ว");
						RefreshMe();
					}else{ //กรณีข้อมูลผิดพลาด
						alert("ผิดพลาดไม่สามารถขอยกเลิกรายการนี้ได้ "+data);
					}
				});
			}
		}
	});
});
</script>
</head>
<body onload="$('#result').focus();";>
<div style="text-align:center;"><h2>เหตุผลที่ต้องการยกเลิก</h2></div>
<div style="color:red;font-weight:bold;">* การยกเลิกในกรณีนี้ข้อมูลจะหายไปจากระบบ อย่างไรก็ดีจะมีการเก็บประวัติการยกเลิกอยู่</div>
<fieldset><legend><B>เหตุผล</B></legend>
	<div style="text-align:center;padding:10px;color:red;font-weight:bold;"><textarea cols="40" rows="4" id="result"><?php echo $result;?></textarea>*</div>
</fieldset> 
<div style="text-align:center;padding:10px;"><input type="button" id="save" value="บันทึก" style="width:100px;height:30px;"><input type="button" value="ปิด" onclick="window.close();" style="width:100px;height:30px;"></div>
</body>
</html>
