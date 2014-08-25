<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");

$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
list($date,$time) = explode(" ",$datenow);
list($hor,$min,$sec) = explode(":",$time);
$hor = $hor - 1;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-- แบบสำรวจ --</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};


function check_num(e)
{
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




$(document).ready(function(){

    $("#empname").autocomplete({
        source: "listemp.php",
        minLength:1
    });
	
	 $("#regis_car").autocomplete({
        source: "gdata_idno.php",
        minLength:1
    });
	
	$("#day").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			
	});
	
});


	
function nicknamefunc(){
	
	$.post("nickname.php",{
			name : document.frm.empname.value
			
		},
		function(data){		
			
				$("#empnickname").val(data);			
		});
};

function clearnick(){
	document.frm.empnickname.value="";
}

	
function checkList(){

			if(confirm(' ควรตรวจสอบข้อมูลให้เรียบร้อย ก่อนลงบันทึกนะครับ\n\nคุณต้องการลงบันทึกใช่หรือไม่ ?')==true)
			{
				return true;
			}else
			{
				return false;
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

<body>
<form name="frm" action="poll_query.php" method="POST">
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="right" width="45%">
			<img src="../../images/<?php echo $file_namepic; ?>" width="100" height="85	" />
		</td>
		<td align="left">
			<h2> THAIACE GROUP </h2>
		</td>
	</tr>	
		<td align="center" colspan="2"><h1> แบบสำรวจความพึงพอใจของผู้ใช้บริการ </h1></td>
	<tr>
	<tr>
		<td colspan="2">
				
				<table  width="800" frame="box"  cellspacing="4" cellpadding="3" align="center" bgcolor="#EEF2F7">
					<tr>
						<td colspan="5">
							<b><u>ส่วนที่.1</u> ข้อมูลทั่วไปของผู้ตอบแบบสอบถาม ( ข้อมูลของท่าจะถูกเก็บเป็นความลับ )</b>
						</td>						
					</tr>
					<tr>
						<td width="25%">
							เพศ : 
						
							<input type="radio" name="gender" id="male" value="Male" checked> ชาย
						
							<input type="radio" name="gender" id="female" value="Female"> หญิง
						</td>	
						<td align="right" width="20%">
							ทะเบียนรถ : 
						</td>
						<td width="20%">
							<input type="text" name="regis_car" id="regis_car">
						</td>	
						<td width="30%">
						</td>		
					</tr>
					<tr>
						<td>
							อายุ : <input type="text" name="age" size="10" onkeypress="check_num(event)"> ปี 
						</td>
						<td align="right">
							หมายเลขโทรศัพท์ : 						
						</td>
						<td>
							<input type="text" name="phone" size="20" onkeypress="check_num(event)"> 
						</td>
						<td colspan="2" align="left">
							อีเมลล์ : 
						
							<input type="text" name="mail">
						</td>
					</tr>
					<tr>
						<td colspan="4">
							เรื่องที่มารับบริการ :							
						
							<input type="text" name="type">
						
							วันที่ใช้บริการ :							
						
							<input type="text" name="day" id="day" value="<?php echo $date ?>" onchange="chkdate()"/>
						
							เวลาที่ใช้บริการ :							
								<select name="hour">
									<?php for($i=0;$i<=24;$i++){ 
										if($i < 10){
											$h = "0".$i;
										}else{
											$h = $i;
										}
									?>	
										<option value="<?php echo $h ?>" <?php if($h == $hor){ ?> selected="selected" <?php } ?>><?php echo $h ?></option>
									<?php } ?>	
								</select>	นาฬิกา
								<select name="minute">
									<?php for($i=0;$i<60;$i++){ 
										if($i < 10){
											$h = "0".$i;
										}else{
											$h = $i;
										}
									?>	
										<option value="<?php echo $h ?>" <?php if($h == $min){ ?> selected="selected" <?php } ?>><?php echo $h ?></option>
									<?php } ?>	
								</select>	นาที
						</td>
					</tr>
					<tr>					
						<td colspan="5">
							<input type="checkbox" name="more5year" value="YES"> ท่านเป็นลูกค้าของไทยเอซมานานเกิน 5 ปี
						</td>
					</tr>
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<b><u>ส่วนที่.2</u> ระดับความพึงพอใจของผู้รับบริการ ( เลือก คะแนน ที่ตรงกับความรู้สึกของท่าน )</b>
						</td>						
					</tr>
					<tr>
						<td colspan="5">
							กรุณากรอกข้อมูลของเจ้าหน้าที่ที่ให้บริการท่าน เท่าที่ท่านทราบ
						</td>						
					</tr>
					<tr>
						<td align="right">
							 เจ้าหน้าที่ ชื่อ-นามสกุล :
						</td>
						<td colspan="2">
							<input type="text" name="empname" id="empname" size="35" onkeypress="clearnick()" onblur="nicknamefunc()">
						</td>
						<td colspan="2">
							ชื่อเล่น : 
						
							<input type="text" name="empnickname" id="empnickname">
						</td>
					</tr>	
					<tr>
						<td align="right">
							รหัสประจำตัวที่ : 
						</td>
						<td>
							<input type="text" name="identify_emp">
						</td>
						<td align="left" colspan="3"> 
							ช่องบริการที่ : 
						
							<input type="text" name="service_number" size="5">
						</td>
								
					</tr>
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>					
					<tr>
						<td colspan="5" align="center">
							ระดับความพึงพอใจ  5 = มากที่สุด  4 = มาก  3 = ปานกลาง  2 = น้อย  1 = น้อยที่สุด
						</td>
					</tr>
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>
					<tr>
						<td colspan="5" align="center">
							<table  width="700" border="1" cellspacing="0" cellpadding="3" align="center">
								<tr>
									<td align="center" width="65%">
										ประเมินความพึงพอใจ
									</td>
									<td align="center" width="7%">
										5
									</td>
									<td align="center" width="7%">
										4
									</td>
									<td align="center" width="7%">
										3
									</td>
									<td align="center" width="7%">
										2
									</td>
									<td align="center" width="7%">
										1
									</td>
								</tr>
								<tr>
									<td>
										ด้านการให้บริการของเจ้าหน้าที่
									</td>
									<td align="center"><input type="radio" name="poll1" value="5" checked></td>
									<td align="center"><input type="radio" name="poll1" value="4"></td>
									<td align="center"><input type="radio" name="poll1" value="3"></td>
									<td align="center"><input type="radio" name="poll1" value="2"></td>
									<td align="center"><input type="radio" name="poll1" value="1"></td>									
								</tr>
								<tr>
									<td>
										ให้บริการด้วยความสุภาพ อ่อนน้อม ยิ้มแย้ม เป็นกันเอง
									</td>
									<td align="center"><input type="radio" name="poll2" value="5" checked></td>
									<td align="center"><input type="radio" name="poll2" value="4"></td>
									<td align="center"><input type="radio" name="poll2" value="3"></td>
									<td align="center"><input type="radio" name="poll2" value="2"></td>
									<td align="center"><input type="radio" name="poll2" value="1"></td>									
								</tr>
								<tr>
									<td>
										มีความเอาใจใส่  กระตือรือร้น เต็มใจให้บริการ
									</td>
									<td align="center"><input type="radio" name="poll3" value="5" checked></td>
									<td align="center"><input type="radio" name="poll3" value="4"></td>
									<td align="center"><input type="radio" name="poll3" value="3"></td>
									<td align="center"><input type="radio" name="poll3" value="2"></td>
									<td align="center"><input type="radio" name="poll3" value="1"></td>									
								</tr>
								<tr>
									<td>
										รับฟังปัญหาหรือข้อซักถามของผู้ใช้บริการอย่างเต็มใจ
									</td>
									<td align="center"><input type="radio" name="poll4" value="5" checked></td>
									<td align="center"><input type="radio" name="poll4" value="4"></td>
									<td align="center"><input type="radio" name="poll4" value="3"></td>
									<td align="center"><input type="radio" name="poll4" value="2"></td>
									<td align="center"><input type="radio" name="poll4" value="1"></td>									
								</tr>
								<tr>
									<td>
										ให้คำอธิบายและตอบข้อสงสัยได้ตรงประเด็น
									</td>
									<td align="center"><input type="radio" name="poll5" value="5" checked></td>
									<td align="center"><input type="radio" name="poll5" value="4"></td>
									<td align="center"><input type="radio" name="poll5" value="3"></td>
									<td align="center"><input type="radio" name="poll5" value="2"></td>
									<td align="center"><input type="radio" name="poll5" value="1"></td>									
								</tr>
								<tr>
									<td>
										มีความชัดเจนในการให้คำแนะนำที่เป็นประโยชน์
									</td>
									<td align="center"><input type="radio" name="poll6" value="5" checked></td>
									<td align="center"><input type="radio" name="poll6" value="4"></td>
									<td align="center"><input type="radio" name="poll6" value="3"></td>
									<td align="center"><input type="radio" name="poll6" value="2"></td>
									<td align="center"><input type="radio" name="poll6" value="1"></td>									
								</tr>
							</table>							
						</td>
					</tr>	
					<tr>
						<td colspan="5"><br>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<b><u>ตอนที่ 3</u> ข้อคิดเห็นและข้อเสนอแนะเพิ่มเติม</b>
						</td>
					</tr>
					<tr>
						<td colspan="5" align="center">
							<textarea cols="100" rows="5" name="recommend"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<br>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value=" บันทึก " style="height:70px; width:120px;" onclick="return checkList()">
						</td>
						
						<td colspan="2" align="center">
							<input type="button" value=" ยกเลิก " onclick="parent.location.href='index.php'" style="height:70px; width:120px;">
						</td>
					</tr>						
				</table>
					
        </td>
    </tr>
</table>
</form>
</body>
</html>
