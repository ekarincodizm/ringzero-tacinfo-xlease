<?php 
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานใบเพิ่มหนี้</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<div style="margin-top:10px;" align="center"><h1>(THCAP) รายงานใบเพิ่มหนี้</h1></div>
	<div style="margin-top:10px; width:60%;margin-left:auto;margin-right:auto;">
	<fieldset><legend>เงือนไขการค้นหา</legend>
		<table align="center" cellspacing="10px">			
			<tr>
				<td><input type="radio" id="search_debit" name="search_type"  value="0" /></td>
				<td><b>ค้นหา รหัส Credit Note:</b></td>
				<td><input type="text" name="s_debitnote" id="s_debitnote"></td>	
			</tr>
			<tr>
				<td><input type="radio" id="date1" name="search_type"  value="1"/></td>
				<td><b>ตามวันที่ที่มีผล :</b></td>
				<td><input type="text" id="datepicker" name="datepicker" value="" size="15" readonly="true" style="text-align:center">&nbsp;</td>
			</tr>
			<tr>
				<td><input type="radio" id="date2" name="search_type"  value="2" checked /></td>
				<td><b>ตามเดือนที่มีผล:</b></td>
				<td>
					<select name="month" id="month"> 
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
					<select name="year" id="year"> 	

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
				<td><input type="radio" id="date3" name="search_type"  value="3" /></td>
				<td><b>ตามช่วงที่มีผล:</b></td>
				<td>
					<b>จาก</b>
					<input type="text" id="datefrom" name="datefrom" value="<?php echo $datefrom; ?>" size="15" readonly="true" style="text-align:center">
					<b>ถึง</b>
					<input type="text" id="dateto" name="dateto" value="<?php echo $dateto; ?>" size="15" readonly="true" style="text-align:center">
				</td>
			</tr>
			<tr>
				<td><input type="radio" id="ALL" name="search_type"  value="4" /></td>
				<td><b>ค้นหาทั้งหมด</b></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3" align="center">
				<input type="hidden" name="val" value="1"/>
				<input type="button" id="Search"  value="ค้นหา" />
				</td>
			</tr>
		</table>
	</fieldset>
	</div>
	<!--ผลจากการค้นหา-->
	<div id="list_debit" style="margin-top:10px;"></div>
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
$("#s_debitnote").autocomplete({
        source: " s_debit_note.php",
        minLength:1
});
$("#Search").click(function(){
	var chk = 0;
	var tv = $("#s_debitnote").val();
	var date1 = $("#datepicker").val();
	var month = $("#month").val();
	var year = $("#year").val();
	var datefrom = $("#datefrom").val();
	var dateto = $("#dateto").val();
	var searchValue = $("input[name=search_type]:checked").val();
	var errorMessage = "Error Message! \n";
	var cancel = "";
	
	if($("#search_debit:checked").val() == "0"){
		if(tv == ""){
			errorMessage += "กรุณาระบุ  Cradit Note ที่ต้องการค้นหา \n";
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
	if(chk == 0){
		$("#list_debit").load("list_debit.php",{
			txt_dcNoteID:tv,
			s_date:date1,
			s_month:month,
			s_year:year,
			s_datefrom:datefrom,
			s_dateto:dateto,
			s_value:searchValue
			});
	}else{
		alert(errorMessage);
		return false;
	}
});
$("input[name=search_type]").change(function(){
	$("#s_debitnote").val('');
	$("#datepicker").val('');
	$("#month").val('');
	$("#datefrom").val('');
	$("#dateto").val('');
});
</script>