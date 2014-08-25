<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
var counter = 0;
var myarray;
var mystring;
$(document).ready(function(){
    
    $("#tb_idno").focus();
    
    $("#tb_idno").autocomplete({
        source: "s_cusid.php",
        minLength:1
    });
    
    $('#btn_search_idno').click(function(){
        var aaaa = $("#tb_idno").val();
        var brokenstring=aaaa.split("#");
        document.location="detail3.php?RadioNum="+ brokenstring[1] +"&COID="+ brokenstring[0]+"&CusID="+ brokenstring[3];
    });
    
});
</script>
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="text-align:center;"><h1>POST รายการวิทยุ</h1></div>
<div style="clear:both"></div>

<fieldset><legend><B>TAC-TR</B></legend>

<div class="ui-widget">

<div style="padding: 5px 0 10px 0;text-align:center;">
<b>เลขที่สัญญาวิทยุ, รหัสวิทยุ, ทะเบียนรถ : </b><input name="tb_idno" id="tb_idno" type="text" size="60">&nbsp;<input name="btn_search_idno" id="btn_search_idno" type="button" value="Next >>">
</div>

</div>

 </fieldset>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
        </td>
    </tr>
</table>

</body>
</html>