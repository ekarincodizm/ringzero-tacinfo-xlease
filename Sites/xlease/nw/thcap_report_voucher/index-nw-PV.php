<?php 
include("../../config/config.php");
include("../../nw/function/load_purpose.php");
set_time_limit(0);

$txt_voucher = pg_escape_string($_REQUEST['s_voucher']); // เลขที่ใบสำคัญ
$s_date = pg_escape_string($_REQUEST['datepicker']); // วันที่ ที่ต้องการสืบค้นข้อมูล
$s_month = pg_escape_string($_REQUEST['month']); // เดือน ที่ต้องการสืบค้นข้อมูล
$s_year = pg_escape_string($_REQUEST['year']); // ปี ที่้ต้องการสืบค้นข้อมูล
$s_datefrom = pg_escape_string($_REQUEST['datefrom']); // วันที่เริิ่มต้น ของต้องการสืบค้นข้อมูล
$s_dateto = pg_escape_string($_REQUEST['dateto']); // วันที่สุดท้าย ของการสืบค้นข้อมูล
$s_sel_year = pg_escape_string($_REQUEST['sel_year']);//ปีที่ค้นหา
$s_valuee = pg_escape_string($_REQUEST['search_type']); // ค่าตัวแปรจากการเลือก  วิธีการสืบค้นข้อมูลในเงื่อนไขหลัก
$s_detail = pg_escape_string($_REQUEST['s_detail']); // ส่วนของ ตามรายละเอียด ที่ใช้สืบค้นข้อมูล
$s_cancel = pg_escape_string($_REQUEST['s_cancel']);  // สถานะการเลือก  "แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก "
$s_purpose_idx = pg_escape_string($_REQUEST['voucher_purpose']);  // เลขรหัสจุดประสงค์ ของใบสำคัญ
$s_chk_detail = pg_escape_string($_REQUEST['chk_s_detail']); // สถานะการเลือก  "ตามรายละเอียด "
$s_chk_purpose = pg_escape_string($_REQUEST['chk_voucher_purpose']); // สถานะการเลือก "จุดประสงค์"
$s_data = pg_escape_string($_REQUEST['s_data']); // กดปุ่มค้นหา ถ้ากดจะเป็น yes

if($s_valuee == ""){$s_valuee = "1";}
if($s_year == ""){$s_year = date('Y');}
if($s_sel_year == ""){$s_sel_year = date('Y');}

$s_cancel = "on"; // แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก :: ให้แสดงเสมอ

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานใบสำคัญ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		function load_voucher(voucherType)
		{
			if(voucherType == 'RV') // ใบสำคัญรับ
			{
				frm_search_RV.submit();
			}
			else if(voucherType == 'JV') // ใบสำคัญรายวันทั่วไป
			{
				frm_search_JV.submit();
			}
		}
	</script>
