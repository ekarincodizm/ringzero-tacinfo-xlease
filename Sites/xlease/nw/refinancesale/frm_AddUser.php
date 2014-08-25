<?php
session_start();
include("../../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>เพิ่มพนักงาน</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript">
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form1.id_users.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกพนักงาน";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_SetUser.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div>   
				
				<fieldset><legend><B>เพิ่มพนักงาน</B></legend>
					<div style="padding:20px;">
					<form method="post" name="form1" action="process_userinvite.php">
					<table width="90%" cellpadding="1" cellspacing="1" border="0" bgcolor="#CCCCCC" align="center">
						<tr height="50" bgcolor="#FFFFFF">
							<td align="center"><b>ค้นหาพนักงาน (รหัส, ชื่อ, สกุล): </b>
							<input type="text" name="id_users" id="id_users" size="60">
							<input name="h_id" type="hidden" id="h_id" value="" />
							<input name="method" type="hidden" value="add">
							</td>
						</tr>
					</table>
					<table width="90%" border="0" align="center">
						<tr height="50" align="center"><td><input type="submit"value="บันทึก" onclick="return checkdata()"><input type="reset"value="ยกเลิก"></td></tr>
					</table>
					<script type="text/javascript">
					function make_autocom(autoObj,showObj){
						var mkAutoObj=autoObj; 
						var mkSerValObj=showObj; 
						new Autocomplete(mkAutoObj, function() {
							this.setValue = function(id) {		
								document.getElementById(mkSerValObj).value = id;
							}
							if ( this.isModified )
								this.setValue("");
							if ( this.value.length < 1 && this.isNotClick ) 
								return ;	
							return "s_user.php?q=" + this.value;
						});	
					}	
					make_autocom("id_users","h_id");
					</script>
					</form>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>