<?php
session_start();
require_once("../../config/config.php");
require_once("function_reminder.php");

$get_userid = $_SESSION["av_iduser"];
$focusdate = $_POST["focusdate"];

// กำหนดสี
$color_red = '#FA8072';
$color_orange = '#FF6600';
$color_green = '#228B22';
if($focusdate==""){
	$focusdate = nowDate();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['session_company_name'].' บันทึกช่วยเตือน'; ?></title>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="styles/list_tab.css" rel="stylesheet" type="text/css" />
	
<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo 'บันทึกรายการเตือน'; ?>');

});
//สร้าง tab
$(function(){
	if(document.getElementById("id").value==""){
		var tab_id = '2';//ทั้งหมด
	}
	else{
		var tab_id = document.getElementById("id").value;
	}
	$('.list_tab_menu').load('list_tabremind.php?tabid='+tab_id+'&focusdate=<?php echo $focusdate; ?>&doerid=<?php echo $get_userid; ?>');
	//ดึง tab ขึ้นมาแสดง
	$('#tab').load('tab_remind.php?focusdate=<?php echo $focusdate; ?>&doerid=<?php echo $get_userid; ?>',function(){
		list_tab_menu(tab_id,'<?php echo $focusdate; ?>','<?php echo $get_userid; ?>');
	});
});
function list_tab_menu(tab_id,focusdate,userid){	
	$('.tab.active').removeClass('active');
	$('#'+tab_id).parent().addClass('active');	
	//ให้ดึงรายการตาม tab มาแสดง
	$('.list_tab_menu').load('list_tabremind.php?tabid='+tab_id+'&focusdate='+focusdate+'&doerid='+userid);
}

$(document).ready(function(){
	$("#focusdate").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#data3").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#expiredate").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
});

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	color: #3A3A3A;
}
H1 {
    font-size: 18px;
}
.title {
    text-align: center;
}
.TextTitle{
    color: #006600;
    font-size: 11px;
    font-weight: bold;
}
</style>

