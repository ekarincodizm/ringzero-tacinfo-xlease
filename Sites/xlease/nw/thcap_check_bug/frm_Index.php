<?php
include("../../config/config.php");

//-=========================================================================-
// 									QUERY		
//-=========================================================================-
	// - 1.ตรวจสอบการใช้เงินซ้ำในระบบเงินโอน
	$qry_check_one = pg_query("SELECT * FROM thcap_check_duplicate_use_transfermoney_data");
	$rows_check_one = pg_num_rows($qry_check_one);
	// - 2.ตรวจสอบความสอดคล้องระหว่าง thcap_temp_receipt_channel (ช่องทางการจ่าย) และ thcap_temp_receipt_otherpay (เงินที่จ่ายแต่ละอย่าง)
	$qry_check_two = pg_query("SELECT * FROM thcap_check_correct_channel_otherpay_data");
	$rows_check_two = pg_num_rows($qry_check_two);
	// - 3.ตรวจสอบเงินโอนที่ถูกใช้ไป กับการนำเงินโอนไปใช้
	$qry_check_three = pg_query("SELECT * FROM \"thcap_check_myTransferMoney_with_useTransferMoney_data\"");
	$rows_check_three = pg_num_rows($qry_check_three);
	// - 4.ตรวจสอบอัตราดอกเบี้ยที่รับชำระในขณะนั้นๆของเงินกู้
	$qry_check_four = pg_query("SELECT * FROM \"thcap_check_interestrate_of_payloan_data\"");
	$rows_check_four = pg_num_rows($qry_check_four);
	// - 5.ตรวจสอบความต่อเนื่องของตารางคำนวณดอกเบี้ย
	$qry_check_five = pg_query("SELECT \"contractID\" FROM \"thcap_check_integrity_tableOfInt_data\" GROUP BY \"contractID\" ");
	$rows_check_five = pg_num_rows($qry_check_five);
	// - 6.ตรวจสอบการสร้างใบกำกับภาษีในกำหนดชำระที่ผ่านมาแล้วว่าออกครบหรือไม่
	$qry_check_six = pg_query("SELECT \"contractID\" FROM \"thcap_check_hp_vat_gen_correction_data\" GROUP BY \"contractID\" ");
	$rows_check_six = pg_num_rows($qry_check_six);
	// - 7.ตรวจสอบเรื่องเงินค้ำประกันว่าเป็นวันที่ที่ถูกต้องหรือไม่
	$qry_check_seven = pg_query("SELECT * FROM \"thcap_check_guaranteed_money_date\" ");
	$rows_check_seven = pg_num_rows($qry_check_seven);
	// - 8.ตรวจสอบรายการ Bill Payment และความสอดคล้องกับข้อมูลการโอนเงิน
	$qry_check_eight = pg_query("SELECT * FROM \"thcap_check_billpayment_with_transferpayment_date\" ");
	$rows_check_eight = pg_num_rows($qry_check_eight);
	// - 9.ตรวจสอบเลขที่สัญญาไม่ได้ใช้-ข้าม ในระบบ
	$qry_check_nine = pg_query("SELECT * FROM \"thcap_check_integrity_contractid_data\" ");
	$rows_check_nine = pg_num_rows($qry_check_nine);
	// - 10.ตรวจสอบการสร้างตารางลดต้นลดดอกของสัญญาเช่า และเช่าซื้อ และตั๋วเงิน
	$qry_check_ten = pg_query("SELECT * FROM \"thcap_check_leasing_gen_effectivetable_data\" ");
	$rows_check_ten = pg_num_rows($qry_check_ten);
	// - 11.ตรวจสอบความถูกต้องของข้อมูล NCB
	$qry_check_eleven = pg_query("SELECT * FROM \"thcap_check_integrity_ncb_data\" ");
	$rows_check_eleven = pg_num_rows($qry_check_eleven);
	// - 12.ตรวจสอบความถูกต้องของรายการเดินบัญชีธนาคาร
	$qry_check_twelve = pg_query("SELECT * FROM \"thcap_check_statement_bank_data\" ");
	$rows_check_twelve = pg_num_rows($qry_check_twelve);
	// - 13.ตรวจสอบการ run process auto ในระบบ
		// ต่อ base ที่ตรวจสอบ
		$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=postgres user=postgres password=". $_SESSION["session_company_dbpass"] ."";
		$db_connect = pg_connect($conn_string) or die("Can't Connect !");
		// ถึงจำนวนข้อมูล
		$qry_check_thirteen = pg_query("SELECT * FROM \"check_process_job_data\" ");
		$rows_check_thirteen = pg_num_rows($qry_check_thirteen);
		// กลับมาต่อ base หลักเหมือนเดิม
		$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
		$db_connect = pg_connect($conn_string) or die("Can't Connect !");
	// - 14.ตรวจสอบตารางการจ่ายค่างวด วันที่สิ้นสุดสัญญา และตารางลูกหนี้
	$qry_check_fourteen = pg_query("SELECT * FROM \"thcap_check_payterm_correction_data\" ");
	$rows_check_fourteen = pg_num_rows($qry_check_fourteen);
	
	// - 15. ตรวจสอบความสอดคล้องของยอดเงิน Debit / Credit ทางบัญชี
	$qry_check_fifteen = pg_query("SELECT * FROM \"thcap_check_acc_debit_credit_amt_data\" ");
	$rows_check_fifteen = pg_num_rows($qry_check_fifteen);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) ตรวจสอบรายการผิดปกติในระบบ</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function windowpop(number,width,height){
	 newWindow = window.open("frm_check_"+number+".php","","toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width="+width+",height="+height);
}

