<?php
include("config/config.php");
if($_SESSION['app_username']=="")
{
	echo "<script type=\"text/javascript\">window.location.href = 'index.php';</script>";
}
else
{
$id = $_GET['id'];
$q = "select * from $schema.\"app_frm\" where \"formID\"='$id'";
$qr = pg_query($q);
$rs = pg_fetch_array($qr);
$model_name = split(",",$rs['model_name']);
$ppu = split(",",$rs['ppu']);
$quantity = split(",",$rs['quantity']);
$personal_attach = split(",",$rs['personal_attach']);
$corperate_attach = split(",",$rs['corperate_attach']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แบบฟอร์มขอสินเชื่อ</title>
<link href="css/form.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<?php require("proc/top.php"); ?>
	<div class="container">
    <form method="post" name="app_form" id="app_form" action="save_process.php" enctype="multipart/form-data">
        <div id="customer_form">
            <div class="box" id="header">
                <div class="row">
                    <div class="inline logo">
                        <img src="images/logo-tacapital.png" width="95" height="66" />
                    </div>
                    <div class="inline corperate">
                        <div class="row big bold">บริษัท ไทยเอซ แคปปิตอล จำกัด</div>
                        <div class="row big bold">THAI ACE CAPITAL CO.,LTD.</div>
                        <div class="row bold">555 ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม กรุงเทพฯ 10240</div>
                        <div class="row bold">โทรศัพท์ 0-2744-2222 โทรสาร 0-2379-1111 www.tcapital.co.th</div>
                        <div class="row bold">เลขที่ประจำตัวผู้เสียภาษีอากร 0105553136996</div>
                    </div>
                    <div class="inline title">
                        <div class="row large bold">แบบฟอร์มการขอสินเชื่อ</div>
                        <div class="row large bold">Application Form</div>
                    </div>
                </div>
            </div>
            <div class="box" id="block1">
                <div class="row">
                    <div class="inline two_col_long">
                        <div class="label">ชื่อบริษัทลูกค้า (Company's name)</div>
                        <div class="input"><input type="text" name="company_name" id="company_name" class="full" value="<?php echo $rs['company_name']; ?>" /></div>
                    </div>
                    <div class="inline two_col_short">
                        <div class="label">วันที่ (date)</div>
                        <div class="input"><input type="text" name="date" id="date" class="full" readonly="readonly"  value="<?php echo $rs['form_date']; ?>" /></div>
                    </div>
                </div>
                <div class="row">
                    <div class="label">ชื่อกรรมการที่ลงนาม (Name of Director Authorized)</div>
                    <div class="input"><input type="text" name="director_name" id="director_name" class="full" value="<?php echo $rs['director_name']; ?>" /></div>
                </div>
                <div class="row">
                    <div class="label">ที่อยู่ตามหนังสือรับรอง (Address of Company)</div>
                    <div class="input"><input type="text" name="company_address" id="company_address" class="full" value="<?php echo $rs['company_address']; ?>" /></div>
                </div>
                <div class="row">
                    <div class="label">ที่อยู่ที่ออกใบกำกับภาษี (Place of VAT Issue)</div>
                    <div class="input"><input type="text" name="vat_place" id="vat_place" class="full" value="<?php echo $rs['vat_place']; ?>" /></div>
                </div>
                <div class="row">
                    <div class="label">ที่ตั้งเครื่อง (Installed Equipment Place)</div>
                    <div class="input"><input type="text" name="equipment_place" id="equipment_place" class="full" value="<?php echo $rs['equipment_place']; ?>" /></div>
                </div>
                <div class="row">
                    <div class="label">ชื่อลูกค้าที่ติดต่อ (Customer's name)</div>
                    <div class="input"><input type="text" name="customer_name" id="customer_name" class="full" value="<?php echo $rs['customer_name']; ?>" /></div>
                </div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="label">หมายเลขโทรศัพท์ (Tel.)</div>
                        <div class="input"><input type="text" name="telephone" id="telephone" class="full" value="<?php echo $rs['telephone']; ?>" /></div>
                    </div>
                    <div class="inline two_col">
                        <div class="label">แฟกซ์ (Fax.)</div>
                        <div class="input"><input type="text" name="fax" id="fax" class="full" value="<?php echo $rs['fax']; ?>" /></div>
                    </div>
                </div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="label">เคยเป็นลูกค้าของดีลเลอร์หรือไม่</div>
                        <div class="input">
                            <label class="inline"><input type="radio" name="dealer_customer" id="dealer_customer1" value="เคย" <?php if($rs['dealer_customer']=="เคย") echo "checked=\"checked\""; ?> /> เคย/Yes</label>
                            <label class="inline"><input type="radio" name="dealer_customer" id="dealer_customer2" value="ไม่เคย" <?php if($rs['dealer_customer']=="ไม่เคย") echo "checked=\"checked\""; ?> /> ไม่เคย/No</label>
                        </div>
                    </div>
                    <div class="inline two_col">
                        <div class="label">ประวัติการชำระเงิน</div>
                        <div class="input">
                            <label class="inline"><input type="radio" name="pay_history" id="pay_history1" value="ดี" <?php if($rs['pay_history']=="ดี") echo "checked=\"checked\""; ?> /> ดี/Good</label>
                            <label class="inline"><input type="radio" name="pay_history" id="pay_history2" value="ล่าช้าบ้าง" <?php if($rs['pay_history']=="ล่าช้าบ้าง") echo "checked=\"checked\""; ?> /> ล่าช้าบ้าง/Some Delay</label>
                            <label class="inline"><input type="radio" name="pay_history" id="pay_history3" value="ล่าช้า" <?php if($rs['pay_history']=="ล่าช้า") echo "checked=\"checked\""; ?> /> ล่าช้า/Delay</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="label">ระยะเวลาที่ได้เป็นลูกค้า</div>
                        <div class="input"><input type="text" name="customer_time" id="customer_time" class="full" value="<?php echo $rs['customer_time']; ?>" /></div>
                    </div>
                    <div class="inline two_col">
                        <div class="label">จำนวนเครื่องถ่ายเอกสารที่มีอยู่</div>
                        <div class="input"><input type="text" name="all_cpm" id="all_cpm" class="full" value="<?php echo $rs['all_cpm']; ?>" /></div>
                    </div>
                </div>
            </div>
            <div class="box" id="block2">
                <div class="row gray">
                    <div class="inline three_col">รายละเอียดการชำระค่าเช่า(Details of rental payment)</div>
                    <div class="inline three_col center">วางบิล(Settle the bill)</div>
                    <div class="inline three_col center">รับเช็ค(Receiving Cheque)</div>
                </div>
                <div class="inline three_col">
                    <div class="row right cust_h">วันที่ (Date)</div>
                    <div class="row right cust_h">สถานที่ (Place)</div>
                    <div class="row right cust_h">เงื่อนไขต่างๆ (Other Condition)</div>
                </div>
                <div class="inline three_col">
                    <div class="row"><input type="text" name="bill_date" id="bill_date" class="full" readonly="readonly" value="<?php echo $rs['bill_date']; ?>" /></div>
                    <div class="row"><input type="text" name="bill_place" id="bill_place" class="full" value="<?php echo $rs['bill_place']; ?>" /></div>
                    <div class="row"><input type="text" name="bill_condition" id="bill_condition" class="full" value="<?php echo $rs['bill_condition']; ?>" /></div>
                </div>
                <div class="inline three_col">
                    <div class="row"><input type="text" name="cheque_date" id="cheque_date" class="full" readonly="readonly" value="<?php echo $rs['cheque_date']; ?>" /></div>
                    <div class="row"><input type="text" name="cheque_place" id="cheque_place" class="full" value="<?php echo $rs['cheque_place']; ?>" /></div>
                    <div class="row"><input type="text" name="cheque_condition" id="cheque_condition" class="full" value="<?php echo $rs['cheque_condition']; ?>" /></div>
                </div>
            </div>
            <div class="box" id="block3">
                <div class="row">
                    <div class="inline two_col">
                        <div class="row gray bold under_line" style="padding-left:5px;">รายละเอียดทรัพย์สิน/Detail of Assets</div>
                        <div class="row">
                            <div class="label">เงินดาวน์ (Down Payment):</div>
                            <div class="input"><input type="text" name="down_payment" id="down_payment" class="full" value="<?php echo $rs['down_payment']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="label">ยอดจัด (Financial Amount : Excluding VAT):</div>
                            <div class="input"><input type="text" name="financial_amount" id="financial_amount" class="full" value="<?php echo $rs['financial_amount']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="label">ระยะเวลา/ปี (Term/Years)</div>
                            <div class="input"><input type="text" name="term_years" id="term_years" class="full" value="<?php echo $rs['term_years']; ?>" /></div>
                        </div>
                        <div class="row">รุ่นและหมายเลขเครื่อง/จำนวนเครื่อง (Model and Serial number per unit)</div>
                        <div class="row">
                            <div class="table_row thead">
                                <div class="inline id center">id</div>
                                <div class="inline model_name">Model Name</div>
                                <div class="inline ppu">Price Per Unit (Excl. VAT)</div>
                                <div class="inline quantity">Quantity</div>
                            </div>
                            <div class="table_row">
                                <div class="inline id center">1</div>
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name1" class="tb_full" value="<?php echo $model_name[0]; ?>" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu1" class="tb_full" value="<?php echo $ppu[0]; ?>" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity1" class="tb_full" value="<?php echo $quantity[0]; ?>" /></div>
                            </div>
                            <div class="table_row">
                                <div class="inline id center">2</div>
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name2" class="tb_full" value="<?php echo $model_name[1]; ?>" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu2" class="tb_full" value="<?php echo $ppu[1]; ?>" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity2" class="tb_full" value="<?php echo $quantity[1]; ?>" /></div>
                            </div>
                            <div class="table_row">
                                <div class="inline id center">3</div>
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name3" class="tb_full" value="<?php echo $model_name[2]; ?>" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu3" class="tb_full" value="<?php echo $ppu[2]; ?>" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity3" class="tb_full" value="<?php echo $quantity[2]; ?>" /></div>
                            </div>
                            <div class="table_row">
                                <div class="inline id center">4</div>
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name4" class="tb_full" value="<?php echo $model_name[3]; ?>" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu4" class="tb_full" value="<?php echo $ppu[3]; ?>" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity4" class="tb_full" value="<?php echo $quantity[3]; ?>" /></div>
                            </div>
                            <div class="table_row tboth">
                                <div class="inline total center">TOTAL</div>
                                <div class="inline ppu"><input type="text" name="pputotal" id="pputotal" class="tb_full" value="<?php echo $rs['pputotal']; ?>" /></div>
                                <div class="inline quantity"><input type="text" name="quantitytotal" id="quantitytotal" class="tb_full" value="<?php echo $rs['quantitytotal']; ?>" /></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="label">ผู้ถือทรัพย์สินเมื่อหมดสัญญา</div>
                            <div class="input">
                                <label class="inline"><input type="radio" name="owner" id="owner1" value="IFEC" <?php if($rs['owner']=="IFEC") echo "checked=\"checked\""; ?> /> IFEC</label>
                                <label class="inline"><input type="radio" name="owner" id="owner2" value="THCAP" <?php if($rs['owner']=="THCAP") echo "checked=\"checked\""; ?> /> THCAP</label>
                                <label class="inline"><input type="radio" name="owner" id="owner3" value="ลูกค้า" <?php if($rs['owner']=="ลูกค้า") echo "checked=\"checked\""; ?> /> ลูกค้า</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="label">วิธีการขำระเงิน</div>
                            <div class="input"><input type="text" name="payment_step" id="payment_step" class="full" value="<?php echo $rs['payment_step']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="label">มูลค่าซาก (%)</div>
                            <div class="input"><input type="text" name="remainder_price" id="remainder_price" class="full" value="<?php echo $rs['remainder_price']; ?>" /></div>
                        </div>
                    </div>
                    <div class="inline two_col">
                        <div class="row">
                            <div class="label">ประเภทเครื่อง</div>
                            <div class="input">
                                <label class="inline"><input type="radio" name="machine_type" id="machine_type1" value="เครื่องมือหนึ่ง" <?php if($rs['machine_type']=="เครื่องมือหนึ่ง") echo "checked=\"checked\""; ?> /> เครื่องมือหนึ่ง</label>
                                <label class="inline"><input type="radio" name="machine_type" id="machine_type2" value="เครื่องมือสอง" <?php if($rs['machine_type']=="เครื่องมือสอง") echo "checked=\"checked\""; ?> /> เครื่องมือสอง</label>
                                <label class="inline"><input type="radio" name="machine_type" id="machine_type3" value="เครื่องทดลอง" <?php if($rs['machine_type']=="เครื่องทดลอง") echo "checked=\"checked\""; ?> /> เครื่องทดลอง</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="label">ค่าเช่ารายเดือน (Excl. VAT)</div>
                            <div class="input"><input type="text" name="ipm" id="ipm" class="full" value="<?php echo $rs['ipm']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="label">วันที่เริ่มสัญญา (Commencement Date)</div>
                            <div class="input"><input type="text" name="commencement_date" id="commencement_date" class="full" readonly="readonly" value="<?php echo $rs['commencement_date']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="label">วันที่ส่งมอบเครื่อง (Installed Equipment Date)</div>
                            <div class="input"><input type="text" name="ied" id="ied" class="full" readonly="readonly" value="<?php echo $rs['ied']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="label">วันที่ระบุใบกำกับภาษี (Tax Invoice Date issued on)</div>
                            <div class="input"><input type="text" name="tidio" id="tidio" class="full" readonly="readonly" value="<?php echo $rs['tidio']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="label">วันชำระเงินงวดแรก (Date of First Installment)</div>
                            <div class="input"><input type="text" name="dofi" id="dofi" class="full" readonly="readonly" value="<?php echo $rs['dofi']; ?>" /></div>
                        </div>
                        <div class="row gray bold" style="padding-left:5px;">ผู้ค้ำประกัน</div>
                        <div class="row">
                            <div class="row">1.ชื่อผู้ค้ำประกัน</div>
                            <div class="row"><input type="text" name="bondsman1_name" id="bondsman1_name" class="full" value="<?php echo $rs['bondsman1_name']; ?>" /></div>
                            <div class="row">ที่อยู่</div>
                            <div class="row"><input type="text" name="bondsman1_address" id="bondsman1_address" class="full" value="<?php echo $rs['bondsman1_address']; ?>" /></div>
                        </div>
                        <div class="row">
                            <div class="row">2.ชื่อผู้ค้ำประกัน</div>
                            <div class="row"><input type="text" name="bondsman2_name" id="bondsman2_name" class="full" value="<?php echo $rs['bondsman2_name']; ?>" /></div>
                            <div class="row">ที่อยู่</div>
                            <div class="row"><input type="text" name="bondsman2_address" id="bondsman2_address" class="full" value="<?php echo $rs['bondsman2_address']; ?>" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box" id="block4">
                <div class="row center gray bold medium">เอกสารประกอบสินเชื่อ</div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="row under_line bold">เช่า/เช่าซื้อบุคคลธรรมดา</div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach1" value="แบบคำขอสินเชื่อ" onchange="per_chk(1,id);" <?php if(in_array("แบบคำขอสินเชื่อ",$personal_attach)) echo "checked=\"checked\""; ?> />แบบคำขอสินเชื่อ</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach2" value="หนังสือยินยอมในการเปิดเผยข้อมูล" onchange="per_chk(2,id);" <?php if(in_array("หนังสือยินยอมในการเปิดเผยข้อมูล",$personal_attach)) echo "checked=\"checked\""; ?> />หนังสือยินยอมในการเปิดเผยข้อมูล (Credit Bureau)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach3" value="สำเนาบัตรประชาชน/บัตรข้าราชการ/รัฐวิสาหกิจ" onchange="per_chk(3,id);" <?php if(in_array("สำเนาบัตรประชาชน/บัตรข้าราชการ/รัฐวิสาหกิจ",$personal_attach)) echo "checked=\"checked\""; ?> />สำเนาบัตรประชาชน/บัตรข้าราชการ/รัฐวิสาหกิจ(รับรองสำเนาถูกต้อง)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach4" value="สำเนาทะเบียนบ้าน" onchange="per_chk(4,id);" <?php if(in_array("สำเนาทะเบียนบ้าน",$personal_attach)) echo "checked=\"checked\""; ?> />สำเนาทะเบียนบ้าน (รับรองสำเนาถูกต้อง)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach5" value="หนังสือรับรองรายได้หรือสลิปเงินเดือนฉบับจริง" onchange="per_chk(5,id);" <?php if(in_array("หนังสือรับรองรายได้หรือสลิปเงินเดือนฉบับจริง",$personal_attach)) echo "checked=\"checked\""; ?> />หนังสือรับรองรายได้หรือสลิปเงินเดือนฉบับจริง ไม่เกิน 6 เดือน</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach6" value="สำเนารายการเดินบัญชีเงินฝากทุกธนาคาร" onchange="per_chk(6,id);" <?php if(in_array("สำเนารายการเดินบัญชีเงินฝากทุกธนาคาร",$personal_attach)) echo "checked=\"checked\""; ?> />สำเนารายการเดินบัญชีเงินฝากทุกธนาคาร ย้อนหลัง 6 เดือน (บัญชีส่วนตัว)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach7" value="รูปถ่ายและแผนที่" onchange="per_chk(7,id);" <?php if(in_array("รูปถ่ายและแผนที่",$personal_attach)) echo "checked=\"checked\""; ?> />รูปถ่ายและแผนที่</label></div>
                    </div>
                    <div class="inline two_col">
                        <div class="row under_line bold">เช่านิติบุคคล/เช่าซื้อนิติบุคคล</div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach1" value="แบบคำขอสินเชื่อ" onchange="cor_chk(1,id);" <?php if(in_array("แบบคำขอสินเชื่อ",$corperate_attach)) echo "checked=\"checked\""; ?> />แบบคำขอสินเชื่อ</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach2" value="หนังสือยินยอมในการเปิดเผยข้อมูล" onchange="cor_chk(2,id);" <?php if(in_array("หนังสือยินยอมในการเปิดเผยข้อมูล",$corperate_attach)) echo "checked=\"checked\""; ?> />หนังสือยินยอมในการเปิดเผยข้อมูล (Credit Bureau)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach3" value="สำเนาบัตรประชาชน และทะเบียนบ้าน ของกรรมการผู้มีอำนาจลงนาม" onchange="cor_chk(3,id);" <?php if(in_array("สำเนาบัตรประชาชน และทะเบียนบ้าน ของกรรมการผู้มีอำนาจลงนาม",$corperate_attach)) echo "checked=\"checked\""; ?> />สำเนาบัตรประชาชน และทะเบียนบ้าน ของกรรมการผู้มีอำนาจลงนาม</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach4" value="สำเนาเอกสารการจดทะเบียนบริษัท" onchange="cor_chk(4,id);" <?php if(in_array("สำเนาเอกสารการจดทะเบียนบริษัท",$corperate_attach)) echo "checked=\"checked\""; ?> />สำเนาเอกสารการจดทะเบียนบริษัท(ทะเบียนพาณิชย์/การค้า/หนังสือรับรองการจดทะเบียนนิติบุคคล ไม่เกิน 3 เดือน)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach5" value="สำเนาทะเบียนภาษีมูลค่าเพิ่ม" onchange="cor_chk(5,id);" <?php if(in_array("สำเนาทะเบียนภาษีมูลค่าเพิ่ม",$corperate_attach)) echo "checked=\"checked\""; ?> />สำเนาทะเบียนภาษีมูลค่าเพิ่ม (ภพ.20 ไม่เกิน 3 เดือน กรรมการที่มีอำนาจลงชื่อรับรองสำเนาถูกต้อง)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach6" value="หนังสือบริคณห์สนธิ" onchange="cor_chk(6,id);" <?php if(in_array("หนังสือบริคณห์สนธิ",$corperate_attach)) echo "checked=\"checked\""; ?> />หนังสือบริคณห์สนธิ (ไม่เกิน 3 เดือน)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach7" value="งบการเงินปีล่าสุด หรือสำเนารายการแสดงภาษีเงินได้ประจำปี" onchange="cor_chk(7,id);" <?php if(in_array("งบการเงินปีล่าสุด หรือสำเนารายการแสดงภาษีเงินได้ประจำปี",$corperate_attach)) echo "checked=\"checked\""; ?> />งบการเงินปีล่าสุด หรือสำเนารายการแสดงภาษีเงินได้ประจำปีปีล่าสุด ย้อนหลัง 1 ปี</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach8" value="สำเนาบัญชีธนาคารที่แสดงกระแสการเงินของบริษัท" onchange="cor_chk(8,id);" <?php if(in_array("สำเนาบัญชีธนาคารที่แสดงกระแสการเงินของบริษัท",$corperate_attach)) echo "checked=\"checked\""; ?> />สำเนาบัญชีธนาคารที่แสดงกระแสการเงินของบริษัท เช่น บัญชีเงินฝากสะสมทรัพย์ บัญชีเงินฝากกระแสรายวัน</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach9" value="วงเงินเบิกเกินบัญชี" onchange="cor_chk(9,id);" <?php if(in_array("วงเงินเบิกเกินบัญชี",$corperate_attach)) echo "checked=\"checked\""; ?> />วงเงินเบิกเกินบัญชี เป็นต้น ย้อนหลัง 6 เดือน</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach10" value="รายแบบแสดงรายการภาษีมูลค่าเพิ่ม" onchange="cor_chk(10,id);" <?php if(in_array("รายแบบแสดงรายการภาษีมูลค่าเพิ่ม",$corperate_attach)) echo "checked=\"checked\""; ?> />รายแบบแสดงรายการภาษีมูลค่าเพิ่ม (ภพ.30 ย้อนหลัง)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach11" value="รูปถ่ายและแผนที่" onchange="cor_chk(11,id);" <?php if(in_array("รูปถ่ายและแผนที่",$corperate_attach)) echo "checked=\"checked\""; ?> />รูปถ่ายและแผนที่</label></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="upload_form">
            <div class="box" id="block5">
                <div class="row center gray bold medium">ไฟล์แนบ</div>
                <div class="row" id="upload_container">
                <?php
					$path = $rs['upload_path'];
					$file = split(",",$rs['upload_file']);
					foreach($file as $val)
					{
						echo "<a href=\"".$path."/".$val."\" class=\"file_attach inline\">$val</a>";
					}
				?>
                </div>
            </div>
        </div>
        <div class="bottom_form">
            <span class="btn save" onclick="window.location.href = 'home.php';">กลับ</span>
        </div>
    </form>
</div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('input').attr('disabled','disabled');
});
</script>
</body>
</html>
<?php
}
?>