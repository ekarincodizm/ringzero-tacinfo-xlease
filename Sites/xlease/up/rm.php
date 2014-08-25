<?php
$name = $_GET["name"];
$type = $_GET["type"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    </head>
<body>

<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div class="header"><h1>AV.LEASING</h1></div>
<div class="wrapper">
<div align="center">
<fieldset>

<?php

if( unlink($name) ){
    echo "ลบไฟล์เรียบร้อยแล้ว<br><br><input type=\"button\" value=\"  Back  \" onclick=\"location.href='frm_show_list.php?type=$type'\">";
}else{
    echo "ไม่สามารถลบไฟล์ได้<br><br><input type=\"button\" value=\"  Back  \" onclick=\"location.href='frm_show_list.php?type=$type'\">";
}

?>

</fieldset>

</div>
</div>
        </td>
    </tr>
</table>

</body>
</html>