<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link> 
    </head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION["session_company_name"]; ?></h1></div>
<div class="wrapper">

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">รายงาน เช็คหมด</td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">จำนวนงวด</td>
        <td align="center">ชำระเช็ค</td>
        <td align="center">ผ่านแล้ว</td>
        <td align="center">เหลืออีก</td>
    </tr>

<?php

$n = 0;
$num = 0;
$summary = 0;   
$qry_fr=pg_query("select \"IDNO\",count(\"ChequeNo\") as count_cheq ,sum(case when \"IsPass\"='TRUE' THEN 1 ELSE 0 END) as sum_ispass from \"VDetailCheque\" GROUP BY \"IDNO\" ORDER BY \"IDNO\" ");
$num=pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){

    $IDNO = $res_fr["IDNO"];
    $count_cheq = $res_fr["count_cheq"];
    $sum_ispass = $res_fr["sum_ispass"];
    $summary = $count_cheq-$sum_ispass;
    
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $P_TOTAL = $res_vc["P_TOTAL"];
        $asset_type = $res_vc["asset_type"];
        $C_REGIS = $res_vc["C_REGIS"];
        $car_regis = $res_vc["car_regis"];
        if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
    }
    
    if($summary < 2){
        echo "<tr class=\"red\">"; 
    }else{
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
    }
?>
        <td align="center"><?php echo $IDNO; ?></td>
        <td align="left"><?php echo $full_name; ?></td>
        <td align="left"><?php echo $show_regis; ?></td>
        <td align="center"><?php echo $P_TOTAL; ?></td>
        <td align="center"><?php echo $count_cheq; ?></td>
        <td align="center"><?php echo $sum_ispass; ?></td>
        <td align="center"><?php echo $summary; ?></td>
    </tr>
<?php
}
?>

<?php 
if($num > 0){
?>
    <tr>
        <td colspan="10" align="right"><a href="report_cheq_pdf.php" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
    </tr>
<?php
}
?>
<?php 
if($num == 0){   
?>
    <tr><td colspan="10" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<div align="center"><br><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

        </td>
    </tr>
</table>

</body>
</html>