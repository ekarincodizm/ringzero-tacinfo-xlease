<?php
include("../../config/config.php");
$debtID = $_GET["debtID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>หมายเหตุ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/js/number.js" type="text/javascript"></script>
</head>
<body>
<?php
// หาหมายเหตุในการตั้งหนี้
$qry = pg_query("select * from \"thcap_temp_otherpay_debt\" where \"debtID\" = '$debtID' ");
//$nub = pg_num_rows($qry);
while($res = pg_fetch_array($qry))
{
	$debtRemark = $res["debtRemark"]; // หมายเหตุในการตั้งหนี้
}
if($debtRemark == "") // ถ้าไม่ได้ระบุหมายเหตุในการตั้งหนี้
{
	$debtRemark = "ไม่ได้ระบุหมายเหตุ";
}
?>
<br>
<center>
<h2>หมายเหตุ</h2>
<textarea cols="40" rows="8" readonly><?php echo $debtRemark; ?></textarea>
<br><br>
<input type="button" value="Close" onclick="window.close();"/>
</center>
</body>
</html>