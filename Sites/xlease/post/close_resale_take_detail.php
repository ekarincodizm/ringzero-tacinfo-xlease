<?php
include("../config/config.php");

$oid = $_GET['oid'];
$nid = $_GET['nid'];
$stdate = $_GET['stdate'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        maxDate: '<?php echo $stdate; ?>',
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

<table width="470" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<fieldset><legend><B>ปิดสัญญารถยึด/ขายคืน</B></legend>

<div class="ui-widget" align="center">
<form name="aa" id="aa" method="post" action="close_resale_take_detail_send.php">
<input type="hidden" name="oid" id="oid" value="<?php echo $oid; ?>">
<input type="hidden" name="nid" id="nid" value="<?php echo $nid; ?>">
<table width="100%" border="0" cellSpacing="0" cellPadding="5">
<tr>
    <td width="35%">IDNO :</td>
    <td><?php echo "$oid => $nid"; ?></td>
</tr>
<tr>
    <td>วันที่ปิดบัญชี :</td>
    <td><input type="text" id="datepicker" name="datepicker" value="<?php echo $stdate; ?>" size="15" readonly></td>
</tr>
<tr>
    <td>ยอดเงินต้นคงเหลือยกไป :</td>
    <td><input type="text" name="money" id="money" size="15"> บาท.</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="btn1" id="btn1" value="บันทึก"></td>
</tr>
</table>
</form>
</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>