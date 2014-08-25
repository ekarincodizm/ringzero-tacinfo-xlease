<?php
include("../../config/config.php");
$idc = $_POST["idc"];
$theradio = $_POST["theradio2"];
$radiocode = $_POST["radiocode2"];
$car = $_POST["car2"];
$my = $_POST["my2"];
?>
<head>
<title>เพิ่มสัญญาวิทยุ (ลูกค้านอก)</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
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
<script language=javascript>
$(document).ready(function(){
$("#s_idno").autocomplete({
        source: "s_idno.php",
        minLength:1
    });
});

function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage

if (document.form1.theradio.value=="") {
    theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.radiocode.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.car.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.s_idno.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}

/*if(document.form1.theradio.value=="" or document.form1.radiocode.value=="" or document.form1.car.value=="")
{
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}*/

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}
</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">เพิ่มสัญญาวิทยุ (ลูกค้านอก)<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<!-- <form method="post" name="form1" action="pAddRadioContract.php"> -->
			<form method="post" name="form1" action="pAddRadioContract.php">
			<table width="600" border="0" style="background-color:#EEF2DB;" cellspacing="1" align="center">
			<tr><th colspan="2"><?php echo $showtext;?></th></tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th width="50%" height="25" align="right"><br><br>สัญญาวิทยุ :</th>
				<?php echo "<td><br><br><input type=\"text\" name=\"theradio\" value=\"$theradio\"></td></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right">รหัสวิทยุ :</th>
				<?php echo "<td><input type=\"text\" name=\"radiocode\" value=\"$radiocode\"></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;" align="left">
				<th height="25" align="right" valign="top">ทะเบียนรถยนต์ :</th>
				<?php echo "<td><input type=\"text\" name=\"car\" value=\"$car\"></td>"; ?>
			</tr>
			<tr style="background-color:#D0DCA0;">
				<th align="right" valign="top">เจ้าของวิทยุ :</th>
				<td>
					<?php echo "<input type=\"text\" name=\"s_idno\" id=\"s_idno\" size=\"50\" value=\"$my\">"; ?>
					<br><br>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center" height="50"><input type="submit" value="เพิ่มสัญญา" onclick="return validate()"><input type="button" value="    ปิด    " onclick="window.close();"></td>
			</tr>
			</table>
			</form>
			
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
			return "listdata_customer.php?q=" + this.value;
		});
	}

	make_autocom("idno_names","h_id");
	</script>
			<!-- </form> -->
		</div>
		
	</div>
</div>
<div style="padding-top:50px;">
			<?php include("frm_wait_appv.php"); ?>
</div>
</body>
</html>