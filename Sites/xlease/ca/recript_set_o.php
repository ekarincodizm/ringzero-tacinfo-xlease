<?php
include("../config/config.php");
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
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<script type="text/javascript">
$(function(){
    
    $('#showtype1').hide();
    $('#showtype2').hide();

    $('#ty').change(function(){
        if($('#ty').val() == 1){
            $('#showtype1').show();
            $('#showtype2').hide();
        }else if($('#ty').val() == 2){
            $('#showtype1').hide();
            $('#showtype2').show();
        }else{
            $('#showtype1').hide();
            $('#showtype2').hide();
        }
    });
    
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    
    $('#btn1').click(function(){
        if($('#ty').val() == ""){
            alert('กรุณาเลือกรูปแบบก่อนค่ะ');
            return false;
        }
        $('#panel').empty();
        $('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#panel").load("recript_set_o_panel.php?datepicker="+ $("#datepicker").val() +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val() +"&ty="+ $("#ty").val());
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
</style>

    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>

<div style="float:left"><input type="button" value=" Back " class="ui-button" onclick="window.location='recript_set.php'"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both"></div>
        
<fieldset><legend><B>พิมพ์ใบเสร็จเป็นชุด - ค่าอื่นๆ</B></legend>

<div style="margin:5px">

<b>เลือกรูปแบบ</b>
<select name="ty" id="ty">
    <option value="">เลือก</option>
    <option value="1">ประจำวัน</option>
    <option value="2">ประจำเดือน</option>
</select>

<span id="showtype1">
<input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15" style="text-align:center">
</span>

<span id="showtype2">
<b>เดือน</b>
<select name="mm" id="mm">
<?php
$nowmonth = date("m");
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;
    if($nowmonth != $a){
        echo "<option value=\"$a\">$month[$i]</option>";
    }else{
        echo "<option value=\"$a\" selected>$month[$i]</option>";
    }
}
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
</span>

<input type="button" name="btn1" id="btn1" value="ค้นหา">

</div>

<div id="panel" style="margin:5px"></div>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>