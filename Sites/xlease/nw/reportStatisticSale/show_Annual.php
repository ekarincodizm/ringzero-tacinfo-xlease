<?php
session_start();
include("../../config/config.php");
$condition = $_POST["condition"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสินเชื่อประจำปี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper">
			<?php
				if($condition==1){
					include "show_Annual_1.php";
				}else if($condition==2){
					include "show_Annual_2.php";
				}else{
					include "show_Annual_3.php";
				}
			?>
			</div>
        </td>
    </tr>
</table>    
</body>
</html>