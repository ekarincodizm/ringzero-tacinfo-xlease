<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>อนุมัติสัญญาเช่าซื้อ</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
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

<script type="text/javascript">
$(document).ready(function(){

    $("#idno_names_m").autocomplete({
        source: "listdata2.php",
        minLength:2
    });

    $('#btn1').click(function(){
		var aaaa = $("#idno_names_m").val();
        var brokenstring=aaaa.split("#");
        $("#panel").load("frm_confrim.php?IDNO="+ brokenstring[0]);
    });

});
</script>
</head>

<body>
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:800px;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:25px; padding-left:10px; padding-top:10px; padding-right:10px;">อนุมัติสัญญาเช่าซื้อ <hr /></div>
<div id="contentpage" style="height:auto;">
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style5" style="width:auto; height:100px; padding:10px;">
  Lock FP
  
  ตรวจสอบ IDNO
    <input type="text" size="50" id="idno_names_m" name="idno_names_m" style="height:20;"/>
    <input type="button" value="SEARCH" id="btn1"/>
    <input name="button" type="button" onclick="window.close();" value="CLOSE" />
  </div>
</div>
</div>
<div id="footerpage"></div>
</div>
</div>
<div id="panel" style="padding-top: 10px;"></div>
</body>
</html>
