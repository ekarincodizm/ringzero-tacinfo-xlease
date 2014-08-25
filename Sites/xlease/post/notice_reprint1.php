<?php
session_start();
include("../config/config.php");
$idno = $_GET['idno'];
$ntid = $_GET['ntid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script>

</head>
<body>

<fieldset><legend><B>RePrint NT</B></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">NTID</td>
        <td align="center">วันที่ออก NT</td>
        <td align="center">คิดถึงวันที่</td>
        <td align="center">ออกโดย</td>
        <td align="center">สถานะ</td>
        <td align="center">ยกเลิกโดย</td>
        <td align="center">วันที่ยกเลิก</td>
    </tr>
<?php

$qry_name=pg_query("SELECT * FROM \"NTHead\" where \"IDNO\"='$idno'
					AND \"NTID\" not in(select \"NTID\" from \"nw_statusNT\" WHERE \"statusNT\" in('0','2'))
					ORDER BY \"NTID\" ASC ");
while($res_name=pg_fetch_array($qry_name)){
    $NTID = $res_name["NTID"];
    $do_date = $res_name["do_date"];
    $to_date = $res_name["to_date"];
    $cancel = $res_name["cancel"]; if($cancel=='f') $cancel = "ใช้งานปกติ"; else $cancel = "ยกเลิก";
    $makerid = $res_name["makerid"];
    $cancelid = $res_name["cancelid"];
    $cancel_date = $res_name["cancel_date"];
    
    $qry_fullname=pg_query("SELECT fullname FROM \"Vfuser\" where \"id_user\"='$makerid'");
    if($res_fullname=pg_fetch_array($qry_fullname)){
        $fullname = $res_fullname["fullname"];
    }
    
    $qry_fullname_c=pg_query("SELECT fullname FROM \"Vfuser\" where \"id_user\"='$cancelid'");
    if($res_fullname_c=pg_fetch_array($qry_fullname_c)){
        $fullname_c = $res_fullname_c["fullname"];
    }

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>   
        <td align="left"><a href="notice_reprint1.php?idno=<?php echo $idno; ?>&ntid=<?php echo $NTID; ?>"><u><?php echo $NTID; ?></u></a></td>
        <td align="center"><?php echo $do_date; ?></td>
        <td align="center"><?php echo $to_date; ?></td>
        <td align="left"><?php echo $fullname; ?></td>
        <td align="center"><?php echo $cancel; ?></td>
        <td align="left"><?php echo $fullname_c; ?></td>
        <td align="center"><?php echo $cancel_date; ?></td>
    </tr>
<?php
}
?>
</table>

</fieldset> 
<br/>
<?php
if(!empty($ntid)){

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
<fieldset>

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
</table>

<div align="center">
<form name="frm_1" action="notice_reprint_pdf.php" method="post" target="_blank">
<input type="hidden" name="idno" value="<?php echo $idno; ?>" />
<input type="hidden" name="ntid" value="<?php echo $ntid; ?>" />
<input type="submit" value="  Re Print  " />
</form>
</div>

</fieldset> 
<?php
}
?>

</body>
</html>