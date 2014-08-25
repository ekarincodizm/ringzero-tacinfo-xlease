<?php
include("../config/config.php");
$fp_appID = $_GET["fp_appID"];
$sql = pg_query("SELECT reason,\"IDNO\" FROM \"Fp_cancel_approve\" where \"fp_appID\" = '$fp_appID'");
$result = pg_fetch_array($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="background-color:#DDDDDD;">
<div style="margin-top:15px" align="center"><h3>เหตุผลการขอยกเลิก</h3></div>
<div style="margin-top:15px;margin-left:26px" align="left" >สัญญา : <?php echo $result['IDNO']; ?></div>

<center>
<textarea readonly="ture" cols="50" rows="8"><?php echo $result['reason']; ?></textarea>
</center>
</body>
</html>