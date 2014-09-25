<?php 
//แสดงข้อมูลโดย Group ตามปีที่ทำสัญญา
include("../../../config/config.php");
include("../../function/nameMonth.php");
set_time_limit(0);

// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// ปัจจุบันในส่วนนี้ไม่อัพเดทข้อมูลตามการแก้ไขครั้งล่าสุด 2014-08-26
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//รับข้อมูล
$month = pg_escape_string($_GET["month"]); //รับเดือน
$txtmonth=nameMonthTH($month);
$year = pg_escape_string($_GET["year"]); //รับปี
$contype = pg_escape_string($_GET['contype']); //ประเภทสัญญาที่จะให้แสดง
$contypechk = explode("@",$contype);//ตัด @ ออกเพื่อเอาประเภทสัญญาที่ส่งมาวนแสดง

//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
$sendget="";
$i=0;
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($contypechk[$con]!=""){
		$i++;
		if($i==1){
			$sendget=$contypechk[$con];
		}else{
			$sendget = $sendget."@".$contypechk[$con];
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>(THCAP) รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<link href="list_tab.css" rel="stylesheet" type="text/css" />

<script language=javascript>
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&month='+'<?php echo $month;?>'+'&year='+'<?php echo $year; ?>'+'&contype='+'<?php echo $sendget;?>');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_showgroup').load('tab_showgroup.php?month='+'<?php echo $month;?>'+'&year='+'<?php echo $year; ?>'+'&contype='+'<?php echo $sendget;?>',function(){
		list_tab_menu('0');
		$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	});
});
function list_tab_menu(tab_id){
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');

	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').html('<img src="images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
	$('.list_tab_menu').load('list_tab_showgroup.php?tabid='+tab_id+'&month='+'<?php echo $month;?>'+'&year='+'<?php echo $year; ?>'+'&contype='+'<?php echo $sendget;?>');
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="padding:50px 0px 0px 0px;"></div>
<table width="1200" align="center" border="0">
<tr>
	<td>
		<table align="left" frame="box" height="50px" bgcolor="#6E7B8B"><tr><td><font size="5px" color="white">แสดงรายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือนตามปีลูกหนี้</font></td></tr></table>
	</td>
</tr>
<tr>
	<td>
		<table align="center" frame="box" width="100%" bgcolor="#A2B5CD">
			<tr>
				<td width="300" colspan="3"><font color="#BB0000">*จำนวนวันที่ค้าง คิดถึงสิ้นสุดของเดือนนั้นๆ</font></td>
				<td align="center" colspan="3">เดือน : <?php echo "$txtmonth $year";?></td>
				<td align="right"><a style="cursor:pointer;" onclick="javascript:popU('frm_pdf_year.php?month=<?php echo $month; ?>&year=<?php echo $year ; ?>&contype=<?php echo $sendget ; ?>');">
					<img src="images/pdf.png" width="20px" height="25px">
					<b><u>พิมพ์ PDF</u></b></a>
				</td>				
			</tr>
			<tr>
				<td colspan="7">
					แสดงเฉพาะ : 
						<?php 
						//แสดงประเภทสัญญา
							$qry_contype = pg_query("SELECT distinct(\"conType\") as contype FROM thcap_contract ORDER BY contype");
								$con=0;
							  while($re_contype = pg_fetch_array($qry_contype)){
								$con++;
								$contype = $re_contype['contype'];
								if($contypechk != ""){
									if(in_array($contype,$contypechk)){ $checked = "checked"; }else{ $checked = "";}
								}else{
									$checked = "checked";
								}
									echo "<input type=\"checkbox\" name=\"contype[]\" id=\"contype$con\" value=\"$contype\" $checked disabled>$contype ";
							  }			
						?>					
				</td>					
			</tr>
			<tr>
				<td colspan="7">					
					<fieldset><legend><b><u>คำอธิบาย</u></b></legend>
						<div>"ยอดสินเชื่อเริ่มแรก" : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ</div>
						<div>"เงินต้นคงเหลือ" : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก</div>
						<div>"ดอกเบี้ยรับ" : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา</div>
						<div>"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ" : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว</div>
						<div>"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน)" : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)</div>
						<div>"รวมคงเหลือที่จะต้องรับชำระ" : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)</div>
						<div>"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน)" : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)</div>
						<div>"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี)" : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)</div>
					</fieldset>
				</td>
			</tr>
		</table>	
	</td>
	
</tr>
</table>
<div id="tab_showgroup" style="width:1150px;margin:0 auto;"></div>

</body>
</html>