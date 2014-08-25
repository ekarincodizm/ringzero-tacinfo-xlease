<?php
session_start();
include('../../../config/config.php');

$cud1 = $_GET['cusidd'];
if($cud1 == ""){
$cud = $_POST['CusID'];

list($cusid,$cusname) = explode("#",$cud);
}else{
$cusid = $_GET['cusidd'];
}


?>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" /> 
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function checkid(){
      $.post("checkiden.php",{
			iden : document.frm.iden.value
			
		},
		function(data){		
			if(data == 'yes'){
				$("#textiden").text('ex.xxxxxxxxxxxxx');
				document.frm.checkidensame.value = 'pass';
				
			}else if(data == 'no'){	
				$("#textiden").text('หมายเลขบัตรซ้ำ กรุณาเปลี่ยน !');
				document.frm.checkidensame.value = 'fail';
				document.getElementById("iden").style.backgroundColor = "#FF6666";
		
			}
		});
}


function digit(){
var str= document.frm.iden.value;
	if(str.length < 13){
		alert('กรุณากรอกบัตรประชาชนให้ครบ 13 หลัก');
		document.frm.checkdigit.value = 'fail';
		document.getElementById("iden").style.backgroundColor = "#FF6666";
	}else{
		
		var dig1 = (str.substring(0, 1))*13;
		var dig2 = (str.substring(1, 2))*12;
		var dig3 = (str.substring(2, 3))*11;
		var dig4 = (str.substring(3, 4))*10;
		var dig5 = (str.substring(4, 5))*9;
		var dig6 = (str.substring(5, 6))*8;
		var dig7 = (str.substring(6, 7))*7;
		var dig8 = (str.substring(7, 8))*6;
		var dig9 = (str.substring(8, 9))*5;
		var dig10 = (str.substring(9, 10))*4;
		var dig11 = (str.substring(10, 11))*3;
		var dig12 = (str.substring(11, 12))*2;
		var dig13 = (str.substring(12, 13));
		var digcheck1 = (dig1+dig2+dig3+dig4+dig5+dig6+dig7+dig8+dig9+dig10+dig11+dig12)%11;
		var	digcheck2 = 11-digcheck1;
			digcheck3 =	digcheck2.toString();
		if(digcheck3.length == 2){
			var dig14 = (digcheck3.substring(1, 2));
			
		}else{
			
			var dig14 = digcheck3;
		}

		if(dig14 == dig13){
			document.frm.checkdigit.value = 'pass';
			
		}else{
			$("#textiden").text('รูปแบบหมายเลขบัตร ไม่ถูกต้อง!');
			document.frm.checkdigit.value = 'fail';
			document.getElementById("iden").style.backgroundColor = "#FF6666";
		}
	}
}

function chklist(){
var errmes = "";
var sameid=document.frm.sameid.value;

	if(document.frm.iden.value != ""){
		if(document.frm.checkdigit.value == 'fail'){
			//$("#textiden").text('รูปแบบหมายเลขบัตร ไม่ถูกต้อง!');
			errmes += '\n --รูปแบบหมายเลขบัตร ไม่ถูกต้อง!';
			
		}if(document.frm.checkidensame.value == 'fail'){
			//$("#textiden").text('หมายเลขบัตรซ้ำ กรุณาเปลี่ยน !');
			errmes += '\n --หมายเลขบัตรซ้ำ กรุณาเปลี่ยน!';
			
		}if(document.frm.filedoc.value == ""){
			
			errmes += '\n --กรุณาแนบเอกสาร เช่น สำเนาบัตรประชาชน!';
			
		}if(sameid== document.frm.iden.value){
			errmes += '\n --เลขประจำตัวประชาชนใหม่ เหมือน  เลขประจำตัวประชาชนเดิม!';
		}
		if(errmes != ""){
			alert(errmes);
			return false;
		}
		
		if(document.frm.checkidensame.value == 'pass' && document.frm.checkdigit.value == 'pass'){
			return true;
		}else{
			return false;
		}
	}else{
	
		return false;
	}	
	
}


document.onkeydown = chkEvent 
function chkEvent(e) {
	var keycode;
	if (window.event) keycode = window.event.keyCode; //*** for IE ***//
	else if (e) keycode = e.which; //*** for Firefox ***//
	if(keycode==13)
	{
		return false;
	}
}

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
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <title>Unlock username</title>

