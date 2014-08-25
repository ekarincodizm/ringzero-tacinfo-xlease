<?php
include("../config/config.php");
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
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value=" กลับ " class="ui-button" onclick="window.location='deposit_select.php'"></div>
<div style="float:right"><input type="button" value=" Close " class="ui-button" onclick="window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>ใช้เงินรับฝาก</B></legend>

<div class="ui-widget">

<div style="text-align:center; color:green; font-size:14px; font-weight:bold; padding: 3px 3px 3px 3px">บันทึกเรียบร้อยแล้ว<br />กรุณาเลือกรายการที่ต้องการเพื่อพิมพ์ใบเสร็จ</div>
<?php
$data = pg_escape_string($_GET['data']);
$idno = pg_escape_string($_GET['idno']);
$arr_data = explode(",",$data);

foreach($arr_data AS $v){
    echo "- <a href=\"../ca/frm_recprint_" . $_SESSION['session_company_code'] . ".php?id=$v&idno=$idno\" target=\"_blank\"><u>$v</u></a><br />";
}

//echo "<iframe src=\"../ca/frm_recprint_" . $_SESSION['session_company_code'] . ".php?id=$v\" border=\"0\" frameborder=\"no\" framespacing=\"0\" scrolling=\"yes\" width=\"100%\" height=\"400\" allowtransparency=\"true\"></iframe>";

?>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>