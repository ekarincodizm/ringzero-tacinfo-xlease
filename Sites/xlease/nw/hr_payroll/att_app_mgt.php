<?php
include("../../config/config.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>อนุมัติการลงเวลาแบบพิเศษ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
  <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
    <script src="../../jqueryui/js/datetime_picker.js" type="text/javascript"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script type="text/javascript">

$(function(){

	
	 $('#mm').change(function(){
	
		
		$("#panel").load("att_app_dtl.php?mm="+ $("#mm").val() +"&yy="+ $("#yy").val() );
		
	});	
	$('#yy').change(function(){
	
		
		$("#panel").load("att_app_dtl.php?mm="+ $("#mm").val() +"&yy="+ $("#yy").val());
		
	});	
    


    




});
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>

    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>


        
<fieldset><legend><B>อนุมัติการลงเวลาแบบพิเศษ</B></legend>

<div style="margin:5px">



<span id="showtype2">
<b>เดือน</b>
<select name="mm" id="mm">
<?php
$nowmonth = date("m");
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;

        echo "<option value=\"$a\">$month[$i]</option>";
    
}
echo "<option value=\"0\"  selected >ทั้งหมด</option>";
?>    
</select>
<b>ปี</b>
<select name="yy" id="yy">
<?php
$nowyear = date("Y");
$year_a = $nowyear + 5; 
$year_b =  $nowyear - 5;

$s_b = $year_b+543;

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
</select>
 &nbsp;</span>
</div>

<div id="panel" style="margin:5px"></div>

</fieldset>

		</td>
	</tr>
</table>
<script type="text/javascript">
$("#panel").load("att_app_dtl.php?mm="+ $("#mm").val() +"&yy="+ $("#yy").val() );
</script>
</body>
</html>