<?php
include("../../../config/config.php");
$ascenID = pg_escape_string($_GET["ascenID"]);  
$assetDetailID = pg_escape_string($_GET["assetDetailID"]);  
				
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติรายละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<body>

	<div style="paddind-top:100px;"></div>
	<div> เหตุผลที่ไม่อนุมัติ </div>
	<!--ส่งค่าแบบ form ใน html-->
	<form name="my" method="post" action="process_approve-no.php">
		<div><textarea rows="5" cols="70" name="note" id="note"></textarea></div>
		<div>
			<input type="hidden" name="ascenID" id="ascenID" value="<?php echo $ascenID;?>">
			<input type="hidden" name="assetDetailID" id="assetDetailID" value="<?php echo $assetDetailID;?>">
			<input type="hidden" name="frompage" id="frompage" value="appvdetail">
			<input type="submit" name="btn_save" id="btn_save" value="บันทึก" onclick="return confirmsave()" style="width:100px; cursor:pointer;" />
			<input type="button" id="cancelvalue" onclick="$('#dialog').remove()" value="ยกเลิก" style="width:100px; cursor:pointer;">		
		</div> 
	</form>
</body>
</html>

<script type="text/javascript">
$("#ref1").text($("#fpayrefvalue").val());
function confirmsave(){
	if(confirm('ยืนยันการบันทึก')==true){
		return true;
	}else{
		$("#btn_save").attr('disabled', false);
		return false;
	}
}
</script>