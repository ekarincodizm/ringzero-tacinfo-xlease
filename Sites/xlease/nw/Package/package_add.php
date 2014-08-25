<?php
session_start();
include("../../config/config.php");
																	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-  -</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
		$("#nametype").hide();
		document.getElementById("num").readOnly = true;
		document.getElementById("price_accessory").readOnly = true;
		document.getElementById("price_not_accessory").readOnly = true;


		$("input[type='radio']").change(function(){

				if(document.getElementById("same").checked){
				
					$("#nametype").hide();
					$("#nameclick").show();
					$("#num").val("");
					$("#price_accessory").val("");
					$("#price_not_accessory").val("");
					document.getElementById("num").readOnly = true;
					document.getElementById("price_accessory").readOnly = true;
					document.getElementById("price_not_accessory").readOnly = true;
				
					
					
				}else if(document.getElementById("new").checked){
					
						$("#nametype").show();
						$("#nameclick").hide();
						$("#num").val("");
						$("#price_accessory").val("");
						$("#price_not_accessory").val("");
						document.getElementById("num").readOnly = false;
					document.getElementById("price_accessory").readOnly = false;
					document.getElementById("price_not_accessory").readOnly = false;
				}
			});
		});

function caldown1(){
	
	$.post("price_car.php",{
			brand : $('#car_gen1 option:selected').attr('value'),
			price : 'notaccessory'
		},
		function(data){		
			
				$("#price_not_accessory").val(data);
			
		});
	$.post("price_car.php",{
			brand : $('#car_gen1 option:selected').attr('value'),
			price : 'accessory'
		},
		function(data){		
			
				$("#price_accessory").val(data);
			
		});	
	$.post("price_car.php",{
			brand : $('#car_gen1 option:selected').attr('value'),
			price : 'num'
		},
		function(data){		
			
				$("#num").val(data);
			
		});

		
};