</head>
<body>

	<div class="title_top">บันทึกการเตือน</div>
	<?php
		if(empty($get_userid)){
			echo "<div align=center>ผิดผลาด ไม่พบข้อมูลผู้ใช้งาน</div>";
		exit;
		}
	?>
	<div id="tabs"> <!-- เริ่ม tabs -->
		<div id="tabs-<?php echo $get_idno; ?>">
			<div style="background-color:<?php echo $bgcolor; ?>">
				<fieldset><legend><b>เพิ่มข้อมูลที่ต้องการจะเตือน</b></legend>
					<div style="padding-top:5px;">
						<form name="frm_reminder" method="post" action="save_reminder.php">
							<input type="radio" name="addtype" value="1" checked>เลือกให้เตือนทุกๆวันที่ 
								<select name="data0">
									<option value="-">เลือกวันที่</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									<option value="31">31</option>
								</select>
							</br>
							<input type="radio" name="addtype" value="2">เลือกให้เตือนทุกๆวันที่เป็นวันจันทร์-วันอาทิตย์
								<select name="data1">
									<option value="-">เลือกวัน</option>
									<option value="1">วันจันทร์</option>
									<option value="2">วันอังคาร</option>
									<option value="3">วันพุธ</option>
									<option value="4">วันพฤหัสบดี</option>
									<option value="5">วันศุกร์</option>
									<option value="6">วันเสาร์</option>
									<option value="7">วันอาทิตย์</option>
								</select>
								<select name="data2">
									<option value="-">เลือกสัปดาห์</option>
									<option value="0">ทุกสัปดาห์</option>
									<option value="1">สัปดาห์ที่ 1</option>
									<option value="2">สัปดาห์ที่ 2</option>
									<option value="3">สัปดาห์ที่ 3</option>
									<option value="4">สัปดาห์ที่ 4</option>
									<option value="9">สัปดาห์สุดท้ายของเดือน</option>
								</select>
							</br>
							<input type="radio" name="addtype" value="3">เลือกให้เตือนเฉพาะวันที่
								<td>
									<input type="text" id="data3" name="data3" value="<?php echo $focusdate; ?>" size="15" readonly="true" style="text-align:center">&nbsp;
								</td>
							</br>
							<input type="radio" name="addtype" value="4">เลือกให้เตือนทุกวัน
							</br>
							<input type="checkbox" name="cb_expiredate" value="1">กำหนดวันที่สิ้นสุดการเตือน (ถ้ามี)
								<td>
									<input type="text" id="expiredate" name="expiredate" value="" size="15" readonly="true" style="text-align:center">&nbsp;
								</td>
							</br>
							<input type="checkbox" name="cb_private" value="1">เตือนเฉพาะฉัน (เฉพาะฉันเท่านั้นที่จะเห็นการเตือนนี้)
							</br>
							</br>
							<span class="TextTitle">รายละเอียด</span><br/>
							(หมายเหตุ : ระบุเลขที่สัญญาลงในข้อความระบบจะ tag เลขที่สัญญา และทำลิ้งไป "(THCAP) ตารางแสดงการผ่อนชำระ" ให้อัตโนมัติ)<br/>
							<TEXTAREA NAME="reminderdetails" ROWS="8" COLS="90"></TEXTAREA><br />
							<INPUT TYPE="submit" VALUE="  บันทึก  ">
							<INPUT TYPE="hidden" NAME="userid" VALUE="<?php echo "$get_userid"; ?>">
							<INPUT TYPE="hidden" NAME="focusdate" VALUE="<?php echo "$focusdate"; ?>">
						</form>
					</div>
				</fieldset>
				
				<?php
					// ---------------------------------------------------------------------------------------------
					// ถ้าไม่มีการกำหนดเวลามาให้ใช้เป็นวันทีปัจจุบัน
					// ---------------------------------------------------------------------------------------------
					if ($focusdate == '--' || $focusdate == '' ||$focusdate == NULL) {
						$focusdate=nowDate();
					}
					
					// ---------------------------------------------------------------------------------------------
					// +0 เพื่อแปลงเป็นตัวเลข จาก 01 จะเป็น 1 เป็นการตัด 0 ตัวหน้าออกถ้ามี
					// ---------------------------------------------------------------------------------------------
					$dayeng = date('l',strtotime($focusdate)); // วัน เช่า monday, friday
					
					// ---------------------------------------------------------------------------------------------
					// หาวันที่ว่าเป็นวันอะไร และเป็นที่เท่าไหร่ของเดือน
					// ---------------------------------------------------------------------------------------------
					if ($dayeng == 'Monday'){
						$dayth = 'วันจันทร์';
					} else if ($dayeng == 'Tuesday'){
						$dayth = 'วันอังคาร';
					} else if ($dayeng == 'Wednesday'){
						$dayth = 'วันพุธ';
					} else if ($dayeng == 'Thursday'){
						$dayth = 'วันพฤหัสบดี';
					} else if ($dayeng == 'Friday'){
						$dayth = 'วันศุกร์';
					} else if ($dayeng == 'Saturday'){
						$dayth = 'วันเสาร์';
					} else if ($dayeng == 'Sunday'){
						$dayth = 'วันอาทิตย์';
					}
					
				?>
				</br>
				<fieldset><legend><b>เลือกวันที่ที่ต้องการให้แสดงงานที่จะต้องดำเนินการ</b></legend>
					<form name="frm_date" method="post" action="index.php">
						<td>
							<input type="text" id="focusdate" name="focusdate" value="<?php echo $focusdate; ?>" size="15" readonly="true" style="text-align:center">&nbsp;
						</td>
						<INPUT TYPE="submit" VALUE="ค้นหา">
					</form>
				</fieldset>
				</br>
				<input type="hidden" name="id" id="id" value="<?php echo $tab_id; ?>">	
				<div id="tab">
				
				</div>
			</div>
		</div>
	</div>
</body>
</html>