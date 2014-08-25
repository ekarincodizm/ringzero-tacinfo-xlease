<?php 
session_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) FA เพิ่มบิลขอสินเชื่อ-รายละเอียด</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language="javascript">
	function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<?php
echo "<div align=\"center\"><h2>รายละเอียด (THCAP) FA เพิ่มบิลขอสินเชื่อ</h2></div>";
echo "<div>";
include "frm_Detail.php";
echo "</div>";

//ดึงรูปบิลมาแสดง
$request=1;
echo "<div>";
include "frm_Picbill.php";
echo "</div>";

echo "<div align=\"center\"><input name=\"close\" type=\"button\" onclick=\"window.close();\" value=\"   ปิด    \"></div>";
?>
</body>
</html>