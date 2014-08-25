<?php
include("../../config/config.php");

$coid3 = $_POST["coid2"];
$radionum3 = $_POST["radionum2"];
$radiocar3 = $_POST["radiocar2"];
$name3 = $_POST["name2"];
$sirname3 = $_POST["sirname2"];
?>

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
	$("#s_idno_t").autocomplete({
        source: "s_idno_RadioContract.php",
        minLength:1
    });
});
</script>
</head>

<body>
<div style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">แก้ไขสัญญาวิทยุ (ลูกค้านอก)<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<center><b>กรุณาเลือกสัญญาวิทยุที่ต้องการจะแก้ไข</b></center><br>
			<center>โดยสามารถค้นหาได้จาก สัญญาวิทยุ , รหัสวิทยุ , ทะเบียนรถ , ชื่อ นามสกุล ของลูกค้าที่ผูกอยู่กับ สัญญาวิทยุนั้นๆ</center><br><br>
			<?php
			echo " <center><table> ";
			echo " <form method=\"post\" name=\"form1\" action=\"fEditRadioContract.php\"> ";
			echo " <tr><td align=\"right\">ค้นหา : </td><td><input type=\"text\" name=\"s_idno_t\" id=\"s_idno_t\" size=\"85\" value=\"$coid3\"> </td><td><input type=\"submit\" value=\"ตกลง\"></td></tr><tr></tr> ";
			echo " </table></center><br> ";
			echo "</form>";
			?>
		</div>
		<div id="footerpage"></div>
	</div>
</div>
</body>
</html>