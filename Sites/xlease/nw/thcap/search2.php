<?php
include("../../config/config.php");
?>

<html>
<head>
<title>ค้นหาเลขที่สัญญาจากชื่อผู้กู้หลักหรือชื่อผู้กู้ร่วม</title>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->

	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#input").autocomplete({
        source: "s_contractNAME.php",
        minLength:1
    });
});
</script>
</head>
 
<body>
<script language="JavaScript">
function updateOpener() {
window.opener.document.forms[0].id.value =
document.forms[0].input.value;

window.opener.document.forms[0].btn1.click();

window.close();
}
</script>

<center>
<br><br>
<form name="form">
ค้นหาเลขที่สัญญา จากชื่อผู้กู้หลัก/ร่วม:
<input type="text" name="input" id="input" size="30"></input>
<input name="Close" type="submit" id="Close"
onClick="Javascript:updateOpener()" value="ตกลง"></input>
</form>
</center>

</body>
</html>