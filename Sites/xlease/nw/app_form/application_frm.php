<?php
include("config/config.php");
if($_SESSION['app_username']=="")
{
	echo "<script type=\"text/javascript\">window.location.href = 'index.php';</script>";
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แบบฟอร์มขอสินเชื่อ</title>
<link href="css/form.css" rel="stylesheet" type="text/css" />
<link href="css/flick/jquery-ui-1.9.0.custom.min.css" rel="stylesheet" type="text/css" />
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
                        <div class="input"><input type="text" name="company_name" id="company_name" class="full" /></div>
                    </div>
                    <div class="inline two_col_short">
                        <div class="label">วันที่ (date)</div>
                        <div class="input"><input type="text" name="date" id="date" class="full" readonly="readonly" /></div>
                    </div>
                </div>
                <div class="row">
                    <div class="label">ชื่อกรรมการที่ลงนาม (Name of Director Authorized)</div>
                    <div class="input"><input type="text" name="director_name" id="director_name" class="full" /></div>
                </div>
                <div class="row">
                    <div class="label">ที่อยู่ตามหนังสือรับรอง (Address of Company)</div>
                    <div class="input"><input type="text" name="company_address" id="company_address" class="full" /></div>
                </div>
                <div class="row">
                    <div class="label">ที่อยู่ที่ออกใบกำกับภาษี (Place of VAT Issue)</div>
                    <div class="input"><input type="text" name="vat_place" id="vat_place" class="full" /></div>
                </div>
                <div class="row">
                    <div class="label">ที่ตั้งเครื่อง (Installed Equipment Place)</div>
                    <div class="input"><input type="text" name="equipment_place" id="equipment_place" class="full" /></div>
                </div>
                <div class="row">
                    <div class="label">ชื่อลูกค้าที่ติดต่อ (Customer's name)</div>
                    <div class="input"><input type="text" name="customer_name" id="customer_name" class="full" /></div>
                </div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="label">หมายเลขโทรศัพท์ (Tel.)</div>
                        <div class="input"><input type="text" name="telephone" id="telephone" class="full" /></div>
                    </div>
                    <div class="inline two_col">
                        <div class="label">แฟกซ์ (Fax.)</div>
                        <div class="input"><input type="text" name="fax" id="fax" class="full" /></div>
                    </div>
                </div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="label">เคยเป็นลูกค้าของผู้ขายหรือไม่</div>
                        <div class="input">
                            <label class="inline"><input type="radio" name="dealer_customer" id="dealer_customer1" value="เคย" onchange="dealer();" /> เคย/Yes</label>
                            <label class="inline"><input type="radio" name="dealer_customer" id="dealer_customer2" value="ไม่เคย" onchange="dealer();" /> ไม่เคย/No</label>
                        </div>
                    </div>
                    <div class="inline two_col">
                        <div class="label">ประวัติการชำระเงิน</div>
                        <div class="input">
                            <label class="inline"><input type="radio" name="pay_history" id="pay_history1" value="ดี" disabled="disabled" /> ดี/Good</label>
                            <label class="inline"><input type="radio" name="pay_history" id="pay_history2" value="ล่าช้าบ้าง" disabled="disabled" /> ล่าช้าบ้าง/Some Delay</label>
                            <label class="inline"><input type="radio" name="pay_history" id="pay_history3" value="ล่าช้า" disabled="disabled" /> ล่าช้า/Delay</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="label">ระยะเวลาที่ได้เป็นลูกค้า</div>
                        <div class="input"><input type="text" name="customer_time" id="customer_time" class="full" disabled="disabled" /></div>
                    </div>
                    <div class="inline two_col">
                        <div class="label">จำนวนเครื่องถ่ายเอกสารที่มีอยู่</div>
                        <div class="input"><input type="text" name="all_cpm" id="all_cpm" class="full" disabled="disabled" onblur="chk_num(this);" /></div>
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
                    <div class="row right cust_h">เงื่อนไขต่างๆ (Other Condition)</div>
                </div>
                <div class="inline three_col">
                    <div class="row"><input type="text" name="bill_condition" id="bill_condition" class="full" /></div>
                </div>
                <div class="inline three_col">
                    <div class="row"><input type="text" name="cheque_condition" id="cheque_condition" class="full" /></div>
                </div>
            </div>
            <div class="box" id="block3">
                <div class="row">
                    <div class="inline two_col">
                        <div class="row gray bold under_line" style="padding-left:5px;">รายละเอียดทรัพย์สิน/Detail of Assets</div>
                        <div class="row">
                            <div class="label">เงินดาวน์ (Down Payment):</div>
                            <div class="input"><input type="text" name="down_payment" id="down_payment" class="full" onblur="chk_num(this);" /></div>
                        </div>
                        <div class="row">
                            <div class="label">ยอดจัด (Financial Amount : Excluding VAT):</div>
                            <div class="input"><input type="text" name="financial_amount" id="financial_amount" class="full" onblur="chk_num(this);" /></div>
                        </div>
                        <div class="row">
                            <div class="label">ระยะเวลา/เดือน (Term/Months)</div>
                            <div class="input"><input type="text" name="term_years" id="term_years" class="full" onblur="chk_num(this);" /></div>
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
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name1" class="tb_full" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu1" class="tb_full" onblur="sumppu();" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity1" class="tb_full" onblur="sumqtt();" /></div>
                            </div>
                            <div class="table_row">
                                <div class="inline id center">2</div>
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name2" class="tb_full" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu2" class="tb_full" onblur="sumppu();" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity2" class="tb_full" onblur="sumqtt();" /></div>
                            </div>
                            <div class="table_row">
                                <div class="inline id center">3</div>
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name3" class="tb_full" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu3" class="tb_full" onblur="sumppu();" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity3" class="tb_full" onblur="sumqtt();" /></div>
                            </div>
                            <div class="table_row">
                                <div class="inline id center">4</div>
                                <div class="inline model_name"><input type="text" name="model_name[]" id="model_name4" class="tb_full" /></div>
                                <div class="inline ppu"><input type="text" name="ppu[]" id="ppu4" class="tb_full" onblur="sumppu();" /></div>
                                <div class="inline quantity"><input type="text" name="quantity[]" id="quantity4" class="tb_full" onblur="sumqtt();" /></div>
                            </div>
                            <div class="table_row tboth">
                                <div class="inline total center">TOTAL</div>
                                <div class="inline ppu"><input type="text" name="pputotal" id="pputotal" class="tb_full" readonly="readonly" /></div>
                                <div class="inline quantity"><input type="text" name="quantitytotal" id="quantitytotal" class="tb_full" readonly="readonly" /></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="label">ผู้ถือทรัพย์สินเมื่อหมดสัญญา</div>
                            <div class="input">
                                <label class="inline"><input type="radio" name="owner" id="owner2" value="THCAP" /> THCAP</label>
                                <label class="inline"><input type="radio" name="owner" id="owner1" value="ผู้ขาย" /> ผู้ขาย</label>
                                <label class="inline"><input type="radio" name="owner" id="owner3" value="ลูกค้า" /> ลูกค้า</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="label">วิธีการขำระเงิน</div>
                            <div class="input"><input type="text" name="payment_step" id="payment_step" class="full" /></div>
                        </div>
                        <div class="row">
                            <div class="label">มูลค่าซาก (%)</div>
                            <div class="input"><input type="text" name="remainder_price" id="remainder_price" class="full" onblur="chk_num(this);" /></div>
                        </div>
                    </div>
                    <div class="inline two_col">
                        <div class="row">
                            <div class="label">ประเภทเครื่อง</div>
                            <div class="input">
                                <label class="inline"><input type="radio" name="machine_type" id="machine_type1" value="เครื่องมือหนึ่ง" /> เครื่องมือหนึ่ง</label>
                                <label class="inline"><input type="radio" name="machine_type" id="machine_type2" value="เครื่องมือสอง" /> เครื่องมือสอง</label>
                                <label class="inline"><input type="radio" name="machine_type" id="machine_type3" value="เครื่องทดลอง" /> เครื่องทดลอง</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="label">ค่าเช่ารายเดือน (Excl. VAT)</div>
                            <div class="input"><input type="text" name="ipm" id="ipm" class="full" onblur="chk_num(this);" /></div>
                        </div>
                        <div class="row">
                            <div class="label">วันที่เริ่มสัญญา (Commencement Date)</div>
                            <div class="input"><input type="text" name="commencement_date" id="commencement_date" class="full" readonly="readonly" /></div>
                        </div>
                        <div class="row">
                            <div class="label">วันที่ส่งมอบเครื่อง (Installed Equipment Date)</div>
                            <div class="input"><input type="text" name="ied" id="ied" class="full" readonly="readonly" /></div>
                        </div>
                        <div class="row">
                            <div class="label">วันชำระเงินงวดแรก (Date of First Installment)</div>
                            <div class="input"><input type="text" name="dofi" id="dofi" class="full" readonly="readonly" /></div>
                        </div>
                        <div class="row gray bold" style="padding-left:5px;">ผู้ค้ำประกัน</div>
                        <div class="row">
                            <div class="row">1.ชื่อผู้ค้ำประกัน</div>
                            <div class="row"><input type="text" name="bondsman1_name" id="bondsman1_name" class="full" /></div>
                            <div class="row">ที่อยู่</div>
                            <div class="row"><input type="text" name="bondsman1_address" id="bondsman1_address" class="full" /></div>
                        </div>
                        <div class="row">
                            <div class="row">2.ชื่อผู้ค้ำประกัน</div>
                            <div class="row"><input type="text" name="bondsman2_name" id="bondsman2_name" class="full" /></div>
                            <div class="row">ที่อยู่</div>
                            <div class="row"><input type="text" name="bondsman2_address" id="bondsman2_address" class="full" /></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box" id="block4">
                <div class="row center gray bold medium">เอกสารประกอบสินเชื่อ</div>
                <div class="row">
                    <div class="inline two_col">
                        <div class="row under_line bold"><label><input type="radio" name="customer_type" id="cus_type1" value="1" onchange="pick_attach();" />เช่า/เช่าซื้อบุคคลธรรมดา</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach1" value="แบบคำขอสินเชื่อ" onchange="per_chk(1,id);" disabled="disabled" />แบบคำขอสินเชื่อ</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach2" value="หนังสือยินยอมในการเปิดเผยข้อมูล" onchange="per_chk(2,id);" disabled="disabled" />หนังสือยินยอมในการเปิดเผยข้อมูล (Credit Bureau)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach3" value="สำเนาบัตรประชาชน/บัตรข้าราชการ/รัฐวิสาหกิจ" onchange="per_chk(3,id);" disabled="disabled" />สำเนาบัตรประชาชน/บัตรข้าราชการ/รัฐวิสาหกิจ(รับรองสำเนาถูกต้อง)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach4" value="สำเนาทะเบียนบ้าน" onchange="per_chk(4,id);" disabled="disabled" />สำเนาทะเบียนบ้าน (รับรองสำเนาถูกต้อง)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach5" value="หนังสือรับรองรายได้หรือสลิปเงินเดือนฉบับจริง" onchange="per_chk(5,id);" disabled="disabled" />หนังสือรับรองรายได้หรือสลิปเงินเดือนฉบับจริง ไม่เกิน 6 เดือน</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach6" value="สำเนารายการเดินบัญชีเงินฝากทุกธนาคาร" onchange="per_chk(6,id);" disabled="disabled" />สำเนารายการเดินบัญชีเงินฝากทุกธนาคาร ย้อนหลัง 6 เดือน (บัญชีส่วนตัว)</label></div>
                        <div class="row"><label><input type="checkbox" name="personal_attach[]" id="personal_attach7" value="รูปถ่ายและแผนที่" onchange="per_chk(7,id);" disabled="disabled" />รูปถ่ายและแผนที่</label></div>
                    </div>
                    <div class="inline two_col">
                        <div class="row under_line bold"><label><input type="radio" name="customer_type" id="cus_type2" value="2" onchange="pick_attach();" />เช่านิติบุคคล/เช่าซื้อนิติบุคคล</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach1" value="แบบคำขอสินเชื่อ" onchange="cor_chk(1,id);" disabled="disabled" />แบบคำขอสินเชื่อ</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach2" value="หนังสือยินยอมในการเปิดเผยข้อมูล" onchange="cor_chk(2,id);" disabled="disabled" />หนังสือยินยอมในการเปิดเผยข้อมูล (Credit Bureau)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach3" value="สำเนาบัตรประชาชน และทะเบียนบ้าน ของกรรมการผู้มีอำนาจลงนาม" onchange="cor_chk(3,id);" disabled="disabled" />สำเนาบัตรประชาชน และทะเบียนบ้าน ของกรรมการผู้มีอำนาจลงนาม</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach4" value="สำเนาเอกสารการจดทะเบียนบริษัท" onchange="cor_chk(4,id);" disabled="disabled" />สำเนาเอกสารการจดทะเบียนบริษัท(ทะเบียนพาณิชย์/การค้า/หนังสือรับรองการจดทะเบียนนิติบุคคล ไม่เกิน 3 เดือน)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach5" value="สำเนาทะเบียนภาษีมูลค่าเพิ่ม" onchange="cor_chk(5,id);" disabled="disabled" />สำเนาทะเบียนภาษีมูลค่าเพิ่ม (ภพ.20 ไม่เกิน 3 เดือน กรรมการที่มีอำนาจลงชื่อรับรองสำเนาถูกต้อง)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach6" value="หนังสือบริคณห์สนธิ" onchange="cor_chk(6,id);" disabled="disabled" />หนังสือบริคณห์สนธิ (ไม่เกิน 3 เดือน)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach7" value="งบการเงินปีล่าสุด หรือสำเนารายการแสดงภาษีเงินได้ประจำปี" onchange="cor_chk(7,id);" disabled="disabled" />งบการเงินปีล่าสุด หรือสำเนารายการแสดงภาษีเงินได้ประจำปีปีล่าสุด ย้อนหลัง 1 ปี</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach8" value="สำเนาบัญชีธนาคารที่แสดงกระแสการเงินของบริษัท" onchange="cor_chk(8,id);" disabled="disabled" />สำเนาบัญชีธนาคารที่แสดงกระแสการเงินของบริษัท เช่น บัญชีเงินฝากสะสมทรัพย์ บัญชีเงินฝากกระแสรายวัน</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach9" value="วงเงินเบิกเกินบัญชี" onchange="cor_chk(9,id);" disabled="disabled" />วงเงินเบิกเกินบัญชี เป็นต้น ย้อนหลัง 6 เดือน</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach10" value="รายแบบแสดงรายการภาษีมูลค่าเพิ่ม" onchange="cor_chk(10,id);" disabled="disabled" />รายแบบแสดงรายการภาษีมูลค่าเพิ่ม (ภพ.30 ย้อนหลัง)</label></div>
                        <div class="row"><label><input type="checkbox" name="corperate_attach[]" id="corperate_attach11" value="รูปถ่ายและแผนที่" onchange="cor_chk(11,id);" disabled="disabled" />รูปถ่ายและแผนที่</label></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="upload_form">
            <div class="box" id="block5">
                <div class="row center gray bold medium">อัพโหลดเอกสาร</div>
                <div class="row" id="upload_container">
                    <div class="row solid_top upload_box" id="perdoc1">
                        <div class="box_label bold big">แบบคำขอสินเชื่อ</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="remove_file(this);">x</span></div>
                        <input type="file" name="personal_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="perdoc2">
                        <div class="box_label bold big">หนังสือยินยอมในการเปิดเผยข้อมูล</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="personal_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="perdoc3">
                        <div class="box_label bold big">สำเนาบัตรประชาชน/บัตรข้าราชการ/รัฐวิสาหกิจ</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="personal_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="perdoc4">
                        <div class="box_label bold big">สำเนาทะเบียนบ้าน</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="personal_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="perdoc5">
                        <div class="box_label bold big">หนังสือรับรองรายได้หรือสลิปเงินเดือนฉบับจริง</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="personal_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="perdoc6">
                        <div class="box_label bold big">สำเนารายการเดินบัญชีเงินฝากทุกธนาคาร</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="personal_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="perdoc7">
                        <div class="box_label bold big">รูปถ่ายและแผนที่</div>
                        <div class="multiupload">
                            <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="remove_file(this)">x</span></div>
                            <input type="file" name="personal_doc[]" onchange="per_multi_upload(this);" />
                            <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                        </div>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc1">
                        <div class="box_label bold big">แบบคำขอสินเชื่อ</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc2">
                        <div class="box_label bold big">หนังสือยินยอมในการเปิดเผยข้อมูล</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc3">
                        <div class="box_label bold big">สำเนาบัตรประชาชน และทะเบียนบ้าน ของกรรมการผู้มีอำนาจลงนาม</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc4">
                        <div class="box_label bold big">สำเนาเอกสารการจดทะเบียนบริษัท</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc5">
                        <div class="box_label bold big">สำเนาทะเบียนภาษีมูลค่าเพิ่ม(ภพ.20)</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc6">
                        <div class="box_label bold big">หนังสือบริคณห์สนธิ </div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc7">
                        <div class="box_label bold big">งบการเงินปีล่าสุด หรือสำเนารายการแสดงภาษีเงินได้ประจำปีปีล่าสุด</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc8">
                        <div class="box_label bold big">สำเนาบัญชีธนาคารที่แสดงกระแสการเงินของบริษัท</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc9">
                        <div class="box_label bold big">วงเงินเบิกเกินบัญชี ย้อนหลัง 6 เดือน</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc10">
                        <div class="box_label bold big">รายแบบแสดงรายการภาษีมูลค่าเพิ่ม(ภพ.30)</div>
                        <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="clear_file(this);">x</span></div>
                        <input type="file" name="corperate_doc[]" onchange="single_upload(this);" />
                        <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                    </div>
                    <div class="row solid_top upload_box" id="cordoc11">
                        <div class="box_label bold big">รูปถ่ายและแผนที่</div>
                        <div class="multiupload">
                            <div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="remove_file(this)">x</span></div>
                            <input type="file" name="corperate_doc[]" onchange="cor_multi_upload(this);" />
                            <span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom_form">
            <span class="btn save" onclick="save();">บันทึก</span>
        </div>
    </form>
	</div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.9.0.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#date,#bill_date,#cheque_date,#commencement_date,#ied,#tidio,#dofi').datepicker();
});
function per_chk(opt,id) {
	var chk_state = $('#'+id).is(':checked');
	if(chk_state==true)
	{
		$('#perdoc'+opt).show();
	}
	else
	{
		$('#perdoc'+opt).find('.file_label').find('.file_name').html('');
		$('#perdoc'+opt).find('.file_label').hide();
		$('#perdoc'+opt).find('input[type="file"]').val('');
		$('#perdoc'+opt).hide();
	}
}
function cor_chk(opt,id) {
	var chk_state = $('#'+id).is(':checked');
	if(chk_state==true)
	{
		$('#cordoc'+opt).show();
	}
	else
	{
		$('#cordoc'+opt).hide();
	}
}
function browse_file(elem) {
	$(elem).parent().find('input[type="file"]').click();
}
function single_upload(elem) {
	var box = $(elem).parent().find('.file_label');
	var label = $(box).find('.file_name');
	var val = $(elem).val();
	if(val!='')
	{
		$(label).html(val);
		$(box).show();
	}
	else
	{
		$(label).html('');
		$(box).hide();
	}
}
function per_multi_upload(elem) {
	var box = $(elem).parent().find('.file_label');
	var label = $(box).find('.file_name');
	var val = $(elem).val();
	if(val!='')
	{
		var upload_btn = $(elem).parent().find('.upload');
		if($(upload_btn).length>=1)
		{
			$(elem).parent().find('.upload').remove();
			$(label).html(val);
			$(box).show();
			$(elem).parent().after('<div class="multiupload"><div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="remove_file(this)">x</span></div><input type="file" name="personal_doc[]" onchange="per_multi_upload(this);" /><span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span></div>');
		}
		else
		{
			$(label).html(val);
		}
	}
	else
	{
		$(elem).parent().remove();
	}
}
function cor_multi_upload(elem) {
	var box = $(elem).parent().find('.file_label');
	var label = $(box).find('.file_name');
	var val = $(elem).val();
	if(val!='')
	{
		var upload_btn = $(elem).parent().find('.upload');
		if($(upload_btn).length>=1)
		{
			$(elem).parent().find('.upload').remove();
			$(label).html(val);
			$(box).show();
			$(elem).parent().after('<div class="multiupload"><div class="file_label"><span class="under_line file_name" onclick="browse_file($(this).parent());"></span><span class="close" onclick="remove_file(this)">x</span></div><input type="file" name="corperate_doc[]" onchange="cor_multi_upload(this);" /><span class="btn upload" onclick="browse_file(this);">เลือกไฟล์</span></div>');
		}
		else
		{
			$(label).html(val);
		}
	}
	else
	{
		$(elem).parent().remove();
	}
}
function clear_file(elem) {
	$(elem).parent().parent().find('input[type="file"]').val('');
	$(elem).parent().find('.file_name').html('');
	$(elem).parent().hide();
}
function remove_file(elem) {
	$(elem).parent().parent().remove();
}
function dealer() {
	var dealer = $('input[name="dealer_customer"]:checked').val();
	if(dealer=='เคย')
	{
		$('input[name="pay_history"]').removeAttr('disabled');
		$('#customer_time').removeAttr('disabled');
		$('#all_cpm').removeAttr('disabled');
	}
	else
	{
		$('input[name="pay_history"]').removeAttr('checked');
		$('input[name="pay_history"]').attr('disabled','disabled');
		$('#customer_time').val('');
		$('#all_cpm').val('');
		$('#customer_time').attr('disabled','disabled');
		$('#all_cpm').attr('disabled','disabled');
	}
}
function pick_attach() {
	var cus_type = $('input[name="customer_type"]:checked').val();
	if(cus_type=='1')
	{
		$('input[name="corperate_attach[]"]').removeAttr('checked');
		$('input[name="corperate_attach[]"]').attr('disabled','disabled');
		$('input[name="personal_attach[]"]').removeAttr('disabled');
	}
	else
	{
		$('input[name="personal_attach[]"]').removeAttr('checked');
		$('input[name="personal_attach[]"]').attr('disabled','disabled');
		$('input[name="corperate_attach[]"]').removeAttr('disabled');
	}
}
function check_val(val) {
	if(val=='')
	{
		val = 0;
		return val;
	}
	else
	{
		return parseInt(val);
	}
}
function sumppu() {
	var ppu1 = check_val($('#ppu1').val());
	var ppu2 = check_val($('#ppu2').val());
	var ppu3 = check_val($('#ppu3').val());
	var ppu4 = check_val($('#ppu4').val());
	
	if($.isNumeric(ppu1)!=false&&$.isNumeric(ppu2)!=false&&$.isNumeric(ppu3)!=false&&$.isNumeric(ppu4)!=false)
	{
		var sumppu = ppu1+ppu2+ppu3+ppu4;
		
		$('#pputotal').val(sumppu);
	}
	else
	{
		alert('กรุณาระบุข้อมูลเป็นตัวเลขเท่านั้น');
	}
}
function sumqtt() {
	var qtt1 = check_val($('#quantity1').val());
	var qtt2 = check_val($('#quantity2').val());
	var qtt3 = check_val($('#quantity3').val());
	var qtt4 = check_val($('#quantity4').val());
	
	var sumqtt = qtt1+qtt2+qtt3+qtt4;
	
	$('#quantitytotal').val(sumqtt);
}
function chk_num(elem) {
	var val = $(elem).val();
	if($.isNumeric(val)==false)
	{
		alert('กรุณาระบุข้อมูลเป็นตัวเลขเท่านั้น');
		$(elem).val('');
		//$(elem).focus();
	}
}
function save() {
	document.app_form.submit();
}
</script>
</body>
</html>
<?php
}
?>