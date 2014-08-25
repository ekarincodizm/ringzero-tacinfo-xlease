<?php
session_start();
include("../../config/config.php");


//$c_code=$_SESSION["session_company_code"];
$rec_id=pg_escape_string($_REQUEST["rec_id"]);
$contractID=pg_escape_string($_REQUEST["contractID"]);
$t=pg_escape_string($_REQUEST["t"]);
$type = pg_escape_string($_GET["type"]);
if($type == ""){
	$typeprint = 'all';
}else if($type == "real"){
	$typeprint = '1';
}else if($type == "copy"){
	$typeprint = '2';
}
$payment = 0;
$other = 0;
$qry_type=pg_query("select \"typePayID\" from thcap_v_receipt_otherpay WHERE \"receiptID\" = '$rec_id'");
while($result_type=pg_fetch_array($qry_type))
{
	$typepay = $result_type['typePayID'];
	if($typepay == "D000" || $typepay == "D112")
	{ // ถ้าเป็นค่างวดของสัญญา JOINT_VENTURE หรือถ้าเป็นค่าที่ปรึกษา จะให้ใช้ใบเสร็จค่างวด
		$payment++;
	}
	else
	{
		$functype = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$typepay')");
		list($typecheck) = pg_fetch_array($functype);
		if($typecheck != "" && $typecheck != "7000" && $typecheck != "8000" && $typecheck != "9000"){
			$payment++;
		}else{
			$other++;
		}
	}
}

if($typecheck == "D000")
{
	$other = 0;
}
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function checkdata(){

	if(document.getElementById('reason_rep').value==""){
		alert("กรุณาระบุเหตุผล");
		document.getElementById('reason_rep').focus();
		return false;
	}else{
		ins_log();
	}
}
 function ins_log(){


 $cs = '1';
 // $.post("thcap_reprint_log_api.php", { 
 // reason: document.getElementById('reason_rep').value,
 // receipt_id: '<?php echo $rec_id; ?>',
 // typeprint: '<?php echo $typeprint; ?>'
 
  // },
  // function(data){
	  // if(data==0){
   // alert("บันทึกเรียบร้อยแล้ว");
   
   <?php if($other > 0){ ?>
   window.open('../Payments_Other/print_receipt_pdf.php?receiptID=<?php echo $rec_id; ?>&contractID=<?php echo $contractID; ?>&typepdf=2&typeprint=<?php echo  $typeprint; ?>&reason='+document.getElementById('reason_rep').value,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800'); // typepdf=2 หมายถึงค่าอื่นๆ
  
   <?php }
   if($payment > 0){ ?>
    window.open('../Payments_Other/print_receipt_pdf.php?receiptID=<?php echo $rec_id; ?>&typepdf=1&typeprint=<?php echo  $typeprint; ?>&reason='+document.getElementById('reason_rep').value,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800'); // ย้ายไปเรียกใบเสร็จใน folder ค่าอื่นๆแทน | typepdf=1 หมายถึงค่างวด
	 <?php }
	 
	 if($t==3){ ?>
   window.open('../Payments_Other/print_receipt_v_inv_pdf.php?receiptID=<?php echo $rec_id; ?>&contractID=<?php echo $contractID; ?>&typeprint=<?php echo  $typeprint; ?>&reason='+document.getElementById('reason_rep').value,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800');
  
   <?php }else if($t==4){ ?>
    window.open('print_receipt_v_inv_pdf.php?receiptID=<?php echo $rec_id; ?>&typeprint=<?php echo  $typeprint; ?>&reason='+document.getElementById('reason_rep').value,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800');

   <?php } ?>
   
   window.close();
	 
	  // }else{
		  
		// alert("บันทึกไม่สำเร็จ กรุณาแจ้งผู้ดูแลระบบ");  
	  // }
  // });


 }
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">


			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr>
				<td align="center"><b>ระบุเหตุผลที่พิมพ์ใบ<?php if($t==1 || $t==2){ ?>เสร็จ<?php }else if($t==3 || $t==4){ ?>กำกับภาษี<?php } ?>(Reprint)</b></td>
			</tr>
			<tr>
				<td align="center"><textarea name="reason_rep" id="reason_rep" cols="55" onKeydown="Javascript: if (event.keyCode==13) document.getElementById('b1').focus();" rows="4"></textarea></td>
			</tr>
			<tr><td align="center">

				<input type="button" id="b1" value="ตกลง" onclick="return checkdata();">
                <input type="reset" value="ยกเลิก" onclick="document.getElementById('reason_rep').value=''" >
			</td></tr>
			</table>
		
			



</td>
</tr>
</table>

</body>
</html>