</head>
<body>
	<div style="margin-top:10px;" align="center"><h1>(THCAP) รายงานใบสำคัญ</h1></div>
	<div style="margin-top:10px; width:60%;margin-left:auto;margin-right:auto;">
	
	<!-- เริ่มต้นส่วน การรับ "เงื่อนไขหลัก" เพื่อการสืบค้นข่้อมูล -->
	<form name="frm_search_PV" method="post" action="index-nw-PV.php">
	<fieldset><legend>เงือนไขการค้นหาหลัก</legend>
		<table align="center" cellspacing="10px">
			
			<tr>
				<td><input type="radio" id="search_voucher" name="search_type"  value="0" <?php if($s_valuee == "0"){echo "checked";} ?> /><!-- เลือกการค้นหา โดย ระบุ "Voucher ID" (เเลขที่ใบสำคัญ) --></td>
				<td><b>ค้นหา Voucher ID:</b></td>
				<td>
					<input type="text" name="s_voucher" id="s_voucher" value="<?php echo $txt_voucher; ?>"><!-- นำเข้า Voucher ID -->
				</td>	
			</tr>
			<tr>
				<td>
					<input type="radio" id="date1" name="search_type"  value="1" <?php if($s_valuee == "1"){echo "checked";} ?> /><!-- เลือกการค้นหา  โดยระบุ  "วันที่ "-->
				</td>
				<td><b>ตามวันที่ :</b></td>
				<td>
					<input type="text" id="datepicker" name="datepicker" value="<?php echo $s_date; ?>" size="15" style="text-align:center">&nbsp;<!-- ตัวเลือกวันที่ -->
				</td>
			</tr>
			<tr>
				<td>
					<input type="radio" id="date2" name="search_type"  value="2" <?php if($s_valuee == "2"){echo "checked";} ?> /><!-- เลือกการค้นหา โดยระบุ  "เดือน" -->
				</td>
				<td><b>ตามเดือน:</b></td>
				<td>
					<select name="month" id="month"> <!-- "เดือน" ให้เลือก --> 
						<option value="">--เลือกเดือน--</option>
						<option value="01" <?php if($s_month=="01") echo "selected";?>>มกราคม</option>
						<option value="02" <?php if($s_month=="02") echo "selected";?>>กุมภาพันธ์</option>
						<option value="03" <?php if($s_month=="03") echo "selected";?>>มีนาคม</option>
						<option value="04" <?php if($s_month=="04") echo "selected";?>>เมษายน</option>
						<option value="05" <?php if($s_month=="05") echo "selected";?>>พฤษภาคม</option>
						<option value="06" <?php if($s_month=="06") echo "selected";?>>มิถุนายน</option>
						<option value="07" <?php if($s_month=="07") echo "selected";?>>กรกฎาคม</option>
						<option value="08" <?php if($s_month=="08") echo "selected";?>>สิงหาคม</option>
						<option value="09" <?php if($s_month=="09") echo "selected";?>>กันยายน</option>
						<option value="10" <?php if($s_month=="10") echo "selected";?>>ตุลาคม</option>
						<option value="11" <?php if($s_month=="11") echo "selected";?>>พฤศจิกายน</option>
						<option value="12" <?php if($s_month=="12") echo "selected";?>>ธันวาคม</option>
					</select>
				<b>ปี :</b>
					<select name="year" id="year"> <!-- ปีให้เลือก  --> 	

					<?php
					$datenow1 = nowDate();
					list($year,$month,$day)=explode("-",$datenow1);
					
					$yearback = $year -10;
					for($t=$yearback;$t<=$year;$t++){
					if($t == $s_year){ ?>
					<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
					<?php		}else{ ?>
					<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
					<?php  
								}
					}
					?>	
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<input type="radio" id="date3" name="search_type" value="3" <?php if($s_valuee == "3"){echo "checked";} ?> /><!-- เลือกการค้นหาโดย  "ตามช่วง" (ช่วงวันที่) -->
				</td>
				<td><b>ตามช่วง:</b></td>
				<td>
					<b>จาก</b>
					<input type="text" id="datefrom" name="datefrom" value="<?php echo $s_datefrom; ?>" size="15" style="text-align:center"><!-- นำเช้า "วันที่เริ่มต้น" -->
					<b>ถึง</b>
					<input type="text" id="dateto" name="dateto" value="<?php echo $s_dateto; ?>" size="15" style="text-align:center"><!-- นำเช้า "วันที่สิ้นสุด" -->
				</td>
			</tr>
			<!--ตามปี-->
			<tr>
				<td>
					<input type="radio" id="search_year" name="search_type" value="5" <?php if($s_valuee == "5"){echo "checked";} ?> /><!-- เลือกการค้นหาโดย  "ตามปี"  -->
				</td>
				<td><b>ตามปี:</b></td>
				<td>
					<select name="sel_year" id="sel_year"><!-- ปีที่ให้เลือก -->
					<?php $v_datenow = nowDate();
					list($v_year,$v_month,$v_day)=explode("-",$v_datenow);
					
					$yearback = $v_year -10;
					for($y=$yearback;$y<=$v_year;$y++){
						if($y == $s_sel_year){ ?> 
						<option value="<?php echo $y;?>" selected="selected"><?php echo $y; ?></option>	
						<?php		}else{ ?>
						<option value="<?php echo $y;?>" ><?php echo $y; ?></option>																
						<?php  
						}
					} 
					?>	
					</select>
				</td>
			</tr>
			
			<!--จบตามปี-->
			
			<tr>
				<td>
					<input type="radio" id="ALL" name="search_type" value="4" <?php if($s_valuee == "4"){echo "checked";} ?> /><!-- เลือกการค้นหาทั้งหมด -->
				</td>
				</td>
				<td><b>ค้นหาทั้งหมด</b></td>
				<td>
					
				</td>
			</tr>
			
		</table>
	</fieldset>
	<!-- สิ้นสุดส่วน การรับ "เงื่อนไขหลัก" เพื่อการสืบค้นข่้อมูล -->
	
	<!-- เริ่มต้นส่วน การรับ "เงื่อนไขรอง"  เพื่อการสืบค้นข้อมูล -->
	<fieldset><legend>เงือนไขการค้นหารอง</legend> 
	  <table align="center" cellspacing="10px">
	    <tr>
				<td>
					<input type="checkbox" name="chk_s_detail" id="chk_s_detail" OnClick ="javascript:Change_Element_ReadOnly_Status('chk_s_detail','s_detail');" <?php if($s_chk_detail == "on"){echo "checked";} ?> /><!-- เลือกการค้นหาแบบ "ตามรายละเอียด"   -->
				</td>
				<td><b>ตามรายละเอียด:</b></td>
				<td>
					<input type="text" name="s_detail" id="s_detail" size="70" readonly = "true" style="background:#DDDDDD;" value="<?php echo $s_detail; ?>" ><!-- นำเข้ารายละเอียด -->
				</td>
			</tr>
			
			<tr>
				<td>
					<input type="checkbox" name="chk_voucher_purpose" id="chk_voucher_purpose" OnClick ="javascript:Change_Element_Disable_Status('chk_voucher_purpose','voucher_purpose');" <?php if($s_chk_purpose == "on"){echo "checked";} ?> /><!-- เลือกการค้นหาแบบ "ตามจุดประสงค์"   -->
				</td>
				<td><b>จุดประสงค์</b></td>
				<td>
				    <?php  
					     $query_x = load_all_purpose_from_table_thcap_purpose();
						 $num_row = pg_num_rows($query_x);
                        
					?>
					<select name="voucher_purpose" id="voucher_purpose" disabled = true > <!-- เลือกจุดประสงค์ที่ต้องการ  --> 
						<option value="<?php echo "-";?>" ><?php echo "-----เลือกจุดประสงค์-----"; ?></option>	
						<?php
						for($i=0; $i<$num_row; $i++)
						{ 
							$data =  pg_fetch_array($query_x); 
						?>
						 <option value="<?php echo $data['thcap_purpose_id'];?>" <?php if($data['thcap_purpose_id'] == $s_purpose_idx){echo "selected";} ?> ><?php echo $data['thcap_purpose_name']; ?></option>	
						<?php
					   }	 
					?>	
					</select>
				</td>
			</tr>
	  </table>
	</fieldset>
	<font color="#FF0000">* วันที่หรือช่วงเวลาที่ใช้ค้นหา จะเป็น <u>วันที่ทำรายการ</u> ไม่ใช่ <u>วันที่ใบสำคัญ</u> โดยมีวัตถุประสงค์เพื่อใช้ตรวจสอบการทำงาน</font>
	<!-- สิ้นสุดส่วน การรับ "เงื่อนไขรอง"  เพื่อการสืบค้นข้อมูล -->
	
	<!--   เริ่มส่วนปุ่มคำสั่งสำหรับ  ค้นหาข้อมูลที่ให้ผู้ใช้ คลิก -->
	<table align="center" cellspacing="10px">
		<tr>
			<td colspan="3" align="right">
			<input type="hidden" name="val" value="1"/>
			<input type="hidden" name="s_data" value="yes"/>
			<input type="submit" id="Search" value="ค้นหา" />
			<input type="checkbox" name="s_cancel" id="s_cancel" checked disabled />แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก
			</td>
		</tr>
	</table>
	</form>
	</div>
	<!--  สิ้นสุดส่วนปุ่มคำสั่งสำหรับ  ค้นหาข้อมูลที่ให้ผู้ใช้ คลิก -->
	
	<!-- form สำหรับ ใบสำคัญรับ -->
	<form name="frm_search_RV" method="post" action="index-nw-RV.php">
		<input type="hidden" name="s_voucher" value="<?php echo $txt_voucher; ?>" /> <!-- เลขที่ใบสำคัญ -->
		<input type="hidden" name="datepicker" value="<?php echo $s_date; ?>" /> <!-- วันที่ ที่ต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="month" value="<?php echo $s_month; ?>" /> <!-- เดือน ที่ต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="year" value="<?php echo $s_year; ?>" /> <!-- ปี ที่้ต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="datefrom" value="<?php echo $s_datefrom; ?>" /> <!-- วันที่เริิ่มต้น ของต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="dateto" value="<?php echo $s_dateto; ?>" /> <!-- วันที่สุดท้าย ของการสืบค้นข้อมูล -->
		<input type="hidden" name="sel_year" value="<?php echo $s_sel_year; ?>" /> <!-- ปีที่ค้นหา -->
		<input type="hidden" name="search_type" value="<?php echo $s_valuee; ?>" /> <!-- ค่าตัวแปรจากการเลือก  วิธีการสืบค้นข้อมูลในเงื่อนไขหลัก -->
		<input type="hidden" name="s_detail" value="<?php echo $s_detail; ?>" /> <!-- ส่วนของ ตามรายละเอียด ที่ใช้สืบค้นข้อมูล -->
		<input type="hidden" name="s_cancel" value="<?php echo $s_cancel; ?>" /> <!-- สถานะการเลือก  "แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก" -->
		<input type="hidden" name="voucher_purpose" value="<?php echo $s_purpose_idx; ?>" /> <!-- เลขรหัสจุดประสงค์ ของใบสำคัญ -->
		<input type="hidden" name="chk_s_detail" value="<?php echo $s_chk_detail; ?>" /> <!-- สถานะการเลือก  "ตามรายละเอียด" -->
		<input type="hidden" name="chk_voucher_purpose" value="<?php echo $s_chk_purpose; ?>" /> <!-- สถานะการเลือก "จุดประสงค์" -->
		<input type="hidden" name="s_data" value="<?php echo $s_data; ?>" /> <!-- กดปุ่มค้นหา ถ้ากดจะเป็น yes -->
	</form>
	
	<!-- form สำหรับ ใบสำคัญรายวันทั่วไป -->
	<form name="frm_search_JV" method="post" action="index-nw-JV.php">
		<input type="hidden" name="s_voucher" value="<?php echo $txt_voucher; ?>" /> <!-- เลขที่ใบสำคัญ -->
		<input type="hidden" name="datepicker" value="<?php echo $s_date; ?>" /> <!-- วันที่ ที่ต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="month" value="<?php echo $s_month; ?>" /> <!-- เดือน ที่ต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="year" value="<?php echo $s_year; ?>" /> <!-- ปี ที่้ต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="datefrom" value="<?php echo $s_datefrom; ?>" /> <!-- วันที่เริิ่มต้น ของต้องการสืบค้นข้อมูล -->
		<input type="hidden" name="dateto" value="<?php echo $s_dateto; ?>" /> <!-- วันที่สุดท้าย ของการสืบค้นข้อมูล -->
		<input type="hidden" name="sel_year" value="<?php echo $s_sel_year; ?>" /> <!-- ปีที่ค้นหา -->
		<input type="hidden" name="search_type" value="<?php echo $s_valuee; ?>" /> <!-- ค่าตัวแปรจากการเลือก  วิธีการสืบค้นข้อมูลในเงื่อนไขหลัก -->
		<input type="hidden" name="s_detail" value="<?php echo $s_detail; ?>" /> <!-- ส่วนของ ตามรายละเอียด ที่ใช้สืบค้นข้อมูล -->
		<input type="hidden" name="s_cancel" value="<?php echo $s_cancel; ?>" /> <!-- สถานะการเลือก  "แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก" -->
		<input type="hidden" name="voucher_purpose" value="<?php echo $s_purpose_idx; ?>" /> <!-- เลขรหัสจุดประสงค์ ของใบสำคัญ -->
		<input type="hidden" name="chk_s_detail" value="<?php echo $s_chk_detail; ?>" /> <!-- สถานะการเลือก  "ตามรายละเอียด" -->
		<input type="hidden" name="chk_voucher_purpose" value="<?php echo $s_chk_purpose; ?>" /> <!-- สถานะการเลือก "จุดประสงค์" -->
		<input type="hidden" name="s_data" value="<?php echo $s_data; ?>" /> <!-- กดปุ่มค้นหา ถ้ากดจะเป็น yes -->
	</form>
	
	<?php
	if($s_data == "yes") // ถ้ามีการคลิกค้นหา
	{
		//-----------------------------
		//-- ใบสำคัญจ่าย
		//-----------------------------
		// สร้าง  Part Of SQL Comand จากสถานะ Click Check box ที่  "แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก"
		if($s_cancel == "on"){	//กรณี Click
			$condition_c = "";
		}else{ 					//กรณี ไม่ Click
			$condition_c = " a.\"voucherStatus\" = '1' and a.\"voucherAdjustCancelFor\" is null ";
			
		}
		
		// สร้าง Part Of SQL Comand ส่วนการระบุ col และ ตาราง
		$qry ="select a.* from v_thcap_temp_voucher_details_payment a ";
		
		// สร้าง Part Of SQL Comand จากเงื่อนไขหลัก
		$S_Cnd = "";
		if($s_valuee=="0"){ // กรณีเลือก   "ค้นหา Voucher ID:"
			$S_Cnd = " a.\"voucherID\"='$txt_voucher'  ";
		}else if($s_valuee=="1"){ //กรณีเลือก  "ตามวันที่ :"
			$S_Cnd = " a.\"doerStamp\"='$s_date'  ";
		}else if($s_valuee=="2"){ //กรณีเลือก "ตามเดือน"
			$S_Cnd = " EXTRACT(MONTH FROM a.\"doerStamp\")='$s_month' and EXTRACT(YEAR FROM a.\"doerStamp\")='$s_year'  ";
		}else if($s_valuee=="3"){//กรณีเลือก "ตามช่วง"
			$S_Cnd = " a.\"doerStamp\" between '$s_datefrom' and '$s_dateto'  ";
		}else if($s_valuee=="4"){//กรณีเลือก "ค้นหาทั้งหมด"
			$S_Cnd = "";
		}else if($s_valuee=="5"){ //เลือกค้นหาตาม ปี
			$S_Cnd = " EXTRACT(YEAR FROM a.\"doerStamp\")='$s_sel_year'  ";
		}
		
		//เชื่อม Part Of SQL Comand เพื่อการใช้งาน
		if(strlen($S_Cnd) > 0 ){
		 $S_Cnd = " Where ".$S_Cnd;
		} 
		
		$method = 0; 
		$qry_2 = ""; 
		
		//สร้าง Part Of SQL Comand จากเงื่อนไขรอง 
		if($s_chk_detail=="on"){// กรณีเลือก  "ตามรายละเอียด"
			 $qry_2 = "  \"voucherRemark\" like '%$s_detail%' "; 
			 if(strlen($S_Cnd) > 0 ){
				$S_Cnd .= " and ".$qry_2;
			 }else{
				$S_Cnd = " Where ".$qry_2;
			 }
		} 
		
		$qry_2 = "";
		if($s_chk_purpose=="on"){//กรณีเลือก . "จุุดประสงค์"
		  $qry_2 = "  \"voucherPurpose\"  =	".$s_purpose_idx;
		  if(strlen($S_Cnd) > 0 ){
			  $S_Cnd .= " and ".$qry_2;
		  }else{
			  $S_Cnd = " Where ".$qry_2;
		  }
		}
		// เตรียม SQL Comamd ส่วน การกำหนดเงื่อนไข
		if(strlen($condition_c) > 0){
			 if(strlen($S_Cnd) > 0 ){
			   $S_Cnd .= " and ". $condition_c;
			 }else{
			   $S_Cnd .= " Where ".$condition_c;
			 }
		} 
		// สร้าง SQL Comand เพื่อการใช้งาน
		$qry.=$S_Cnd; 
		?>
		<div align="center">
			<fieldset style="width:80%;">
				<legend>
					<input type="button" value="รายการใบสำคัญจ่าย" style="height:4em;" disabled>
					<input type="button" value="รายการใบสำคัญรับ" style="height:4em; cursor:pointer;" onClick="load_voucher('RV');">
					<input type="button" value="รายการใบสำคัญรายวันทั่วไป" style="height:4em; cursor:pointer;" onClick="load_voucher('JV');">
				</legend>
				<form name="frm_PV" action="pdf_payment_voucher.php" method="post" target="_blank">
					<div style="margin-top:10px;"align="center">
						<table cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#F0F0F0" align="center">
							<tr bgcolor="white">
								<td colspan="2" align="left"><font size="3" color="blue"><b>รายการใบสำคัญจ่าย</b></font></td>
								<td colspan="8" align="right">
									<input type="button" name="cancel" id="cancel" value="ขอยกเลิก ใบสำคัญจ่าย" onclick="validate_PV(this.form,'C');"/>
									<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF ใบสำคัญจ่าย" onclick="validate_PV(this.form,'PDF');"/>
								</td>
							</tr>
							<tr style="font-weight:bold;" valign="middle" bgcolor="#BEBEBE" align="center">
								<td>ลำดับที่</td>
								<td>รหัสใบสำคัญจ่าย</td>
								<td>วันที่ใบสำคัญจ่าย</td>
								<td>เวลาใบสำคัญจ่าย</td>
								<td>ผลรวม</td>
								<td>เลขที่บันทึกบัญชี</td>
								<td>ผู้ำทำรายการ</td>
								<td>วันเวลาที่ทำรายการ</td>
								<td><span id="selectAll_P" style="cursor:pointer;"><u><font color="blue">เลือกรายการ</font></u></span></td>
								<td>หมายเหตุ</td>
							</tr>
							<?php 
								$query_list = pg_query($qry);
								$num_row = pg_num_rows($query_list);
								if($num_row>0){
									$i = 0;
									while($res_v = pg_fetch_array($query_list)){
										$i++;
										$sum_debit=0;
										$voucherID = $res_v['voucherID'];
										$voucherDate = $res_v['voucherDate'];
										$doerFull = $res_v['doerFull'];
										$doerStamp = $res_v['doerStamp'];
										$abh_id = $res_v['abh_id'];
										$voucherTime = $res_v['voucherTime'];
										$voucherCancelRef = $res_v['voucherCancelRef'];
										$voucherStatus = $res_v['voucherStatus'];
										$voucherAdjustCancelFor = $res_v['voucherAdjustCancelFor'];//not null คือ ปรับปรุงยกเลิก
										//เพิ่มรายละเอียด ในบรรทัดใหม่ #6771
										$voucherRemark = $res_v['voucherRemark'];
										
										
										$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
										$abh_autoid = pg_fetch_result($qry_bookhead,0);
										
										$qry_concurrent = pg_query("select \"voucherID\" from thcap_temp_voucher_cancel where \"voucherID\"='$voucherID' and \"appvStatus\"='9' ");
										$num = pg_num_rows($qry_concurrent);
										
									
										if($num>0){
											$textStatus = "รออนุมัติยกเลิก";
											$Fcolor = "FF8000";
										}else if($voucherStatus == '0'){
											$textStatus = "ยกเลิกแล้ว";
											$Fcolor = "red";
										}else if($voucherAdjustCancelFor != ''){
											$textStatus = "ปรับปรุงยกเลิก";
											$Fcolor = "#CD853F";
												
										}else{
											$textStatus = "";
										}
										//หาผลรวม เดบิต
										$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
										$abh_autoid = pg_fetch_result($qry_bookhead,0);
										$qry_detail = pg_query("select sum(\"abd_amount\") as \"sum_amount\" from account.\"all_accBookDetail\" 
										where abd_autoidabh='$abh_autoid' and \"abd_bookType\" ='1' ");
										$sum_debit = pg_fetch_result($qry_detail,0);						
										$sum_debit = number_format($sum_debit,2);
										
										
										
									$bgcolor="";	
									if($i%2==0){	
										if($voucherStatus == '0'){
											echo "<tr bgcolor=\"#CCCCCC\" >";
											$bgcolor="#CCCCCC";
										}
										else if($voucherAdjustCancelFor != ''){
											echo "<tr bgcolor=\"#FFFFCC\" >";
											$bgcolor="#FFFFCC";
										}
										else{
											echo "<tr bgcolor=\"#EDF8FE\" >";
											$bgcolor="#EDF8FE";
										}
									}else{
										if($voucherStatus == '0'){
											echo "<tr bgcolor=\"#CCCCCC\" >";
											$bgcolor="#CCCCCC";
										}else if($voucherAdjustCancelFor != ''){
											echo "<tr bgcolor=\"#FFFFCC\" >";
											$bgcolor="#FFFFCC";
										}else{
											echo "<tr >";
										}
									}
											echo "<td align=\"center\">".number_format($i,0)."</td>";
											echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
											echo "<td align=\"left\">$voucherDate</td>";
											echo "<td align=\"left\">$voucherTime</td>";
											echo "<td align=\"right\">$sum_debit</td>";
											echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');\" style=\"cursor:pointer;\">$abh_id</a></u></font></td>";
											echo "<td align=\"left\">$doerFull</td>";
											echo "<td align=\"left\">$doerStamp</td>";
											echo "<td align=\"center\"><input type=\"checkbox\" name=\"select_print_PV[]\" id=\"select_print_PV$i\" value=\"$voucherID\"></td>";
											echo "<td align=\"center\"><font color=\"$Fcolor\">$textStatus</font></td>";
										echo "</tr>";
										
									/** if($s_valuee=="6"){ **/
										//format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
										$fromChannelDetails_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/';
										$fromChannelDetails_popup = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4\5'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4\5'."</u></font></span>";
						
										//รายละเอียด
										$voucherRemark = preg_replace($fromChannelDetails_format,$fromChannelDetails_popup,$voucherRemark);
										echo "<tr bgcolor=$bgcolor><td colspan=\"10\"><b>รายละเอียด:</b> $voucherRemark</tr>";
									/**	} **/
									}
								}else{					
									echo "<tr><td colspan=\"10\" align=\"center\">ไม่พบข้อมูลที่ค้นหา</td></tr><br>";
								}
							?>
							<tr cellspacing="10px" bgcolor="#BEBEBE">
								<td colspan="10" align="right">
									<input type="hidden" id="AllorClear" value="A"/>
									<input type="button" name="cancel" id="cancel" value="ขอยกเลิก ใบสำคัญจ่าย" onclick="validate_PV(this.form,'C');"/>
									<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF ใบสำคัญจ่าย" onclick="validate_PV(this.form,'PDF');"/>
								</td>
							</tr>
						</table>
					</div>
				</form>
			</fieldset>
		</div>
	<?php
	}
	// จบการค้นหาข้อมูล
	?>
	
	<div id="list_wait_cancel_P" style="width:80%;margin-top:30px;margin-left:auto;margin-right:auto;">
		<?php include("../thcap_approve_cancel_voucher/PaymentVoucher_cancel_wait.php"); ?>
	</div>
	
	<div id="list_wait_cancel_R" style="width:80%;margin-top:30px;margin-left:auto;margin-right:auto;">
		<?php include("../thcap_approve_cancel_voucher/ReceiveVoucher_cancel_wait.php"); ?>
	</div>
	
	<div id="list_wait_cancel_J" style="width:80%;margin-top:30px;margin-left:auto;margin-right:auto;">
		<?php include("../thcap_approve_cancel_voucher/JournalVoucher_cancel_wait.php"); ?>
	</div>
	
