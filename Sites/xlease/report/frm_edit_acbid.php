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

    $('#btn1').click(function(){
        $('#panel').empty();
        $('#panel').html('<img src="progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#panel").load("frm_edit_acbid_show.php?yy="+ $("#yy").val() +"&mm="+ $("#mm").val() +"&ty="+ $("#ty").val());
    });
    
    $('#btnupdate').click(function(){
        $('#panel').empty();
        $('#panel').html('<img src="progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        
        $.post('frm_edit_acbid_save.php',{
            mm: $('#mm').val(),
            yy: $('#yy').val(),
            ty: $('#ty').val()
        },
        function(data){
            if(data.success){
                $("#panel").html(data.message);
                //$("#panel").load("frm_edit_acbid_show.php?yy="+ $("#yy").val() +"&mm="+ $("#mm").val() +"&ty="+ $("#ty").val());
            }else{
                $("#panel").html(data.message);
            }
        },'json');
    });

});
</script>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both"></div>
        
<fieldset><legend><B>แก้ไข acb_id</B></legend>

<div style="margin:5px">

<b>ประเภท</b>
<select name="ty" id="ty">
    <option value="">เลือก</option>
    <option value="GJ">GJ</option>
    <option value="RC">RC</option>
    <option value="PC">PC</option>
    <option value="VR">VR</option>
    <option value="VP">VP</option>
    <option value="AJ">AJ</option>
    <option value="AP">AP</option>
</select>

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

<input type="button" name="btn1" id="btn1" value="ค้นหา">&nbsp;/&nbsp;<input type="button" name="btnupdate" id="btnupdate" value="เริ่มทำการจัดเรียงใหม่">
</div>

<div id="panel" style="margin:5px"></div>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>