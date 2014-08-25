<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$ntid=$_GET['ntid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>รายละเอียด NT</title>
</head>
<body>
<?php
$qry_name=pg_query("SELECT * FROM \"NTHead\" where \"NTID\"='$ntid' ");
if($res_name=pg_fetch_array($qry_name)){
    $idno = $res_name["IDNO"];
    $do_date = $res_name["do_date"];
    $to_date = $res_name["to_date"];
    $remine_date = $res_name["remine_date"];
}

$qry_name=pg_query("SELECT * FROM \"Fp\" where \"IDNO\"='$idno' ");
if($res_name=pg_fetch_array($qry_name)){
    $P_LAWERFEEAmt = $res_name["P_LAWERFEEAmt"];
}
?>
<table width="100%">
<tr>
    <td width="20%"><b>IDNO</b></td>
    <td width="30%"><?php echo $idno; ?></td>
    <td width="20%"><b>NTID</b></td>
    <td width="30%" style="color:#ff0000; font-weight:bold;"><?php echo $ntid; ?></td>
</tr>
<tr>
    <td><b>วันที่ออก NT</b></td>
    <td><?php echo $do_date; ?></td>
    <td><b>คิดถึงวันที่</b></td>
    <td><?php echo $to_date; ?></td>
</tr>
<tr>
    <td><b>งวดที่เริ่มค้าง</b></td>
    <td><?php echo $remine_date; ?></td>
</tr>
</table>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center" width="75%">รายละเอียด</td>
        <td align="center" width="25%">ยอดเงิน</td>
    </tr>
<?php
$qry_name=pg_query("SELECT * FROM \"NTDetail\" where \"NTID\"='$ntid' AND \"MainDetail\"='true' ORDER BY autoid ASC");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $Detail = $res_name["Detail"];
    $Amount = $res_name["Amount"]; $Amount = round($Amount,2);
    $sum_amts += $Amount;

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>   
        <td align="left"><?php echo $Detail; ?></td>
        <td align="right"><?php echo number_format($Amount,2); ?></td>
    </tr>
<?php
}

$qry_name=pg_query("SELECT * FROM \"NTDetail\" where \"NTID\"='$ntid' AND \"MainDetail\"='false' ORDER BY autoid ASC");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $Detail = $res_name["Detail"];
    $Amount = $res_name["Amount"]; $Amount = round($Amount,2);
    $sum_amts += $Amount;

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>   
        <td align="left"><?php echo $Detail; ?></td>
        <td align="right"><?php echo number_format($Amount,2); ?></td>
    </tr>
<?php
}
?>
    <tr>
        <td align="right"><b>รวม</b></td>
        <td align="right"><?php echo number_format($sum_amts,2); ?></td>
    </tr>
	<tr><td align="center" bgcolor="#FFFFFF" height="50" colspan="2"><input type="button" onclick="window.close();" value="x ปิดหน้านี้"></td></tr>
</table>

</body>
</html>