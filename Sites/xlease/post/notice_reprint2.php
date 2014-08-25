<?php
session_start();
include("../config/config.php");
$ntid = $_GET['ntid'];

$qry_name=pg_query("SELECT * FROM \"NTHead\" where \"NTID\"='$ntid' ");
if($res_name=pg_fetch_array($qry_name)){
    $idno = $res_name["IDNO"];
    $do_date = $res_name["do_date"];
    $to_date = $res_name["to_date"];
}

$qry_name=pg_query("SELECT * FROM \"Fp\" where \"IDNO\"='$idno' ");
if($res_name=pg_fetch_array($qry_name)){
    $P_LAWERFEEAmt = $res_name["P_LAWERFEEAmt"];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>

<fieldset><legend><B>RePrint NT</B></legend>

<table width="100%">
<tr>
    <td width="20%"><b>IDNO</b></td>
    <td width="30%"><?php echo $idno; ?></td>
    <td width="20%"><b>NTID</b></td>
    <td width="30%"><?php echo $ntid; ?></td>
</tr>
<tr>
    <td><b>วันที่ออก NT</b></td>
    <td><?php echo $do_date; ?></td>
    <td><b>คิดถึงวันที่</b></td>
    <td><?php echo $to_date; ?></td>
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
</table>

<div align="center">
<form name="frm_1" action="notice_reprint_pdf.php" method="post" target="_blank">
<input type="hidden" name="idno" value="<?php echo $idno; ?>" />
<input type="hidden" name="ntid" value="<?php echo $ntid; ?>" />
<input type="submit" value="  Re Print  " />
</form>
</div>

</fieldset> 


</body>
</html>