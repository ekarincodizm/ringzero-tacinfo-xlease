<?php
include('../config/config.php');
$id_tranpay = pg_escape_string($_GET["id_tranpay"]);

//ดึง ข้อมูล ต่าง ๆ
$query_detail= pg_query("SELECT * FROM \"TranPay\" WHERE \"id_tranpay\" = '$id_tranpay' ");

while($res=pg_fetch_array($query_detail))
{
    $amt = $res['amt'];
    $pay_bank_branch = $res['pay_bank_branch'];
    $tr_date = $res['tr_date'];
    $tr_time = $res['tr_time'];
    $bank_no = $res['bank_no'];
    $PostID = $res['PostID'];
    
    $BankName = 0;
    $qry_bank=pg_query("select \"BankName\" from \"BankCheque\" WHERE \"BankNo\"='$bank_no' ");
    if($res_bank=pg_fetch_array($qry_bank))
	{
        $BankName = $res_bank["BankName"];
    }
}
?>

<script type="text/javascript">
function validate()
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	if (document.frm.note.value=="")
	{
		theMessage = theMessage + "\n -->  กรุณาระบุ หมายเหตุการยกเลิก";
	}

	// If no errors, submit the form
	if (theMessage == noErrors){
		return true;
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>


<body>
<form name="frm" action="process_acc_del.php" method="POST">
	<div style="text-align:center"><h2>ขอยกเลิกรายการเงินที่ไม่ใช่ Bill Payment</h2></div>
	<div><b>ธนาคาร :</b> <?php echo $BankName?></div>
	<div><b>รหัสสาขา :</b> <?php echo $pay_bank_branch;?></div>
	<div><b>วันเวลาที่โอน :</b> <?php echo "$tr_date $tr_time";?></div>
	<div><b>จำนวนเงิน :</b> <?php echo number_format($amt,2); ?></div>
	<div style="padding-top:10px;width:400px;">
		<fieldset><legend><b>หมายเหตุการยกเลิก</b></legend>
			<textarea name="note" id="note" cols="60" rows="4" ></textarea>
		</fieldset>
	</div>
	<div style="text-align:center;padding:20px">
		<input type="hidden" name="id_tranpay" value="<?php echo $id_tranpay; ?>">
		<input type="submit" onclick="return validate();"; value="บันทึก">
		<input type="button" onclick="window.close();" value="ปิดหน้านี้">
	</div>
</form>
</body>