</body>

<script>
$(document).ready(function(){
	$("#datepicker").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#datefrom").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$("#dateto").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
});
$("#s_voucher").autocomplete({
        source: "voucher_autocomplete.php",
        minLength:1
});

$("#Search").click(function(){
	var chk = 0;
	var tv = $("#s_voucher").val();
	var date1 = $("#datepicker").val();
	var month = $("#month").val();
	var year = $("#year").val();
	var datefrom = $("#datefrom").val();
	var dateto = $("#dateto").val();
	var sel_year = $("#sel_year").val();
	var detail = $("#s_detail").val();
	var searchValue = $("input[name=search_type]:checked").val();
	var errorMessage = "Error Message! \n";
	var cancel = "";
	var purpose_idx = $("#voucher_purpose").val();
	var chk_sel_detail = "";
	var chk_sel_purpose = ""; 
	
	if($("#search_voucher:checked").val() == "0"){
		if(tv == ""){
			errorMessage += "กรุณาระบุ Voucher ที่ต้องการค้นหา \n";
		chk++;
		}
	}
	if($("#date1:checked").val() == "1"){
		if(date1 == ""){
			errorMessage +="กรุณาระบุวันที่ต้องการค้นหา\n";
			chk++;
		}
	}
	if($("#date2:checked").val() == "2"){
		if(month == ""){
			errorMessage +="กรุณาระบุเดือนต้องการค้นหา\n";
			chk++;
		}
		if(year == ""){
			errorMessage +="กรุณาระบุปีที่้ต้องการค้นหา\n";
			chk++;
		}
	}
	if($("#date3:checked").val() == "3"){
		if(datefrom == ""){
			errorMessage +="กรุณาระบุวันที่เริ่มต้นที่ต้องการค้นหา\n";
			chk++;
		}
		if(dateto == ""){
			errorMessage +="กรุณาระบุวันที่สิ้นสุดที่ต้องการค้นหา\n";
			chk++;
		}
		if(datefrom > dateto){
			errorMessage +="วันที่เริ่มต้นต้องน้อยกว่าวันที่สินสุด\n";
			chk++;
		}
	}
	//ตรวจสอบการค้นหาตามปี
	if($("#search_year:checked").val() == "5"){
		if(sel_year == ""){
			errorMessage +="กรุณาี่เลือกปีที่ต้องการค้นหา\n";
			chk++;
		}
	}
	//จบตรวจสอบการค้นหาตามปี
	
	//ตรวจสอบการค้นหาตามรายละเอียด
	if($("#search_year:checked").val() == "6"){
		if(detail == ""){
			errorMessage +="กรุณาี่กรอกรายละเอียดที่ต้องการค้นหา\n";
			chk++;
		}
	}
	
	// ตรวรจสอบการค้นหาตามจุดประสงค์์
	if((document.getElementById("chk_voucher_purpose").checked)&&(purpose_idx=="-")){
		errorMessage += "กรุณาเลือกจุดประสงค์\n";
		chk++;
	}
	if($("#s_cancel").is(':checked')){
		cancel = "on";
	}else{
		cancel = "off";
	}
	// ตรวจสอบว่า ในส่วนเงื่อนไขรอง มีการเลือกที่จะ  ค้นหาตามรายละเอียดหรือไม่
	if($("#chk_s_detail").is(':checked')){
		chk_sel_detail = "on";
	}else{
		chk_sel_detail = "off";
	}
	
	
	if($("#chk_voucher_purpose").is(':checked')){
		chk_sel_purpose = "on";
	}else{
		chk_sel_purpose = "off";
	}
	
	
	
	// ตรวจสอบว่า เลือกค้นหาตามรายละเอียดในเงื่อนไขรองหรือไม่ 
	
	if(chk == 0){
		// ถูกต้อง ทำงานต่อไปได้
	}else{
		alert(errorMessage);
		return false;
	}
});

