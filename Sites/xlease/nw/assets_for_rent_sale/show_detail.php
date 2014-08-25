<?php
include("../../config/config.php");
$assetDetailID = $_GET["assetDetailID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>คำอธิบาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<?php
// หาคำอธิบาย
$qry = pg_query("select \"explanation\" from \"vthcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
list($explanation) = pg_fetch_array($qry);
if($explanation == "") // ถ้าไม่ได้ระบุหมายเหตุในการตั้งหนี้
{
	$explanation = "ไม่ได้ระบุหมายเหตุ";
}

?>
<br>
<center>
<h2>คำอธิบาย</h2>
<textarea cols="80" rows="8" readonly><?php echo $explanation; ?></textarea>
<br><br>
<input type="button" value="Close" onclick="window.close();"/>
</center>
</body>
</html>