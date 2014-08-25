<?php 
set_time_limit(0);
include("../config/config.php");
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#btn1_submit').click(function(){
        $("#divshow").empty();
        $("#divshow").text('กำลังค้นหาข้อมูล กรุณารอสักครู่...');
        $("#divshow").load("frm_car_search_panel.php?mm="+ $("#mm").val() +"&yy="+ $("#yy").val());
    });
});
</script>
    
<script language="Javascript">
function selectAll(select)
{
    with (document.f_2)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
        
        if(checkval == true)          
            document.f_2.button2.disabled = false;
        else
            document.f_2.button2.disabled = true;
    }
}

function selectDisable(field){
    var temp=0;
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ) temp = temp+1;
    
    if(temp > 0){
        document.f_2.button2.disabled = false;
    }else{
        document.f_2.button2.disabled = true;
    }
    
    

}

function CheckSelect(field) {
    var temp=0;
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ) temp = temp+1;
    
    if(temp > 0) {
        return true;
    } else {
        alert('กรุณาเลือกข้อมูล');
        return false;
    }
}
</script>    

</head>
<body>

<?php include("menu.php"); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>

<fieldset><legend><b>สร้างรายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี</b></legend>

<div align="right">
<b>เดือน</b>
<select name="mm" id="mm">
<?php
if(empty($mm)){
    $nowmonth = date("m");
}else{
    $nowmonth = $mm;
}
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
if(empty($yy)){
    $nowyear = date("Y");
}else{
    $nowyear = $yy;
}
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
</select><input type="button" name="btn1_submit" id="btn1_submit" value="ค้นหา">
</div>

<div id="divshow"></div>

		</td>
	</tr>
</table>

</body>
</html>