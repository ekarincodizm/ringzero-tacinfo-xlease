<?php
include("../../config/config.php");
$term=pg_escape_string($_POST["s_text"]);
$radio=$_POST["search"];
$datepic=pg_escape_string($_POST["datepicker"]);
$currentdate=nowDate();

if($datepicker==""){
	$datepicker=$currentdate;
	$datefrom=$currentdate;
	$dateto=$currentdate;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) บัญชีสมุดรายวันทั่วไป</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>

    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/JavaScript">
$(function(){
    
    $('#inputtext').hide();
    $('#select').hide();
	$('#betweenm').hide();
    $('#select_type').hide();

$('#find').change(function(){
        if($('#find').val() == '0'){
			$('#inputtext').hide();
			$('#select').hide();
			$('#all').show();
			$('#betweenm').hide();
			$('#select_type').hide();
        }else if($('#find').val() == '2'){
           $('#inputtext').hide();
			$('#select').show();
			$('#betweenm').hide();
			$('#all').hide();
			$('#select_type').hide();
        }else if(($('#find').val() == '1') || ($('#find').val() == '3')){
            $('#inputtext').show();
			$('#select').hide();
			$('#all').hide();
			$('#betweenm').hide();
			$('#select_type').hide();
        }else if($('#find').val() == '4'){
            $('#inputtext').hide();
			$('#select').hide();
			$('#all').hide();
			$('#betweenm').show();
			$('#select_type').hide();
        }else if($('#find').val() == '5'){
            $('#inputtext').hide();
			$('#select').hide();
			$('#all').hide();
			$('#betweenm').hide();
			$('#select_type').show();
        }
    });
    
});

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
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function check(){	
	var i=0;
	var cancel = "";
	var checkerror=true;
	var theMessage = "กรุณาเลือกเงื่อนไข ที่จะค้นหาให้ครบ: \n-----------------------------------\n";
	var selectdate=false;
	var r=document.getElementsByName("date1");
	while(i<r.length){
		if(r[i].checked==true){
			selectdate=true;
			if(r[i].value=='3'){
			  if(($("#datefrom").val())>($("#dateto").val())){
					checkerror=false;
					theMessage = theMessage + "\n --> วันที่เริ่มค้นหาต้องน้อยกว่าหรือเท่ากับวันที่สิ้นสุด";
				}
			}
			else if(r[i].value=='2'){				
				if($("#month").val()==''){
					checkerror=false;
					theMessage = theMessage + "\n --> กรุณาเลือกเดือนที่จะค้นหา";				
				}
			}
			break;}
		else{i++;}
	}
	
	if(($("#find").val()!='')&&(selectdate==true)&&(checkerror==true) ){
		if($("#find").val()=='0'){}
		else if(($("#find").val()=='1')||($("#find").val()=='3')){		
			if($("#id").val()==""){
				checkerror=false;
				theMessage = theMessage + "\n --> กรุณากรอกข้อมูล";	
			}
		}
		else if($("#find").val()=='2'){
			if($("#selectfind").val()==''){
				checkerror=false;
				theMessage = theMessage + "\n --> กรุณาเลือกข้อมูล";	
			}
		}
		else if($("#find").val()=='4'){
			if(($("#mfrom").val()=='' )||($("#mto").val()=='')){
				checkerror=false;
				theMessage = theMessage + "\n --> กรุณากรอกจำนวนเงิน";	
			}
			else{
				if(parseFloat($("#mfrom").val())>(parseFloat($("#mto").val()))){
					checkerror=false;
					theMessage = theMessage + "\n --> จำนวนเงินที่เริ่มค้นหาต้องน้อยกว่าหรือเท่ากับจำนวนเงินสิ้นสุด";
				}
			}
		}
		
		if($("#s_cancel").is(':checked')){
			cancel = "on";
		}else{
			cancel = "off";
		}
		if(checkerror==true){
			$("#detail").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
			$("#detail").load("frm_select_find_acc.php?date1="+r[i].value+"&find="+$("#find").val()+"&mfrom="+$("#mfrom").val()+"&mto="+$("#mto").val()+"&datepicker="+$("#datepicker").val()+"&year="+$("#yearfind").val()+"&month="+$("#month").val()+"&selectfind="+$("#selectfind").val()+"&datefrom="+$("#datefrom").val()+"&dateto="+$("#dateto").val()
			+"&id="+$("#id").val()+"&selectfind="+$("#selectfind").val()+"&by_year="+$("#year").val()+"&cancel="+cancel+"&selecttype="+$("#selecttype").val());
		}
		else{alert(theMessage);}		
	}
	else{
		alert(theMessage);
	}	
}
function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
};
</script>

