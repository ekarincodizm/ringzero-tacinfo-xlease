<?php
include("../../config/config.php");

$method = pg_escape_string($_GET["method"]);
$autoid = pg_escape_string($_GET["autoid"]);
$show = pg_escape_string($_GET["show"]);

if($method=='edit'){
	$qrysendName=pg_query("SELECT  \"sendName\" FROM thcap_letter_head where \"auto_id\"='$autoid'  ");
	$sendname = pg_fetch_result($qrysendName,0);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<?php if($method=='edit'){?>
		  <title>แก้ไขชื่อประเภทของจดหมาย</title>	
	<?php }else {?>
		  <title>เพิ่มชื่อประเภทของจดหมาย</title>	
	<?php }?>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function validate() 
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	
	
	if (document.frm1.accname.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุ ชื่อประเภทของจดหมาย";
	}
	if (document.frm1.chk_typename.value=="1") {
	theMessage = theMessage + "\n -->  ชื่อประเภทของจดหมาย มีแล้วในระบบ";
	}
	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function chk_conif(){	
	$.post('chk_typename.php',{					
			name:$('#accname').val()
	},function(data){
		if(data == 0){
			document.getElementById("chk_typename").value= 0;
			document.getElementById("accname").style.backgroundColor="#98FB98";
		}
		else{
			document.getElementById("chk_typename").value= 1;
			document.getElementById("accname").style.backgroundColor="#FF6A6A";
		}
	});
}
</script>

</head>

<body>
<form name="frm1" method="post" action="process_addtype.php">
<input  id="chk_typename" name="chk_typename"   hidden>	
<input  id="autoid" name="autoid" value="<?php echo $autoid ;?>"  hidden>	
<input  id="show" name="show" value="<?php echo $show ;?>"  hidden>
<center><h2><?php if($method=="edit"){ echo "แก้ไข";}?>ชื่อประเภทของจดหมาย</h2></center>
<center>
<input type="text" name="method" value='<?php echo $method;?>' hidden>
<table border="0">	
	<?php if($method=='edit'){ ?>
	<tr>
		<td align="right">ชื่อประเภทของจดหมายเดิม : </td><td><input type="text" name="accnameold" size="30" value="<?php echo $sendname;?>" readonly><font color="#FF0000"></font></td>
	</tr>
	<?php } ?>
	<tr>
		<td align="right">ชื่อประเภทของจดหมาย : </td><td><input type="text" name="accname" id="accname" size="30"onblur="chk_conif();" onChange="chk_conif();" onKeyUp="chk_conif();" ><font color="#FF0000"> *</font></td>
	</tr>	
</table>
<br><br>
<input type="submit" name="add" value="บันทึก" onclick="return validate();"> &nbsp;&nbsp;&nbsp; 
<input type="button" value="ยกเลิก/ปิด" onclick="javascript:window.close();">
</center>
</form>
</body>
</html>