$("input[name=search_type]").change(function(){
	$("#s_voucher").val('');
	$("#datepicker").val('');
	$("#month").val('');
	$("#datefrom").val('');
	$("#dateto").val('');
	$("#sel_year").val('<?php echo $v_year ?>');
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function Change_Element_ReadOnly_Status(e_id1,e_id2){
	var chk_status = document.getElementById(e_id1).checked;
	if(chk_status==true){ 
		document.getElementById(e_id2).readOnly = false;  
		document.getElementById(e_id2).style.backgroundColor='#FFFFFF';		
	    
   }else{
		document.getElementById(e_id2).readOnly = true;
		document.getElementById(e_id2).value = "";
		document.getElementById(e_id2).style.backgroundColor='#DDDDDD';		
   }
		
}
function Change_Element_Disable_Status(e_id1,e_id2){
	var chk_status = document.getElementById(e_id1).checked;
	var elm1=document.getElementById(e_id2);
	if(chk_status){
		elm1.disabled = false; 
	}else{
		elm1.disabled = true;
		elm1.selectedIndex = 0;
	}
}

Change_Element_ReadOnly_Status('chk_s_detail','s_detail'); // ตามรายละเอียด
Change_Element_Disable_Status('chk_voucher_purpose','voucher_purpose'); // จุดประสงค์
</script>

<script>
// ใบสำคัญจ่าย
$("#selectAll_P").click(function(){
	var select_PV = $("input[name=select_print_PV[]]");
	var chkBT = $("#AllorClear").val();
	var num = 0;
	
	if(chkBT=="A"){
		for(i=0; i<select_PV.length; i++){
			$(select_PV[i]).attr("checked","checked");
		}
		$("#AllorClear").val('C');
	}else{
		for(i=0; i<select_PV.length; i++){
			$(select_PV[i]).removeAttr("checked");
		}
		$("#AllorClear").val('A');
	}
});

function validate_PV(frm_PV,method){
	
	var select_PV = $("input[name=select_print_PV[]]:checked");
	var ErrorMessage = "Error Message! \n";
	var Error = 0;
	if(select_PV.length<1){
		ErrorMessage += "กรุณาเลือกรายการที่ต้องการ Print";
		Error++;
	}

	if(Error>0){
		alert(ErrorMessage);
		return false;
	}else{
		if(method == "PDF"){
			frm_PV.action="pdf_payment_voucher.php";
			frm_PV.submit();
		}else if(method == "C"){
			frm_PV.action="frm_cancel_payment_voucher.php";
			frm_PV.submit();
		}
	} 
}
</script>

</html>