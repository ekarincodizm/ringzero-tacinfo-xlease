<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
var counter = 0;
var myarray;
var mystring;
$(document).ready(function(){
    
    $("#h_id").focus();
    
    $("#h_id").autocomplete({
        source: "listdata_acc_un.php",
        minLength:1
    });
    
});
function checkField(){
	var text = document.getElementById("h_id").value;
		if(text==""||text==null){
			alert("กรุณาตรวจสอบ IDNO");
			return false;
		}
}
</script>
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>รับเงินสดใบเสร็จชั่วคราว</B></legend>

<div class="ui-widget">

<div style="padding: 5px 0 10px 0">
    <form method="post" action="frm_enter_detail.php" onsubmit="return checkField()">
    <b>ตรวจสอบ IDNO : </b><input name="h_id" id="h_id" type="text" size="80">&nbsp;<input name="btn_search_idno" id="btn_search_idno" type="submit" value="NEXT">
    </form>
</div>

</div>

 </fieldset>

        </td>

    </tr>
</table>

</body>
</html>