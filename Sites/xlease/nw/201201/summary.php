<?php
include("../../config/config.php");
$yearnow=date('Y');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<title>จัดการยอดสรุปสิ้นเดือน</title>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
function acceptOnlyDigit(event,el){
   var e=window.event?window.event:event;
   var keyCode=e.keyCode?e.keyCode:e.which?e.which:e.charCode;  
    //0-9 (numpad,keyboard)
   if ((keyCode>=96 && keyCode<=105)||(keyCode>=48 && keyCode<=57)){
    return true;
   }
   //backspace,delete,left,right,home,end
   if (',8,46,37,39,36,35,'.indexOf(','+keyCode+',')!=-1){
    return true;
   }  
   return false;
 }
</script>
</head>
<body>
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>       
			<div style="float:right"><input type="button" value="  Back  " onclick="window.location='frm_Index.php'"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<div style="clear:both;"></div>
			<div style="text-align:center;"><h2>จัดการยอดสรุปสิ้นเดือน</h2></div>
			<fieldset><legend>ระบุเงื่อนไขในการคำนวณ</legend>
			<div align="center" style="padding:10px;text-align:center;">
				<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td><b>ประจำเดือน :</b>
					<select name="mount" id="mount">
						<option value="">----เดือน----</option>
						<option value="01">มกราคม</option>
						<option value="02">กุมภาพันธ์</option>
						<option value="03">มีนาคม</option>
						<option value="04">เมษายน</option>
						<option value="05">พฤษภาคม</option>
						<option value="06">มิถุนายน</option>
						<option value="07">กรกฎาคม</option>
						<option value="08">สิงหาคม</option>
						<option value="09">กันยายน</option>
						<option value="10">ตุลาคม</option>
						<option value="11">พฤศจิกายน</option>
						<option value="12">ธันวาคม</option>
					</select>
					<b>ปี ค.ศ.</b><input type="text" name="year" id="year" size="10" value="<?php echo $yearnow;?>" style="text-align:center;" maxlength="4" onkeydown="return acceptOnlyDigit(event,this)"> <font color="red">( เช่น ปี ค.ศ.2012)</font>
					</td>
				</tr>
				<tr>
					<td height="50"><input type="button" value="เริ่มจัดการข้อมูล" id="submitButton"></td>
				</tr>
				</table>				
			</div>
			</fieldset>
        </td>
    </tr>
</table>

<script type="text/javascript">
var counter = 1;
var counter2 = 1;
$(document).ready(function(){
$("#submitButton").click(function(){		
        $("#submitButton").attr('disabled', true);
	
		if ( $("#mount").val() == ""){
			alert('กรุณาเลือกเดือนที่ต้องการ');
			$('#mount').focus();
			$("#submitButton").attr('disabled', false);
            return false;
        }
		if ( $("#year").val() == ""){
			alert('กรุณาระบุปี ค.ศ. ที่ต้องการ');
			$('#year').focus();
			$("#submitButton").attr('disabled', false);
            return false;
        }
			  
        $.post("detail_pgf_sum.php",{
            cmd : "save" , 
            mount :$("#mount").val() , 
			year :$("#year").val()
        },
        function(data){
            if(data == 1){
                alert("บันทึกรายการเรียบร้อย");
                location.href = "summary.php";
                $("#submitButton").attr('disabled', false);
            }else{
				//alert(data);
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
                $("#submitButton").attr('disabled', false);
            }
        });

    });
});
</script>
</body>
</html>