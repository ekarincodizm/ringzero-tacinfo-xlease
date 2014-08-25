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

    function replayStatus(){
        if($("#panel").html()=="") $("#panel").html("กำลังประมวลผล กรุณารอสักครู่...");
        else $("#panel").html("");
    }
    
    $('#btn1').click(function(){
        $("#panel").empty();
        var divplaying= setInterval("replayStatus()", 500);
        $("#panel").load("frm_begin_show.php?yy="+ $("#yy").val(), function(response, status, xhr){
            if (status == "success"){
                clearInterval(divplaying);
            }
        });
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
        
<fieldset><legend><B>ตั้งบัญชียกมา 1/1</B></legend>

<div style="margin:5px">วันที่ <b>1</b> เดือน <b>มกราคม</b> ปี 
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
<input type="button" name="btn1" id="btn1" value="ค้นหา">
</div>

<div id="panel"></div>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>