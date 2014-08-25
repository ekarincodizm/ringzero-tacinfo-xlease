<?php
include("../../config/config.php");
$contractidsend = $_GET["contractID"];
$coneditID = $_GET["coneditID"];
				
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใส่รายละเอียดสัญญา BH</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<body>
<form name="my" method="post" action="process_approve.php">
	<div style="paddind-top:100px;"></div>
	<div> เลขที่สัญญา : <?php echo $contractidsend; ?> </div>
	<div> เหตุผลที่ไม่ถูกต้อง </div>
	<div><textarea rows="5" cols="70" name="note" id="note"></textarea></div>
	<div>
	     <input type="hidden" name="coneditID" id="coneditID" value="<?php echo $coneditID;?>">
		 <input type="hidden" name="cmd" id="cmd" value="not">
		 <input type="submit" name="btn_save" id="btn_save" value="บันทึก" style="width:100px;" />
		 <input type="button" id="cancelvalue" onclick="$('#dialog').remove()" value="ยกเลิก" style="width:100px;">
	</div> 
</form>	
</body>
</html>

<script type="text/javascript">
$("#ref1").text($("#fpayrefvalue").val());
$('#btn_save').click(function(){
  
$("#btn_save").attr('disabled', true);
	
	if(confirm('ยืนยันการบันทึก')==true){ 
		return true;			
	}else{
		$("#btn_save").attr('disabled', false);
		return false;
	}
});
</script>