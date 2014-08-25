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
    $('#btn00').click(function(){
        $("#btn00").attr('disabled', true);
        $("#panel").text('กำลังค้นหาข้อมูล ....');
        $("#panel").load("tax_buy_panel.php?yy="+ $("#yy").val() +"&mm="+ $("#mm").val() );
        $("#btn00").attr('disabled', false);
    });
});
</script>
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left">&nbsp;</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>รายงานภาษีซื้อ</B></legend>
<div class="ui-widget" align="center">

<b>เลือกเดือน</b>
<select name="mm" id="mm">
<?php
$cur_month = date('m');
$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม ","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน ","ธันวาคม");
for($i=0; $i<=11; $i++){
    $m = $i+1;
    if($m > 0 && $m < 10){
        $m = "0".$m;
    }

    if($m == $cur_month)
        echo "<option value=\"$m\" selected>$thaimonth[$i]</option>";
    else
        echo "<option value=\"$m\">$thaimonth[$i]</option>";
}
?>
</select>

<b>ปี</b>
<select name="yy" id="yy">
<?php
$cur_year = date('Y');
for($a=($cur_year-3); $a<=($cur_year+3); $a++){
    if($a == $cur_year)
        echo "<option value=\"$a\" selected>$a</option>";
    else
        echo "<option value=\"$a\">$a</option>";
}
?>
</select>

<input type="button" id="btn00" value="เริ่มค้น"/></p>

<div id="panel"></div>

</div>
 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>