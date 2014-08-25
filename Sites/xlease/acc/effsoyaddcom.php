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
function replayStatus(){
    if($("#panel").html()=="") $("#panel").html("กำลังประมวลผล กรุณารอสักครู่...");
    else $("#panel").html("");
}

$(document).ready(function(){
    $('#btn00').click(function(){
        $("#panel").empty();
        var divplaying= setInterval("replayStatus()", 500);
        $("#panel").load("effsoyaddcom_panel.php?yy="+ $("#yy").val() +"&datepicker="+ $("#datepicker").val(), function(response, status, xhr){
            if (status == "success"){
                clearInterval(divplaying);
            }
        });
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

<fieldset><legend><B>ต้นทุนเริ่มแรก</B></legend>
<div class="ui-widget" align="left">
<b>วันที่ปิดบัญชี</b> <input type="text" id="datepicker" name="datepicker" value="<?php echo nowDate(); ?>" size="15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>ลูกหนี้ปี</b>
<select name="yy" id="yy">
<?php
$cur_year = date('Y');
for($a=($cur_year-5); $a<=($cur_year+5); $a++){
    if($a == $cur_year)
        echo "<option value=\"$a\" selected>$a</option>";
    else
        echo "<option value=\"$a\">$a</option>";
}
?>
</select>
<input type="button" id="btn00" value="เริ่มค้น"/>

<div id="panel"></div>

</div>
 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>