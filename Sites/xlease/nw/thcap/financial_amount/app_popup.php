<?php
include("../../../config/config.php");

$appid = $_GET["appid"];
$apptype = $_GET["apptype"];
$faid = explode("@",$appid);

if($apptype == 'notapp'){
	$header = '<b>ยืนยันการไม่อนุมัติรายการดังต่อไปนี้ </b>';
}else{
	$header = '<b>ยืนยันการอนุมัติรายการดังต่อไปนี้</b>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
    
<fieldset>
<form name="frm1" id="frm1" action="" method="post">
<table width="450" cellpadding="0" cellspacing="1" border="0">
	<tr>
		<td align="left" colspan="5"><?php echo $header ?></td>
	<tr>
<?php 
for($i=0;$i<sizeof($faid);$i++){
	if($faid[$i] != ""){
		$sql = pg_query("SELECT * FROM thcap_financial_amount_add_temp where  \"financial_amount_serial\" = '$faid[$i]' ");
		$result = pg_fetch_array($sql);
		$contractID = $result["contractID"]; 
		$financial_amount_add = $result["financial_amount_add"]; //วงเงินที่ขอเพิ่ม
		$feeandvat = $result["feeandvat"]; //ค่าธรรมเนียมรวมภาษี
?>
	<tr>
		<td align="left" colspan="5">สัญญา : <?php echo $contractID ?> </td>
	<tr>
	</tr>
		<td  width="28" align="right">-</td>
		<td width="50"  align="left" >วงเงินที่เพิ่ม :</td>
		<td width="55" align="right"><?php echo number_format($financial_amount_add,2) ?></td>  
		<td width="90"  align="right">ค่าธรรมเนียมรวมภาษี : </td> 
		<td width="60" align="right"><?php echo number_format($feeandvat,2) ?></td>  		
	</tr>
	
<?php }
} ?>

<?php if($apptype == 'notapp'){ ?>
<tr><td><br></td></tr>
<tr>
    <td align="right" colspan="1"><b>เหตุผล </b></td>
	<td align="left" colspan="5" ><textarea id="note" name="note" cols="55"></textarea></td>
</tr>
<?php } ?>
<tr><td><br></td></tr>
<tr>
	<td colspan="5" align="center">
		  <input type="button" name="btn_save" id="btn_save" value="ยืนยัน"  style="width:100px"/>		  			

		<input type="button" id="cancelvalue" onclick="$('#dialog').remove()" value="ยกเลิก" style="width:100px">
	</td>
</tr>
</table>
</form>
</fieldset>
<script type="text/javascript">
$('#btn_save').click(function(){
	$("#btn_save").attr('disabled', true);
		$.post("process_approve.php",{
				cmd : '<?php echo $apptype;?>',
				appid :'<?php echo $appid;?>',
				note : $("#note").val(),	
			},
			function(data){	
				if(data == "1"){	
					alert("บันทึกรายการเรียบร้อย");
					location.href = "frm_approve.php";
					$("#btn_save").attr('disabled', false);
				}else if(data == "2"){
					alert("ผิดผลาด ไม่สามารถบันทึกได้!");
					$("#btn_save").attr('disabled', false);
				}
			});
});
</script>
</html>