function checkList(){

	if(document.getElementById("same").checked){
				
				if(document.getElementById("car_gen1").value == 'fail' || document.getElementById("car_gen1").value == ""){
		
					alert(' กรุณาบันทึก ในรูปแบบข้อมูลใหม่ เพราะท่านไม่ได้เลือก รุ่นรถยนต์');
					return false;
					
				}
				else if(document.getElementById("price_not_accessory").value == ""){
					alert(' กรอกราคารถยนต์ ไม่รวมอุปกรณ์ ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("price_accessory").value == ""){
					alert(' กรอกราคารถยนต์ ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("down").value == ""){
					alert(' กรอกราคาดาวน์ ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("month").value == ""){
					alert(' กรอกจำนวนงวด ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("period").value == ""){
					alert(' กรอกค่างวด ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("interest").value == ""){
					alert(' กรอก ดอกเบี้ย ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("num").value == ""){
					alert(' ระบุ รหัสรถยนต์ ด้วยครับ ');
					return false;
				}else{
					return true;
				}
	}else if(document.getElementById("new").checked){
				
				if(document.getElementById("brandtype").value == ""){
		
					alert(' กรุณากรอก ชื่อรุ่นด้วยครับ');
					return false;
				}
				else if(document.getElementById("price_not_accessory").value == ""){
					alert(' กรอกราคารถยนต์ ไม่รวมอุปกรณ์ ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("price_accessory").value == ""){
					alert(' กรอกราคารถยนต์ ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("down").value == ""){
					alert(' กรอกราคาดาวน์ ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("month").value == ""){
					alert(' กรอกจำนวนงวด ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("period").value == ""){
					alert(' กรอกค่างวด ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("interest").value == ""){
					alert(' กรอก ดอกเบี้ย ด้วยครับ ');
					return false;
				}
				else if(document.getElementById("num").value == ""){
					alert(' ระบุ รหัสรถยนต์ ด้วยครับ ');
					return false;
				}else{
					return true;
				}
	}
}


function check_num(e){
	var key;
	if(window.event){
			key = window.event.keyCode; // IE
	if (key > 57)
		  window.event.returnValue = false;
		}else{
			key = e.which; // Firefox       
	if (key > 57)
		  key = e.preventDefault();
	  }
} 
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>
<body bgcolor="#EEF2F7">
<form name="frm" action="package_add_query.php" method="post">

	<table width="900" border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td align="center">
				<legend><h2><B> เพิ่ม Package </B></h2></legend>
				<hr width="650">
				<br>
				<table width="500" frame="border" cellspacing="0" cellpadding="0" >
					<tr>
						<td align="right" width="200">
							รุ่นรถยนต์ :
						</td>
						<td >
							<input type="radio" name="chiose" id="same" value="same" checked> มีอยู่แล้ว 
						
							<input type="radio" name="chiose" id="new" value="new" > เพิ่มใหม่ 
						</td>
					</tr>
					<tr name="nametype" id="nametype" >
						<td align="right">
							ชื่อรุ่น :
						</td>
						<td><input type="text" name="brandtype" id="brandtype"><font color="red">*</font> </td>
					</tr>
					<tr name="nameclick" id="nameclick" >
						<td align="right">
							ชื่อรุ่น :
						</td>
						<td>
									<select name="car_gen1" id="car_gen1" onchange="caldown1()">
												<?php $sql = pg_query("select distinct \"brand\",\"numtest\" from \"Fp_package\""); ?>
													<option value="fail">---- เลือกรุ่นรถยนต์ ----</option>													
												<?php while($re = pg_fetch_array($sql)){ ?>
													<option value="<?php echo $re['numtest']; ?>">---- <?php echo $re['brand']; ?> ----</option>								
												<?php } ?>
						</select><font color="red">*</font> </td>
					</tr>
					<tr>
						<td align="right">
							ราคารถยนต์ (ไม่รวมอุปกรณ์) :
						</td>
						<td >
							<input type="text" name="price_not_accessory" id="price_not_accessory" autocomplete="off"  OnKeyPress="check_num(event)"><font color="red">*</font> 
						</td>
					</tr>  
					<tr>
						<td align="right">
							ราคารถยนต์ :
						</td>
						<td >
							<input type="text" name="price_accessory" id="price_accessory" autocomplete="off" OnKeyPress="check_num(event)"><font color="red">*</font> 
						</td>
					</tr>
					<tr>
						<td align="right">
							ราคาดาวน์ :
						</td>
						<td >
							<input type="text" name="down" id="down" autocomplete="off" OnKeyPress="check_num(event)"><font color="red">*</font> 
						</td>
					</tr>
					<tr>
						<td align="right">
							จำนวนงวด :
						</td>
						<td >
							<select name="month" id="month">
								<option value="24"> 24 </option>
								<option value="30"> 30 </option>
								<option value="36"> 36 </option>
								<option value="42"> 42 </option>
								<option value="48"> 48 </option>
								<option value="54"> 54 </option>
								<option value="60"> 60 </option>
								<option value="66"> 66 </option>								
							</select>
							<!--<input type="text" name="month" id="month" autocomplete="off" OnKeyPress="check_num(event)"><font color="red">*</font> -->
						</td>
					</tr>
					<tr>
						<td align="right">
							ค่างวด :
						</td>
						<td >
							<input type="text" name="period" id="period" autocomplete="off" OnKeyPress="check_num(event)"><font color="red">*</font> 
						</td>
					</tr>
					<tr>
						<td align="right">
							ดอกเบี้ย :
						</td>
						<td >
							<input type="text" name="interest" id="interest" autocomplete="off" OnKeyPress="check_num(event)" size="5">% <font color="red">*</font> 
						</td>
					</tr>
					<tr>
						<td align="right">
							รหัสรุ่นของรถ  :
						</td>
						<td >
							<input type="text" name="num" id="num" size="5" autocomplete="off" OnKeyPress="check_num(event)"><font color="red">*</font> 
					</td>
					</tr>
					<tr>
						<td><br></td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input type="submit" value="บันทึก" style="height:50px; width:100px;" onclick="return checkList()"></td>
					</tr>
					
				</table>
				<br>
				<?php $numtest = pg_query("select distinct \"brand\",\"numtest\" from \"Fp_package\" order by \"numtest\" "); 
					  $row = pg_num_rows($numtest);
					  if($row != 0){ 
				?>
				<table width="400" cellSpacing="0" border="1" cellPadding="0" >
						<tr bgcolor="">						
								<th width="350"> <div align="center">รหัส รุ่นรถยนต์</div></th>
								<th width="350"> <div align="center">ยี่ห้อ/รุ่น</div></th>								
						</tr>	
						<?php	while($numtestvalue = pg_fetch_array($numtest)){ ?>
						<tr>	
								<td><div align="center"><?php echo $numtestvalue['numtest'];?></div></td>
								<td><div align="center"><?php echo $numtestvalue['brand'];?></div></td>
						</tr>
						<?php } ?>
				</table>
				<?php } ?>
				
			</td>
		</tr>						
	</table>
</form>