<?php
include("../../config/config.php");
$start=$_POST["start"];

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- คิคิ -</title>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function check(){	

	var a1 = document.frm.receiptID.value;
	window.location = "search_index.php?reno="+a1;	
}
</script>


<form name="frm" method="post" action="print_receipt_pdf.php" target="_blank">

<div style="padding-top:5px;"><b>เลขที่ใบเสร็จ</b><input type="text" name="receiptID" size="50"><input type="button" value=" กด " onclick="return check();"></div>
<div style="padding-top:5px;"><b>เลขที่สัญญา</b><input type="text" name="contractID" size="50"></div>
<div style="padding-top:5px;"><b>วันที่ชำระ</b><input type="text" name="receiveDate" size="50"></div>
<div style="padding-top:5px;"><b>ชื่อลูกค้า</b><input type="text" name="name3" size="50"></div>
<div style="padding-top:5px;"><b>ที่อยู่</b><textarea name="address" cols="50" rows="5"></textarea></div>
<br>
<div style="padding-top:5px;"><b>NO1</b><input type="text" name="no1" size="50"></div>
<div style="padding-top:5px;"><b>NO2</b><input type="text" name="no2" size="50"></div>
<div style="padding-top:5px;"><b>NO3</b><input type="text" name="no3" size="50"></div>
<br>
<div style="padding-top:5px;"><b>รายละเอียดการรับชำระ1</b><input type="text" name="detail1" size="50"></div>
<div style="padding-top:5px;"><b>รายละเอียดการรับชำระ2</b><input type="text" name="d2" size="50"></div>
<div style="padding-top:5px;"><b>รายละเอียดการรับชำระ3</b><input type="text" name="d3" size="50"></div>

<br>
<div style="padding-top:5px;"><b>จำนวนเงินที่ 1</b><input type="text" name="receiveAmount1" size="50"></div>
<div style="padding-top:5px;"><b>จำนวนเงินที่ 2</b><input type="text" name="receiveAmount2" size="50"></div>
<div style="padding-top:5px;"><b>จำนวนเงินที่ 3</b><input type="text" name="receiveAmount3" size="50"></div>
<br>
<div style="padding-top:5px;"><b>ภาษีมูลค่าเพิ่ม 1</b><input type="text" name="tax1" size="50"></div>
<div style="padding-top:5px;"><b>ภาษีมูลค่าเพิ่ม 2</b><input type="text" name="tax2" size="50"></div>
<div style="padding-top:5px;"><b>ภาษีมูลค่าเพิ่ม 3</b><input type="text" name="tax3" size="50"></div>
<br>
<div style="padding-top:5px;"><b>ภาษีหัก ณ ที่จ่าย 1</b><input type="text" name="taxdel1" size="50"></div>
<div style="padding-top:5px;"><b>ภาษีหัก ณ ที่จ่าย 2</b><input type="text" name="taxdel2" size="50"></div>
<div style="padding-top:5px;"><b>ภาษีหัก ณ ที่จ่าย 3</b><input type="text" name="taxdel3" size="50"></div>
<br>
<div style="padding-top:5px;"><b>อ้างอิงใบภาษีหัก ณ ที่จ่าย เลขที่</b><input type="text" name="reftaxdel" size="50"></div>
<br>
<div style="padding-top:5px;"><b>ชำระเป็น</b>
<input type="radio" name="byChannel" value="1">เงินสด 
<input type="radio" name="byChannel" value="5">เช็ค  
จำนวน<input type="text" name="money">

<br>
<div style="padding-top:5px;">
<input type="checkbox" name="WHT" value="1">ภาษีหัก ณ ที่จ่าย
จำนวน<input type="text" name="ChannelAmtWHT">
</div>

<br>
<div style="padding-top:5px;">ผู้รับเงิน<input type="text" name="fullname" size="50"></div>

<div style="text-align:center;"><input type="hidden" name="start" value="1"><input type="submit" value="OK" style="width:150px;height:50px;"><input type="reset" value="clear"  style="width:150px;height:50px;"></div>


</form>

