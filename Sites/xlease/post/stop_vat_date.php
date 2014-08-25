<?php
session_start();

$idno = $_GET['idno'];
$date = $_GET['date'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<fieldset><legend><B>Stop VAT</B></legend>

<form name="frm_1" action="stop_vat_date_send.php" method="post">
<input type="hidden" name="idno" value="<?php echo $idno; ?>">
<input type="hidden" name="chkdate" value="<?php echo $date; ?>">
<table width="100%">
<tr>
    <td>IDNO</td><td><?php echo $idno; ?></td>
</tr>
<tr>
    <td>วันที่หยุด VAT</td><td><input type="text" size="13" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo nowDate(); ?>" /><input name="button2" type="button" onclick="displayCalendar(document.frm_1.signDate,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
</tr>
<tr>
    <td></td>
    <td><input name="btnButton" id="btnButton" type="submit" value="  OK  " /></td>
</tr>
</table>
</form>

</fieldset> 


</body>
</html>