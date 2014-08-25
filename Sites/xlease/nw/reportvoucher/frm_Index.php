<?php
include("../../config/config.php");
list($year,$month,$day) = explode("-",nowDate());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">

    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#userreceive").autocomplete({
        source: "s_user.php",
        minLength:2
    });
	
    $('#btnshow').click(function(){
        var aaaa = $("#userreceive").val();
        var brokenstring=aaaa.split("-");
		
		$("#divshow").html('<img src="../../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#divshow").load('frm_Report.php?month='+$("#month").val()+'&year='+$("#year").val()+'&userreceive='+brokenstring[0]+'&detail='+$("#detail").val());
    });
});
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:11px;
    text-align:center;
}
</style>

</head>
<body>

<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>Voucher รายงานสรุปประจำเดือน</B></legend>

<div align="center">
<b>ประจำเดือน</b>
<select name="month" id="month">
	<option value="01" <?php if($month=="01") echo "selected";?>>มกราคม</option>
	<option value="02" <?php if($month=="02") echo "selected";?>>กุมภาพันธ์</option>
	<option value="03" <?php if($month=="03") echo "selected";?>>มีนาคม</option>
	<option value="04" <?php if($month=="04") echo "selected";?>>เมษายน</option>
	<option value="05" <?php if($month=="05") echo "selected";?>>พฤษภาคม</option>
	<option value="06" <?php if($month=="06") echo "selected";?>>มิถุนายน</option>
	<option value="07" <?php if($month=="07") echo "selected";?>>กรกฎาคม</option>
	<option value="08" <?php if($month=="08") echo "selected";?>>สิงหาคม</option>
	<option value="09" <?php if($month=="09") echo "selected";?>>กันยายน</option>
	<option value="10" <?php if($month=="10") echo "selected";?>>ตุลาคม</option>
	<option value="11" <?php if($month=="11") echo "selected";?>>พฤศจิกายน</option>
	<option value="12" <?php if($month=="12") echo "selected";?>>ธันวาคม</option>
</select>
<b>ค.ศ.</b><input type="text" id="year" name="year" value="<?php echo $year; ?>" size="10" style="text-align:center" maxlength="4">
<b>ผู้รับเงิน:</b> <input type="text" name="userreceive" id="userreceive" size="60">
<input type="submit" name="btnshow" id="btnshow" value="แสดง">
<div align="center">
<b>รายละเอียด :</b> <input type="text" name="detail" id="detail" size="60"align="center">

</div>

<div id="divshow" style="margin-top:10px"></div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>