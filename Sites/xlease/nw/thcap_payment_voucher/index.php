<?php 
include("../../config/config.php");
include("../../nw/function/load_purpose.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใบสำคัญจ่าย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<div style="margin-top:10px;" align="center"><h1>(THCAP) ใบสำคัญจ่าย</h1></div>
	<div style="margin-top:10px; width:60%;margin-left:auto;margin-right:auto;">
	
	<!-- เริ่มต้นส่วน การรับ "เงื่อนไขหลัก" เพื่อการสืบค้นข่้อมูล -->
	
	<fieldset><legend>เงือนไขการค้นหาหลัก</legend>
		<table align="center" cellspacing="10px">
			
			<tr>
				<td><input type="radio" id="search_voucher" name="search_type"  value="0" /><!-- เลือกการค้นหา โดย ระบุ "Voucher ID" (เเลขที่ใบสำคัญ) --></td>
				<td><b>ค้นหา Voucher ID:</b></td>
				<td>
					<input type="text" name="s_voucher" id="s_voucher"><!-- นำเข้า Voucher ID -->
				</td>	
			</tr>
			<tr>
				<td>
					<input type="radio" id="date1" name="search_type"  value="1" checked /><!-- เลือกการค้นหา  โดยระบุ  "วันที่ "-->
				</td>
				<td><b>ตามวันที่ :</b></td>
				<td>
					<input type="text" id="datepicker" name="datepicker" value="" size="15" style="text-align:center">&nbsp;<!-- ตัวเลือกวันที่ -->
				</td>
			</tr>
			<tr>
				<td>
					<input type="radio" id="date2" name="search_type"  value="2" /><!-- เลือกการค้นหา โดยระบุ  "เดือน" -->
				</td>
				<td><b>ตามเดือน:</b></td>
				<td>
					<select name="month" id="month"> <!-- "เดือน" ให้เลือก --> 
						<option value="">--เลือกเดือน--</option>
						<option value="01" <?php if($month=="01") echo "selected";?>>มกราคม</option>
						<option value="02" <?php if($month=="02") echo "selected";?>>กุมภาพันธ์</option>
						<option value="03" <?php if($month=="03") echo "selected";?>>มีนาคม</option>
						<option value="04" <?php if($month=="04") echo "selected";?>>เมษายน</option>
						<option value="05" <?php if($month=="05") echo "selected";?>>พฤษภาคม</option>
						<option value="06" <?php if($month=="06") echo "selected";?>>มิถุนายน</option>
						<option value="07" <?php if($month=="07") echo "selected";?>>กรกฎาคม</option>
						<option value="08" <?php if($month=="08") echo "selected";?>>สิงหาคม</option>
						<option value="09" <?php if($month=="09") echo "selected";?>>กันยายน</option>
						<option value="10" <?php if($month=="10") echo "selected";?>>ตุลาคม</option>
						<option value="11" <?php if($month=="11") echo "selected";?>>พฤศจิกายน</option>
						<option value="12" <?php if($month=="12") echo "selected";?>>ธันวาคม</option>
					</select>
				<b>ปี :</b>
					<select name="year" id="year"> <!-- ปีให้เลือก  --> 	

					<?php $datenow1 = nowDate();
					list($year,$month,$day)=explode("-",$datenow1);
					$yearback = $year -10;
					for($t=$yearback;$t<=$year;$t++){
					if($t == $year){ ?> 
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
					<input type="radio" id="date3" name="search_type"  value="3" /><!-- เลือกการค้นหาโดย  "ตามช่วง" (ช่วงวันที่) -->
				</td>
				<td><b>ตามช่วง:</b></td>
				<td>
					<b>จาก</b>
					<input type="text" id="datefrom" name="datefrom" value="<?php echo $datefrom; ?>" size="15" style="text-align:center"><!-- นำเช้า "วันที่เริ่มต้น" -->
					<b>ถึง</b>
					<input type="text" id="dateto" name="dateto" value="<?php echo $dateto; ?>" size="15" style="text-align:center"><!-- นำเช้า "วันที่สิ้นสุด" -->
				</td>
			</tr>
			<!--ตามปี-->
			<tr>
				<td>
					<input type="radio" id="search_year" name="search_type"  value="5" /><!-- เลือกการค้นหาโดย  "ตามปี"  -->
				</td>
				<td><b>ตามปี:</b></td>
				<td>
					<select name="sel_year" id="sel_year"><!-- ปีที่ให้เลือก -->
					<?php $v_datenow = nowDate();
					list($v_year,$v_month,$v_day)=explode("-",$v_datenow);
					$yearback = $v_year -10;
					for($y=$yearback;$y<=$v_year;$y++){
						if($y == $year){ ?> 
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
					<input type="radio" id="ALL" name="search_type"  value="4" /><!-- เลือกการค้นหาทั้งหมด -->
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
					<input type="checkbox" name="chk_s_detail" id="chk_s_detail"  OnClick ="javascript:Change_Element_ReadOnly_Status('chk_s_detail','s_detail');"  /><!-- เลือกการค้นหาแบบ "ตามรายละเอียด"   -->
				</td>
				<td><b>ตามรายละเอียด:</b></td>
				<td>
					<input type="text" name="s_detail" id="s_detail" size="70" readonly = "true" style="background:#DDDDDD;" ><!-- นำเข้ารายละเอียด -->
				</td>
			</tr>
			
			<tr>
				<td>
					<input type="checkbox" name="chk_voucher_purpose" id="chk_voucher_purpose" OnClick ="javascript:Change_Element_Disable_Status('chk_voucher_purpose','voucher_purpose')"; /><!-- เลือกการค้นหาแบบ "ตามจุดประสงค์"   -->
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
						 <option value="<?php echo $data['thcap_purpose_id'];?>" ><?php echo $data['thcap_purpose_name']; ?></option>	
						<?php
					   }	 
					?>	
					</select>
				</td>
				</td>
			</tr>
	  </table>
	</fieldset>
	
	<!-- สิ้นสุดส่วน การรับ "เงื่อนไขรอง"  เพื่อการสืบค้นข้อมูล -->
	
	<!--   เริ่มส่วนปุ่มคำสั่งสำหรับ  ค้นหาข้อมูลที่ให้ผู้ใช้ คลิก -->
	
	<table align="center" cellspacing="10px">
	  <tr>
			<td colspan="3" align="right">
			<input type="hidden" name="val" value="1"/>
			<input type="button" id="Search"  value="ค้นหา" />
			<input type="checkbox" name="s_cancel" id="s_cancel" />แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก
			</td>
				
	  </tr>
	</table>
	
	<!--  สิ้นสุดส่วนปุ่มคำสั่งสำหรับ  ค้นหาข้อมูลที่ให้ผู้ใช้ คลิก -->
	
	</div>
	
	<div id="list_voucher" style="margin-top:10px;"></div>
	
	<div id="list_wait_cancel" style="width:80%;margin-top:30px;margin-left:auto;margin-right:auto;"><?php include("../thcap_approve_cancel_voucher/PaymentVoucher_cancel_wait.php"); ?></div>
	
</bodY>
</html>
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
		$("#list_voucher").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
		$("#list_voucher").load("list_voucher.php",{
			txt_voucher:tv,
			s_date:date1,
			s_month:month,
			s_year:year,
			s_datefrom:datefrom,
			s_dateto:dateto,
			s_sel_year:sel_year,
			s_value:searchValue,
			s_cancel:cancel,
			s_detail:detail,
			s_purpose_idx:purpose_idx,
			s_chk_detail:chk_sel_detail,
			s_chk_purpose:chk_sel_purpose
			});
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
</script>