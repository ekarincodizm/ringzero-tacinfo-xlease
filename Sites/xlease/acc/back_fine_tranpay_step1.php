<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$bank = pg_escape_string($_GET['bank']);
$date = pg_escape_string($_GET['date']);
$amt = pg_escape_string($_GET['amt']);
$trantype = pg_escape_string($_GET['trantype']);
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
    $("#tb_idno").autocomplete({
        source: "s_idno.php",
        minLength:1
    });
});
</script>
    
</head>
<body>

<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<fieldset>
<form name="frm1" id="frm1" action="back_fine_tranpay_step1_send.php" method="post">
<input type="hidden" name="id" id="id" value="<?php echo "$id"; ?>">
<input type="hidden" name="bank" id="bank" value="<?php echo "$bank"; ?>">
<input type="hidden" name="idno" id="idno" value="">
<input type="hidden" name="date" id="date" value="<?php echo "$date"; ?>">
<input type="hidden" name="amt" id="amt" value="<?php echo "$amt"; ?>">
<input type="hidden" name="trantype" id="trantype" value="<?php echo "$trantype"; ?>">
<div class="ui-widget" align="center">
    <div style="padding: 5px 0 5px 0">
    <b>เลือกเลขที่สัญญา</b> : <input name="tb_idno" id="tb_idno" type="text" size="70"><br /><br />
    <input type="submit" name="btn1" id="btn1" value="บันทึก">
    </div>
</div>
</form>
 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>