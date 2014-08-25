<?php
include("../config/config.php");
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
$(document).ready(function(){
    $('#btnsearch').click(function(){
        $('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#panel").load("beginx_panel.php?mm="+ $("#mm").val() +"&yy="+ $("#yy").val() );
    });
});

function showedit(id,m){
    $('body').append('<div id="dialog"></div>');
    $('#dialog').load("beginx_edit.php?mm="+ $("#mm").val() +"&yy="+ $("#yy").val() + "&id="+id+"&m="+m);
    $('#dialog').dialog({
        title: 'แก้ไข '+id,
        resizable: false,
        modal: true,  
        width: 300,
        height: 150,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });
}
</script>
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>บันทึกต้นทุนรถ</B></legend>

<div align="center">

<b>เดือน</b>&nbsp;
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

<b>เลือก ปี</b>
<select name="yy" id="yy">
<?php
$nowyear = date("Y");
$year_a = $nowyear + 5; 
$year_b =  $nowyear - 10;

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

<input type="button" id="btnsearch" value="ค้นหา">

<div id="panel" align="left" style="margin-top:10px"></div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>