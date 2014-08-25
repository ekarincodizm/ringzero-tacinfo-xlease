<?php
include("../../config/config.php");
$CusID=$_GET["CusID"];
$hidden=$_GET["hidden"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
 <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<div style="margin-left:auto; margin-right:auto"><h1 align="center">ประวัติการแก้ไขข้อมูลลูกค้า</h1></div>
	<div style="margin-left:auto; margin-right:auto;"> <?php include("frm_history_EditDetail.php");?></div>
</body>
</html>