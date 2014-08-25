<?php 
include("../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$nowyear = date("Y");
$year_a = $nowyear + 5; 
$year_b =  $nowyear - 5;
$s_b = $year_b+543;
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    //$("#showpanel").text("กำลังโหลดข้อมูลกรุณารอสักครู่...");
    //$("#showpanel").load("frm_soyendyear_panel.php?yy=<?php echo $nowyear; ?>");
    
    $('#btnsubmit').click(function(){
        $("#showpanel").text("กำลังโหลดข้อมูลกรุณารอสักครู่...");
        $("#showpanel").load("frm_soyendyear_panel.php?yy="+ $("#yy").val());
    });
});
</script>
    
    <script type="text/javascript">
    var wnd = new Array();
    function popU(U,N,T){
        wnd[N] = window.open(U, N, T);
    }
    </script>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="header"><h1>รายงานรับรู้รายได้ประจำปี</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>รายงานแสดงการรับรู้รายได้ตามงวดค้าง ประจำปี</b></legend>

<div style="float:left">
<input name="button" type="button" onclick="window.location='frm_soyendyear.php'" value="ตามงวดค้าง" disabled/>
<input name="button" type="button" onclick="window.location='frm_soyendyear_year.php'" value="ตามปีสัญญา"/>
</div>
<div style="float:right"><input type="button" name="btnrun" id="btnrun" value="Run รับรู้รายได้ใหม่" onclick="javascript:popU('frm_soy_run.php','dasd1e1qweq1we1q2w3e1','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=450,height=300');"></div>
<div style="clear:both;"></div>

<div style="float:left">
<b>เลือกปี</b> 
<select name="yy" id="yy">
<?php
while($year_b <= $year_a){
    if($nowyear != $year_b){
        echo "<option value=\"$year_b\">$s_b</option>";
    }else{
        echo "<option value=\"$year_b\" selected>$s_b</option>";
    }
    $year_b += 1;
    $s_b +=1;
}
?>
</select><input type="button" name="btnsubmit" id="btnsubmit" value="ค้นหา">
</div>
<div style="float:right"></div>
<div style="clear:both;"></div>

<div id="showpanel" style="text-align:center"></div>

</fieldset>

<div align="center"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

</div>

		</td>
	</tr>
</table>

</body>
</html>