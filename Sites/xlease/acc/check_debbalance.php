<?php
include("../config/config.php");
$nowdate = nowDate();//ดึง วันที่จาก server
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
    $('#btnshow').click(function(){
        $('#panel').html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#panel").load("check_debbalance_show.php?date="+ $("#datepicker").val() +"&yy="+ $("#yy").val() );
    });

    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
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

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ตรวจสอบลูกหนี้คงเหลือ</B></legend>

<div align="center">

<b>วันที่</b>&nbsp;<input type="text" id="datepicker" name="datepicker" value="<?php echo $nowdate; ?>" size="15" style="text-align:center">
<b>ลูกหนี้ปี</b>&nbsp;
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
<input type="button" id="btnshow" value="แสดง"/></p>
<div id="panel" align="left" style="margin-top:10px"></div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>