<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ค้นหาบริษัทที่ติดต่อ</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){


    $('#btncom').click(function(){
        $("#panel1").load("fu_company_search.php");
    });
	
	$('#btnemp').click(function(){
        $("#panel1").load("fu_empcontact_search.php");
    });
	
	$('#btncont').click(function(){
        $("#panel1").load("fu_conversation_search.php");
    });
	
	$('#btnemail').click(function(){
        $("#panel1").load("fu_email_index.php");
    });
	
	$('#btntag').click(function(){
        $("#panel1").load("fu_tag_search.php");
    });

});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
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
</head>

<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;">
<span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ติดตามการสนทนา</B></legend>

<div align="center">
<div class="style5" style="width:auto; height:40px; padding-left:10px;">
  <b></b>
    <input type="button" value="บริษัท" id="btncom" style="width:100px; height:30px;" />
	<input type="button" value="ผู้ติดต่อ" id="btnemp" style="width:100px; height:30px;" />
	<input type="button" value="การสนทนา" id="btncont"  style="width:100px; height:30px;"/>
	<input type="button" value="การติดตาม" id="btntag" style="width:100px; height:30px;" />
	<input type="button" value="E-mail" id="btnemail"  style="width:100px; height:30px;"/>
	<input type="button" name="bt_select" id="bt_select" value="รายงานการติดตาม" onclick="javascript:popU('fu_report.php','toolbar=yes,menubar=yes,resizable=yes,scrollbars=yes,status=no,location=no')" style="width:100px; height:30px;">
    <input name="button" type="button" onclick="window.close();" value="ปิด" style="width:100px; height:30px;"/>
  </div>



</div>

 </fieldset>

        </td>
    </tr>
</table>
<div id="panel1" style="padding-top: 10px;"></div>



</html>