<style type="text/css">
BODY{
    font-family: tahoma;
    font-size: 14px;
    color: #585858;
    background-color: #C0C0C0;
    margin: 0 auto;
    padding-top: 20px;
}
H1{
    font-size: 16px;
    color: #585858;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
H2{
    font-size: 20px;
    color: #888800;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
INPUT {
    font-family: tahoma;
    font-size: 14px;
    font-weight: normal;
    /*color: #585858;
    background-color: #E0E0E0;*/
}
HR {
    border: 0;
    color: #ACACAC;
    background-color: #ACACAC;
    height: 1px;
}

.roundedcornr_box {
   background: #ffffff;
   width: 500px;
   margin: auto;
}
.roundedcornr_top div {
   background: url(../../../img/roundedcornr_tl.png) no-repeat top left;
}
.roundedcornr_top {
   background: url(../../../img/roundedcornr_tr.png) no-repeat top right;
}
.roundedcornr_bottom div {
   background: url(../../../img/roundedcornr_bl.png) no-repeat bottom left;
}
.roundedcornr_bottom {
   background: url(../../../img/roundedcornr_br.png) no-repeat bottom right;
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
<?php
 $sqlchk = pg_query("SELECT * FROM \"Re_indentity_cus_temp\" where \"CusID\" = '$cusid' and app_status = '1'");
$rowschk = pg_num_rows($sqlchk);
//ตรวจสอบ ว่าลูกค้าคนนี้ มีการรอการอนุมัติการเปลี่ยนแปลง ข้อมูล หรือไม่
$sqlchkdata = pg_query("SELECT * FROM \"Customer_Temp\" where \"CusID\" = '$cusid' and \"statusapp\" = '2'");
$rowschkdata = pg_num_rows($sqlchkdata);

if(($rowschk=='0'|| empty($rowschk))and ($rowschkdata==0)){ ?>
</head>
<body onload="document.frm.iden.focus();">
<div class="style5" style="width:auto; padding:10px;text-align:center;font-size:20px;">
<b><font color="red">เมนูนี้สำหรับแก้ไขเลขบัตรประชาชนเนื่องจากคีย์ผิดเท่านั้น !!<p>ห้ามแก้ไขเพิ่อเปลี่ยนคน การเปลี่ยนคนให้แจ้งผ่าน HelpDesk</font></b></div>
<div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
      <div class="roundedcornr_content">

<h2>เปลี่ยนระหัสประจำตัวประชาชนลูกค้า</h2>
<hr/>
<div>
<FORM method="post" action="process_add.php" style="margin:0px" name="frm" enctype="multipart/form-data" >
<TABLE width="450" cellspacing="0" cellpadding="3" border="0" align="center">

<TR>
	<?php 
	$sql1 = "SELECT fa1.\"A_FIRNAME\", fa1.\"A_NAME\", fa1.\"A_SIRNAME\",fn.\"N_IDCARD\" FROM \"Fa1\" fa1 join \"Fn\" fn on fa1.\"CusID\" = Fn.\"CusID\"
	where fa1.\"CusID\" = '$cusid'"; 
	$sqlque1 = pg_query($sql1);
	$re1 = pg_fetch_array($sqlque1);
	$iden = str_replace(" ","",$re1['N_IDCARD']);
	$u=$iden;
	?>
    <TD align="right" width="40%" colspan=""><font size="2"><B>ชื่อ-สกุล : </B></font></TD>
    <td colspan="2" width="60%"><?php echo $re1['A_FIRNAME']." ".$re1['A_NAME']." ".$re1['A_SIRNAME']; ?></td>

	
	<input type="hidden" name="checkdigit" id="checkdigit" value="">
	<input type="hidden" name="checkidensame" id="checkidensame" value="">
	<input type="hidden" name="cusID" value="<?php echo $cusid; ?>">
	<input type="hidden" name="sameid" value="<?php echo $iden; ?>">
</TR>
<TR>
    <TD align="right"><font size="2"><B>เลขประจำตัวประชาชนเดิม</B></font></TD>
    <TD colspan="2"> <?php echo $iden; ?></TD>
	
</TR>
<TR>
    <TD align="right"><font size="2"><B>เลขประจำตัวประชาชนใหม่</B></font></TD>
    <TD><INPUT TYPE="text" autocomplete="off" NAME="iden" maxlength="13" id="iden" onblur="javascript :checkid(),digit();" onkeypress="check_num(event)"></TD>
	
</TR>
<TR>
    <TD align="right"><font size="2"><B>เอกสารแนบ</B></font></TD>
    <TD><INPUT TYPE="file" NAME="filedoc" id="filedoc" ><font color="red">*</font></TD>
	
</TR>
<TR>
	<td></td>
	<td colspan="" align="left"><font color="gray" size="2" name="color" id="color"><span name="textiden" id="textiden">ex.xxxxxxxxxxxxx</span></font></td>
</tr>
<tr>
<td colspan="3" align="center"><input type="submit" value="เปลี่ยน" style="width:100px; height:30px;"  onclick="return chklist(this.form);">

<input type="button" value=" กลับ "  style="width:100px; height:30px;"  onclick="parent.location.href='frm_index.php' "></td>
<td></td>
</tr>
</TABLE>
</FORM>
</div>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>
<?php 
}
else if($rowschkdata>0){
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_index.php\">";
	echo "<script type='text/javascript'>alert('ลูกค้าคนนี้มีการ แก้ไขข้อมูลลูกค้า อยู่\\nต้องทำรายการที่ เมนู อนุมัติข้อมูลลูกค้าก่อน!')</script>";
}
else{
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_index.php\">";
	echo "<script type='text/javascript'>alert('ลูกค้าคนนี้อยู่ในระหว่างการรออนุมัติแล้ว\\nโปรดลองใหม่หลังจากการอนุมัติเสร็จสิ้น!')</script>";
}	?>
</body>
</html>