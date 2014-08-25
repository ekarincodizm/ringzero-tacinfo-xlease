<?php
include("../../config/config.php");
echo $reno = $_GET['reno'];
$sql = "SELECT reno, conno, date, cusname, address, \"NO1\", \"NO2\", \"NO3\", detail1, 
       detail2, detail3, money2, money3, vat1, vat2, vat3, vat_pay1, 
       vat_pay2, vat_pay3, refer, typepay, type_pay_many, vat_pay_check, 
       vat_pay_check_many, \"user\", money1
  FROM \"FA_pirnt_temp\" where \"reno\" = '$reno'";
  
 $query = pg_query($sql);
$re = pg_fetch_array($query);
$row = pg_num_rows($query);
if($row == 0 ){

echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_Index.php\">";
	echo "<script type='text/javascript'>alert(' ไม่มีนะจ๊ะ ')</script>";
	exit();
}



?>
<form method="post" action="print_receipt_pdf.php" target="_blank">
<input type="hidden" name="check" value="S">
<div style="padding-top:5px;"><b>เลขที่ใบเสร็จ</b><input type="text" name="receiptID" size="50" value="<?php echo $re['reno'];?>" ></div>
<div style="padding-top:5px;"><b>เลขที่สัญญา</b><input type="text" name="contractID" size="50" value="<?php echo $re['conno'];?>"></div>
<div style="padding-top:5px;"><b>วันที่ชำระ</b><input type="text" name="receiveDate" size="50" value="<?php echo $re['date'];?>"></div>
<div style="padding-top:5px;"><b>ชื่อลูกค้า</b><input type="text" name="name3" size="50" value="<?php echo $re['cusname'];?>"></div>
<div style="padding-top:5px;"><b>ที่อยู่</b><textarea name="address" cols="50" rows="5"> <?php echo $re['address'];?></textarea></div>
<br>
<div style="padding-top:5px;"><b>NO1</b><input type="text" name="no1" size="50" value="<?php echo $re['NO1'];?>"></div>
<div style="padding-top:5px;"><b>NO2</b><input type="text" name="no2" size="50" value="<?php echo $re['NO2'];?>"></div>
<div style="padding-top:5px;"><b>NO3</b><input type="text" name="no3" size="50" value="<?php echo $re['NO3'];?>"></div>
<br>
<div style="padding-top:5px;"><b>รายละเอียดการรับชำระ1</b><input type="text" name="detail1" size="50" value="<?php echo $re['detail1'];?>"></div>
<div style="padding-top:5px;"><b>รายละเอียดการรับชำระ2</b><input type="text" name="d2" size="50" value="<?php echo $re['detail2'];?>"></div>
<div style="padding-top:5px;"><b>รายละเอียดการรับชำระ3</b><input type="text" name="d3" size="50" value="<?php echo $re['detail3'];?>"></div>

<br>
<div style="padding-top:5px;"><b>จำนวนเงินที่ 1</b><input type="text" name="receiveAmount1" size="50" value="<?php echo $re['money1'];?>"></div>
<div style="padding-top:5px;"><b>จำนวนเงินที่ 2</b><input type="text" name="receiveAmount2" size="50" value="<?php echo $re['money2'];?>"></div>
<div style="padding-top:5px;"><b>จำนวนเงินที่ 3</b><input type="text" name="receiveAmount3" size="50" value="<?php echo $re['money3'];?>"></div>
<br>
<div style="padding-top:5px;"><b>ภาษีมูลค่าเพิ่ม 1</b><input type="text" name="tax1" size="50" value="<?php echo $re['vat1'];?>"></div>
<div style="padding-top:5px;"><b>ภาษีมูลค่าเพิ่ม 2</b><input type="text" name="tax2" size="50" value="<?php echo $re['vat2'];?>"></div>
<div style="padding-top:5px;"><b>ภาษีมูลค่าเพิ่ม 3</b><input type="text" name="tax3" size="50" value="<?php echo $re['vat3'];?>"></div>
<br>
<div style="padding-top:5px;"><b>ภาษีหัก ณ ที่จ่าย 1</b><input type="text" name="taxdel1" size="50" value="<?php echo $re['vat_pay1'];?>"></div>
<div style="padding-top:5px;"><b>ภาษีหัก ณ ที่จ่าย 2</b><input type="text" name="taxdel2" size="50" value="<?php echo $re['vat_pay2'];?>"></div>
<div style="padding-top:5px;"><b>ภาษีหัก ณ ที่จ่าย 3</b><input type="text" name="taxdel3" size="50" value="<?php echo $re['vat_pay3'];?>"></div>
<br>
<div style="padding-top:5px;"><b>อ้างอิงใบภาษีหัก ณ ที่จ่าย เลขที่</b><input type="text" name="reftaxdel" size="50" value="<?php echo $re['refer'];?>"></div>
<br>
<div style="padding-top:5px;"><b>ชำระเป็น</b>
<input type="radio" name="byChannel" value="1" <?php if($re['typepay'] == '1'){ ?> checked <?php } ?>>เงินสด 
<input type="radio" name="byChannel" value="5" <?php if($re['typepay'] == '5'){ ?> checked <?php } ?>>เช็ค  
จำนวน<input type="text" name="money" value="<?php echo $re['type_pay_many'];?>">

<br>
<div style="padding-top:5px;">
<input type="checkbox" name="WHT" value="1" <?php if($re['vat_pay_check'] == '1'){ ?> checked <?php } ?>>ภาษีหัก ณ ที่จ่าย
จำนวน<input type="text" name="ChannelAmtWHT" value="<?php echo $re['vat_pay_check_many'];?>">
</div>

<br>
<div style="padding-top:5px;">ผู้รับเงิน<input type="text" name="fullname" size="50" value="<?php echo $re['user'];?>"></div>

<div style="text-align:center;"><input type="hidden" name="start" value="1"><input type="submit" value="OK"  style="width:150px;height:50px;"><input type="button" value="clear" onclick="parent.location.href='frm_Index.php'" style="width:150px;height:50px;"></div>


</form>

