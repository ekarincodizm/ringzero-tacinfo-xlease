<?php
session_start();
$idno = $_GET['idno'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>

<fieldset><legend><B>ยกเลิก NT</B></legend>

<form name="frm_1" action="cancel_add_detail_send.php" method="post">
<input type="hidden" name="idno" value="<?php echo $idno; ?>">
<table width="100%">
<tr>
    <td width="20%"><b>IDNO</b></td>
    <td width="80%"><?php echo $idno; ?></td>
</tr>
<tr>
    <td><b>เหตุผล</b></td>
    <td><textarea name="remark" rows="5" cols="50"></textarea></td>
</tr>
<tr>
    <td></td>
    <td><input name="btnButton1" type="submit" value="ยืนยัน" /><input name="btnButton2" type="reset" value="ยกเลิก" onclick="javascript:window.close();" /></td>
</tr>
</table>
</form>

</fieldset> 


</body>
</html>