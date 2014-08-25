<?php
session_start();
include("../../config/config.php");
$IDNO = $_GET["idno"];
$CusID = $_GET["cusid"];
$asset_id = $_GET["asset_id"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>จับคู่เลขที่สัญญา</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript">
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form1.idnonew.value=="") {
	theMessage = theMessage + "\n -->  กรุณาเลือกเลขที่สัญญา";
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
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div>   
				
				<fieldset><legend><B>จับคู่เลขที่สัญญา</B></legend>
					<div style="padding:20px;">
					<form method="post" name="form1" action="process_match.php">
					<table width="98%" cellpadding="1" cellspacing="1" border="0" bgcolor="#CCCCCC" align="center">
					<tr><td>
					<table width="100%" border="0" align="center" bgcolor="#FFFFFF">
						<tr height="30">
							<td align="right"><b>เลขที่สัญญาเดิม :</b> </td><td><?php echo $IDNO;?>
							<input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id?>">
							<input type="hidden" name="CusID"  value="<?php echo $CusID?>">
							<input name="h_id" type="hidden" id="h_id" value="" /></td>				
						</tr>
						<tr height="50">
							<td align="right"><b>ค้นหาเลขที่สัญญาใหม่ : </b></td><td>
							<input type="text" name="idno_new" id="idno_new" size="80">
							<input type="hidden" name="idno_old"  id="idno_old" value="<?php echo $IDNO?>" />
							</td>
						</tr>
					</table>
					</td></tr></table>
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
							return "listdata.php?q=" + this.value;
						});	
					}	
					make_autocom("idno_new","h_id");
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