function OnMouseIn(elem) {
     elem.style.border = "1px solid #FF9900";
	 elem.style.backgroundColor = "#FFFFCC";
}
function OnMouseOut(elem) {
     elem.style.border = "1px dashed #969696";
	 elem.style.backgroundColor = "";
}
</script>
<style type="text/css">
BODY{
    font-family: tahoma;
    font-size: 14px;
    color: #585858;
    background-color: #C0C0C0;
    margin: 0 auto;
    padding-top: 20px;
}
H2{
    font-size: 20px;
    color: #A0522D;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
INPUT {
    font-family: tahoma;
    font-size: 14px;
    font-weight: normal;
}
HR {
    border: 0;
    color: #ACACAC;
    background-color: #ACACAC;
    height: 1px;
}

.roundedcornr_box {
   background: #ffffff;
   width: 900px;
   margin: auto;
}
.roundedcornr_top div {
   background: url(../../img/roundedcornr_tl.png) no-repeat top left;
}
.roundedcornr_top {
   background: url(../../img/roundedcornr_tr.png) no-repeat top right;
}
.roundedcornr_bottom div {
   background: url(../../img/roundedcornr_bl.png) no-repeat bottom left;
}
.roundedcornr_bottom {
   background: url(../../img/roundedcornr_br.png) no-repeat bottom right;
}

.roundedcornr_top div, .roundedcornr_top, 
.roundedcornr_bottom div, .roundedcornr_bottom {
   width: 100%;
   height: 15px;
   font-size: 1px;
}
.roundedcornr_content {
    margin: 0 15px;
}

</style>
</head>
<body>
<div class="roundedcornr_box">
	<div class="roundedcornr_top"><div></div></div>
		<div class="roundedcornr_content">
			<div align="center" >
				<div style="width:700px; display:block;" align="left" >
					<h2>(THCAP) ตรวจสอบรายการผิดปกติในระบบ</h2>
					<hr />
						<!-- ตรวจสอบการใช้เงินซ้ำในระบบเงินโอน -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(1,700,600);" >
									<div style="float:left"><b>1. การใช้เงินซ้ำในระบบเงินโอน</b></div>
									<div style="float:right"><font color="red"><b><?php echo number_format($rows_check_one); ?></b></font></div>
									<div style="clear:both;"></div>	
									<div>
										<span id="space1" ></span>
									</div>
								</fieldset>	
							
						<!-- จบตรวจสอบการใช้เงินซ้ำในระบบเงินโอน -->
						
						<!-- ตรวจสอบความสอดคล้องระหว่าง ช่องทางการจ่าย และ เงินที่จ่ายแต่ละอย่าง -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(2,700,600);" >
									<div style="float:left"><b>2. ความสอดคล้องระหว่าง ช่องทางการจ่าย และ เงินที่จ่ายแต่ละอย่าง</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_two); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space2" ></span>
									</div>
								</fieldset>	
							
						<!-- จบตรวจสอบความสอดคล้องระหว่าง ช่องทางการจ่าย และ เงินที่จ่ายแต่ละอย่าง -->
						
						<!-- ตรวจสอบเงินโอนที่ถูกใช้ไป กับการนำเงินโอนไปใช้ -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(3,700,600);" >
									<div style="float:left"><b>3. ตรวจสอบเงินโอนที่ถูกใช้ไป กับการนำเงินโอนไปใช้ผิด</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_three); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space3" ></span>
									</div>
								</fieldset>	
							
						<!-- จบตรวจสอบเงินโอนที่ถูกใช้ไป กับการนำเงินโอนไปใช้ -->
						
						<!-- ตรวจสอบอัตราดอกเบี้ยที่รับชำระในขณะนั้นๆของเงินกู้ -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(4,700,600);" >
									<div style="float:left"><b>4. ตรวจสอบอัตราดอกเบี้ยที่รับชำระในขณะนั้นๆของเงินกู้</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_four); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space4" ></span>
									</div>
								</fieldset>	
							
						<!-- จบตรวจสอบอัตราดอกเบี้ยที่รับชำระในขณะนั้นๆของเงินกู้ -->
						
						<!-- ตรวจสอบความต่อเนื่องของตารางคำนวณดอกเบี้ย -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(5,1250,600);" >
									<div style="float:left"><b>5. ตรวจสอบความต่อเนื่องของตารางคำนวณดอกเบี้ย</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_five); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space5" ></span>
									</div>
								</fieldset>	
							
						<!-- จบตรวจสอบความต่อเนื่องของตารางคำนวณดอกเบี้ย -->
						
						<!-- ตรวจสอบการสร้างใบกำกับภาษีในกำหนดชำระที่ผ่านมาแล้วว่าออกครบหรือไม่ -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(6,1050,600);" >
									<div style="float:left"><b>6. ตรวจสอบการสร้างใบกำกับภาษีในกำหนดชำระที่ผ่านมาแล้วว่าออกครบหรือไม่</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_six); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space6" ></span>
									</div>
								</fieldset>	
							
						<!-- จบตรวจสอบการสร้างใบกำกับภาษีในกำหนดชำระที่ผ่านมาแล้วว่าออกครบหรือไม่ -->
	
						<!-- ตรวจสอบเรื่องเงินค้ำประกันว่าเป็นวันที่ที่ถูกต้องหรือไม่ -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(7,1050,600);" >
									<div style="float:left"><b>7. ตรวจสอบเรื่องเงินค้ำประกันว่าเป็นวันที่ที่ถูกต้องหรือไม่</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_seven); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space7" ></span>
									</div>
								</fieldset>
							
						<!-- จบการตรวจสอบเรื่องเงินค้ำประกันว่าเป็นวันที่ที่ถูกต้องหรือไม่ -->
						
						<!-- ตรวจสอบรายการ Bill Payment และความสอดคล้องกับข้อมูลการโอนเงิน -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(8,1050,600);" >
									<div style="float:left"><b>8. ตรวจสอบรายการ Bill Payment และความสอดคล้องกับข้อมูลการโอนเงิน</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_eight); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space8" ></span>
									</div>
								</fieldset>
						<!-- จบการตรวจสอบเรื่องเงินค้ำประกันว่าเป็นวันที่ที่ถูกต้องหรือไม่ -->
						
						<!-- ตรวจสอบเลขที่สัญญาไม่ได้ใช้-ข้าม ในระบบ -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(9,1050,600);" >
									<div style="float:left"><b>9. ตรวจสอบเลขที่สัญญาไม่ได้ใช้-ข้าม ในระบบ</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_nine); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space9" ></span>
									</div>
								</fieldset>
						<!-- จบการตรวจสอบเลขที่สัญญาไม่ได้ใช้-ข้าม ในระบบ -->
						
						<!-- ตรวจสอบการสร้างตารางลดต้นลดดอกของสัญญาเช่า และเช่าซื้อ และตั๋วเงิน -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(10,1050,600);" >
									<div style="float:left"><b>10. ตรวจสอบการสร้างตารางลดต้นลดดอกของสัญญาเช่า และเช่าซื้อ และตั๋วเงิน</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_ten); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space10" ></span>
									</div>
								</fieldset>
						<!-- จบการตรวจสอบการสร้างตารางลดต้นลดดอกของสัญญาเช่า และเช่าซื้อ และตั๋วเงิน -->
						
						<!-- ตรวจสอบความถูกต้องของข้อมูล NCB -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(11,1050,600);" >
									<div style="float:left"><b>11. ตรวจสอบความถูกต้องของข้อมูล NCB</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_eleven); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space11" ></span>
									</div>
								</fieldset>
						<!-- จบการตรวจสอบความถูกต้องของข้อมูล NCB -->
						
						<!-- ตรวจสอบความถูกต้องของรายการเดินบัญชีธนาคาร -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(12,1050,600);" >
									<div style="float:left"><b>12. ตรวจสอบความถูกต้องของรายการเดินบัญชีธนาคาร</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_twelve); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space12" ></span>
									</div>
								</fieldset>
						<!-- จบการตรวจสอบความถูกต้องของรายการเดินบัญชีธนาคาร -->
						
						<!-- ตรวจสอบการ run process auto ในระบบ -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(13,1050,600);" >
									<div style="float:left"><b>13. ตรวจสอบการ run process auto ในระบบ</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_thirteen); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space13" ></span>
									</div>
								</fieldset>
						<!-- ตรวจสอบการ run process auto ในระบบ -->
						
						<!-- ตรวจสอบความสอดคล้องของยอดเงิน Debit / Credit ทางบัญชี -->
							<div style="padding-top:5px;"></div>
								<fieldset Title="คลิกเพื่อดูข้อมูล" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px;width:98%;cursor:pointer;"  onmouseout="OnMouseOut(this);" onmouseover="OnMouseIn(this);" onclick="windowpop(15,1050,600);" >
									<div style="float:left"><b>15. ตรวจสอบความสอดคล้องของยอดเงิน Debit / Credit ทางบัญชี</b></div>
									<div style="float:right">
												<font color="red"><b><?php echo number_format($rows_check_fifteen); ?></b></font>
												<img src="">
									</div>
									<div style="clear:both;"></div>
									<div>
										<span id="space13" ></span>
									</div>
								</fieldset>
						<!-- ตรวจสอบความสอดคล้องของยอดเงิน Debit / Credit ทางบัญชี -->
				</div>
				<div style="padding-top:50px;"></div>	
				<div style="width:400px; display:block;" align="center">					
						<div><input type="button" value=" ปิด " onclick="window.close();" style="width:120px;height:35px;"></div>
				</div>
			</div>
		</div>
	<div class="roundedcornr_bottom"><div></div></div>	
</div>		
</body>
</html>