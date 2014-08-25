<?php
include("../../config/config.php");
include("../function/checknull.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>

<?php
$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server


pg_query("BEGIN");
$status = 0;

$date_invoice=pg_escape_string($_POST["date_invoice"]);//วันที่ใบกำกับภาษี
$creditor=pg_escape_string($_POST["creditor"]);//ตั้งเจ้าหนี้จากเอกสาร 1-ใบสั่งซื้อ 2-ใบเสนอราคา 3-หนังสือสัญญา
$ref_no=pg_escape_string($_POST["ref_no"]);//เลขอ้างอิงเอกสาร
$date_ref_no=pg_escape_string($_POST["date_ref_no"]);//วันที่ของเอกสารอ้างอิง

$rdo_creditor=pg_escape_string($_POST["rdo_creditor"]);//radio เจ้าหนี้  โดย    0-บุคคลภายนอก, 1-ลูกค้าบุคคล, 2-ลูกค้านิติบุคคล  3-พนักงานบริษัท
$txt_creditor=pg_escape_string($_POST["txt_creditor"]);//เจ้าหนี้
$txt_creditor_out=pg_escape_string($_POST["txt_creditor_out"]);//ถ้า  radio เจ้าหนี้  โดย    0-บุคคลภายนอก
list($CusID,$CusName) = explode('#',$txt_creditor); 

$txt_conid=pg_escape_string($_POST["txt_conid"]);//เลขที่สัญญา
$note=pg_escape_string($_POST["note"]);//หมายเหตุ
$date_due=pg_escape_string($_POST["date_due"]);//วันที่ครบกำหนดชำระ

$rowShare = $_POST["rowShare"]; // จน.หนี้ที่ต้องการจะตั้ง
$txt_sumvat=pg_escape_string($_POST["txt_sumvat"]);

echo '<br>ส่วน  ที่ 1.<br>';
echo 'วันที่ใบกำกับภาษี (date_invoice)='.$date_invoice.'<br>';
echo 'ตั้งเจ้าหนี้จากเอกสาร (creditor)='.$creditor.'   โดย    1-ใบสั่งซื้อ 2-ใบเสนอราคา 3-หนังสือสัญญา<br>';
echo 'เลขอ้างอิงเอกสาร (ref_no)='.$ref_no.'<br>';
echo 'วันที่ของเอกสารอ้างอิง (date_ref_no)='.$date_ref_no.'<br>';
echo 'radio เจ้าหนี้  (rdo_creditor)='.$rdo_creditor.'    โดย    0-บุคคลภายนอก, 1-ลูกค้าบุคคล, 2-ลูกค้านิติบุคคล  3-พนักงานบริษัท<br>';
echo 'เจ้าหนี้ (txt_creditor)='.$txt_creditor.'<br>';
echo 'เจ้าหนี้  บุคคลภายนอก (txt_creditor_out)='.$txt_creditor_out.'<br>';
echo 'วันที่ครบกำนดกชำระ (date_due)='.$date_due.'<br>';
echo 'เลขที่สัญญา (txt_conid)='.$txt_conid.'<br><br>';

echo '<br>ส่วน  ที่ 2  บัญชี<br>';
for($i=0;$i<count($_POST["acid"]);$i++)
{
	$adds_serial  = pg_escape_string($_POST['acid'][$i]); 
	$adds_money   = pg_escape_string($_POST['text_money'][$i]);
	$abd_bookType = pg_escape_string($_POST['actype'][$i]);
echo 'ลำดับที่ '.$i.'<br>';	
echo 'เลือกบัญชี  ($adds_serial)='.$adds_serial.'<br>';
echo 'สถานะ ($adds_money)='.$adds_money.'<br>';
echo 'ยอดเงิน ($abd_bookType)='.$abd_bookType.'<br><br>';	
}

echo '<br>ส่วน  ที่ 3  หนี้ที่ต้องการจะตั้ง<br>';
for($i=1; $i<=$rowShare; $i++)
{
	$txt_namep_s[$i] = $_POST["txt_namep_s$i"]; 
	$txt_amountp_s[$i] = $_POST["txt_amountp_s$i"]; 
	$txt_vatp_s[$i] = $_POST["txt_vatp_s$i"];

	
echo 'ลำดับที่ '.$i.'<br>';		
echo 'ชื่อรายการสินค้าหรือบริการ  ($txt_namep_s[$i])='.$txt_namep_s[$i].'<br>';
echo 'จำนวนเงินค่าสินค้าหรือบริการ ($txt_amountp_s[$i])='.$txt_amountp_s[$i].'<br>';
echo 'จำนวนภาษีมูลค่าเพิ่ม ($txt_vatp_s[$i])='.$txt_vatp_s[$i].'<br><br>';

}

echo 'หมายเหตุ (note)='.$note.'<br>';
echo 'รวม vat (txt_sumvat)='.$txt_sumvat.'<br>';



?>
</html>