</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0" align="center" border="0">
    <tr>
		<td>
			<div style="padding-bottom: 10px;text-align:center;"><h2>(THCAP) บัญชีสมุดรายวันทั่วไป</h2></div>
			<fieldset><legend><B>เลือกเงื่อนไข</B></legend>
				<table align="center" >
				<tr>
					<td><b>เงื่อนไขการค้นหา:</b></td>
					<td>
					<select name="find" id="find">
					<option value="0" <?php if($find=="0") echo "selected";?>>ทั้งหมด</option>
					<option value="1" <?php if($find=="1") echo "selected";?>>เลขที่</option>
					<option value="2" <?php if($find=="2") echo "selected";?>>ประเภทสมุดเฉพาะ</option>
					<option value="3" <?php if($find=="3") echo "selected";?>>คำอธิบาย</option>
					<option value="4" <?php if($find=="4") echo "selected";?>>จำนวนเงิน</option>
					<option value="5" <?php if($find=="5") echo "selected";?>>ประเภทใบสำคัญ</option>
					</select>
					</td>
				<td>
				<span id= "all">	
					
				</span>				
				<span id ="inputtext">	
					<b>กรอกข้อมูล:<b><input type="text" id="id" name="id"  size="30" >
				</span>
				<span id ="betweenm">	
					<b>จาก :<b><input type="text" id="mfrom" name="mfrom"  size="15" onkeypress="check_num(event);" >
					<b>ถึง :<b><input type="text" id="mto" name="mto"  size="15" onkeypress="check_num(event);">
				</span>
				<span id ="select">	
					<b>เลือกข้อมูล:<b>
					<select name="selectfind" id="selectfind">
					<?php 	
						$sql_type = pg_query("select \"GJ_typeID\",\"bookName\" from account.\"General_Journal_Type\"");
							echo "<option value=\"all\">-ทุกประเภท-</option>";
						while($re_type = pg_fetch_array($sql_type)){							
							echo "<option value=\"".$re_type["GJ_typeID"]."\">".$re_type["GJ_typeID"]."-".$re_type["bookName"];							
							echo "</option>";
						} 
					?></select>
				</span>
				<span id ="select_type">
					<b>ประเภทใบสำคัญ:<b>
					<select name="selecttype" id="selecttype">
					<?php
						$query_type=pg_query("select DISTINCT \"abh_type\" as \"abh_type\" from account.\"all_accBookHead\"
												order by \"abh_type\" asc ");
						while($re_abh_type = pg_fetch_array($query_type)){
							echo "<option value=\"".$re_abh_type["abh_type"]."\">".$re_abh_type["abh_type"];
							echo "</option>";
						}
					?></select>
				</span>

				</td>
				<td><b>จาก:</b></td>
				<td><input type="radio" id="alldate" name="date1"  value="0" <?php if($date1=="0"){ echo "checked"; }?>/></td>
				<td>ทุกช่วงเวลา</td>
				
				<tr>
					<td colspan="4"></td>				
					<td><input type="radio" id="date1" name="date1"  value="1" <?php if($date1=="1" || $date1=="1"){ echo "checked"; }?>/></td>				
				<td>ตามวันที่ :</td>
				<td>
				<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" readonly="true" style="text-align:center">&nbsp;	
				</td>
				</tr>
			<tr>
				<td colspan="4"></td>	
				<td><input type="radio" id="date2" name="date1"  value="2"<?php if($date1=="" || $date1=="2"){ echo "checked"; }?> /></td>
				
				<td>ตามเดือน:</td>
				<td><select name="month" id="month"> 
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
				<td>ปี  </td>
					<td><select name="yearfind" id="yearfind"> 	
					<?php
					$datenow1 = nowDate();
					list($year,$month,$day)=explode("-",$datenow1);
						for($t=2013;$t<=2023;$t++){
							if($t == $year){ ?> 
							<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
							<?php }else{ ?>
							<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
							<?php  
							}
						} 
						
					?>	
					</select></td>
				</td>
			</tr>
		<tr>
			<td colspan="4"></td>	
			<td><input type="radio" id="date3" name="date1"  value="3" <?php if($date1=="3"){ echo "checked"; }?>/></td>			
			<td>ตามช่วง: จาก </td>
			<td>
				<input type="text" id="datefrom" name="datefrom" value="<?php echo $datefrom; ?>" size="15" readonly="true" style="text-align:center">&nbsp;
			</td>
			<td>ถึง</td>
			<td>
				<input type="text" id="dateto" name="dateto" value="<?php echo $dateto; ?>" size="15" readonly="true" style="text-align:center">&nbsp;		
			</td>
		</tr>
		<tr>
			<td colspan="4"></td>	
			<td><input type="radio" id="date4" name="date1"  value="4" <?php if($date1=="4"){ echo "checked"; }?>/></td>			
			<td>ตามปี:</td>
			<td>
			<select name="year" id="year"> 	

					<?php 
					$year='2013';
					$yearback = $year +10;
					for($t=$year;$t<=$yearback;$t++){
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
		<tr><td colspan="8" align="center">
		<input type="checkbox" name="s_cancel" id="s_cancel" />แสดงรายการที่ยกเลิก</td></tr>
		<tr><td colspan="8" align="center">
		<input type="hidden" name="val" value="1"/>
		<input type="button" id="search"  value="ค้นหา" onclick="check();"/></td></tr>

			</table>
			</fieldset><br>
			<div name="detail" id="detail">	
			</div>
        </td>
    </tr>
</table>
</body>
</html>