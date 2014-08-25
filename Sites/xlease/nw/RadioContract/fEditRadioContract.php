<?php
include("../../config/config.php");

$coid = $_POST["s_idno_t"];
$radionum = $_POST["s_radionum"];
$radiocar = $_POST["s_radiocar"];
$name = $_POST["s_name"];
$sirname = $_POST["s_sirname"];

//echo $radionum."<br>";

$f_sql=pg_query("select * from public.\"RadioContract\" where \"AppvID\" is null and (\"COID\" = '$coid' or \"COID\" = '$radionum' or \"COID\" = '$radiocar' or \"COID\" = '$name' or \"COID\" = '$sirname') ");
$rownum=pg_num_rows($f_sql);
if($rownum!=1)
{
	echo "<form method=\"post\" name=\"form1\" action=\"sEditRadioContract.php\">";
	echo "<center><h1><b>ไม่พบข้อมูล!  <b></h1><h2>กรุณาทำรายการใหม่</h2></center><br>";
	echo "<input type=\"hidden\" name=\"coid2\" value=\"$coid\">";
	echo "<input type=\"hidden\" name=\"radionum2\" value=\"$radionum\">";
	echo "<input type=\"hidden\" name=\"radiocar2\" value=\"$radiocar\">";
	echo "<input type=\"hidden\" name=\"name2\" value=\"$name\">";
	echo "<input type=\"hidden\" name=\"sirname2\" value=\"$sirname\">";
	echo "<center><input type=\"submit\" value=\"    กลับ    \"></center>";
	echo "</form>";
}
else
{
?>
<html>
<head>
<title>แก้ไขสัญญาวิทยุ (ลูกค้านอก)</title>
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

if (document.form1.coid.value=="") {
    theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.radionum.value==""){
	theMessage = theMessage + "\n -->  กรอกข้อมูลไม่ครบถ้วน";
}
else if(document.form1.radiocar.value==""){
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
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">แก้ไขสัญญาวิทยุ (ลูกค้านอก)<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
		<form method="post" name="form1" action="pEditRadioContract.php">
			<?php
			$L_sql=pg_query("select \"RadioContract\".\"COID\" , \"RadioContract\".\"RadioNum\" , \"RadioContract\".\"RadioCar\" , \"RadioContract\".\"RadioRelationID\" from public.\"RadioContract\" , public.\"GroupCus_Active\" where \"RadioContract\".\"COID\" = '$coid' or \"RadioContract\".\"COID\" = '$radionum' or \"RadioContract\".\"COID\" = '$radiocar' or \"RadioContract\".\"COID\" = '$name' or \"RadioContract\".\"COID\" = '$sirname' and \"RadioContract\".\"RadioRelationID\" = \"GroupCus_Active\".\"GroupCusID\" ");
			while($result=pg_fetch_array($L_sql)){
							$COID=$result["COID"];
							$RadioNum=$result["RadioNum"];
							$RadioCar=$result["RadioCar"];
							$RadioRelationID=$result["RadioRelationID"];
							}
			$L2_sql=pg_query("select * from public.\"GroupCus_Active\" where \"GroupCusID\" = '$RadioRelationID' ");
			while($result2=pg_fetch_array($L2_sql)){
							$CusID=trim($result2["CusID"]);
							}
			/*echo $COID."<br>";
			echo $RadioNum."<br>";
			echo $RadioCar."<br>";
			echo $CusID;*/
			
			echo "<center><table>";
			echo "<tr><td align=\"right\">สัญญาวิทยุ: </td><td><input type=\"text\" name=\"coid\" disabled=\"disabled\" value=\"$COID\"></td></tr>";
			echo "<tr><td align=\"right\">รหัสวิทยุ: </td><td><input type=\"text\" name=\"radionum\" value=\"$RadioNum\"></td></tr>";
			echo "<tr><td align=\"right\">ทะเบียนรถ: </td><td><input type=\"text\" name=\"radiocar\" value=\"$RadioCar\"></td></tr>";
			echo "<tr><td align=\"right\">เจ้าของวิทยุ: </td><td><input type=\"text\" name=\"s_idno\" id=\"s_idno\" value=\"$CusID\"></td></tr></table></center>";
			echo "<input type=\"hidden\" name=\"coid2\" value=\"$COID\">";
			echo "<input type=\"hidden\" name=\"cusid\" value=\"$CusID\">";
			echo "<input type=\"hidden\" name=\"radioralationid\" value=\"$RadioRelationID\">";
			?>
			<br><center><input type="submit" value="แก้ไข" onclick="return validate()"> <input type="button" value="กลับ" onclick="window.location='sEditRadioContract.php'"></center>
		</form>
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>
<?php	
}
?>