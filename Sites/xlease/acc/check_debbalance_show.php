<?php
set_time_limit(0);
include("../config/config.php");

$date = pg_escape_string($_GET['date']);
$yy = pg_escape_string($_GET['yy']);

$yy_plus = $yy+543;
$st_date_del_1year =date("Y-m-d", strtotime("-1 year",strtotime($date)));
$st_date_positive_1day =date("Y-m-d", strtotime("+1 day",strtotime($st_date_del_1year)));
?>

<div style="float:left">
    <?php echo "<b>แสดงข้อมูลของ</b> วันที่ $st_date_del_1year <b>ลูกหนี้ปี</b> $yy_plus"; ?>
</div>
<div style="float:right">
    <div style="font-size:10px; background-color:#FFFFCA; padding: 3px; width:80px; text-align:center; float:left">ปิดบัญชี</div>
    <div style="font-size:10px; background-color:#FFC0C0; padding: 3px; width:80px; text-align:center; float:left">ผิดผลาด</div>
</div>
<div style="clear:both"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold; text-align:center" valign="top" bgcolor="#79BCFF">
      <td>IDNO</td>
      <td>ชื่อ</td>
      <td>ยอดปีเก่า</td>
      <td>ยอดในปีนี้</td>
      <td>ยอดยกไป</td>
   </tr>
<?php
$qry=pg_query("SELECT * FROM account.\"debtbalance\" WHERE \"acclosedate\" = '$st_date_del_1year' AND \"custyear\"='$yy' ORDER BY \"idno\",\"acclosedate\" ");
$qry_num = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    $acclosedate = $res["acclosedate"];
    $idno = $res["idno"];
    $cusid = $res["cusid"];
    $custyear = $res["custyear"];
    $monthly = $res["monthly"];
    $totaldue = $res["totaldue"];
    $notpaid = $res["notpaid"];
    $vatpayready = $res["vatpayready"];

    $full_name = "";
    $sql_fname = pg_query("SELECT \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" FROM \"Fa1\" WHERE \"CusID\"='$cusid' ");
    if($rs_fname = pg_fetch_array($sql_fname)){
        $full_name = $rs_fname['A_FIRNAME']." ".$rs_fname['A_NAME']." ".$rs_fname['A_SIRNAME'];
    }

    $fr = pg_query("SELECT COUNT(\"R_DueNo\") as countfr FROM \"Fr\" WHERE \"IDNO\"='$idno' AND \"Cancel\" = 'FALSE' AND (\"R_Date\" BETWEEN '$st_date_positive_1day' AND '$date') AND \"CustYear\"='$yy' ");
    if($rs_fr = pg_fetch_array($fr)){
        $countfr = $rs_fr['countfr'];
    }
    
    $x = $notpaid-$countfr;
    
    if($x == 0){
        //ปิด บัญชี
        echo "<tr bgcolor=\"#FFFFCA\">";
    }elseif($x > 0){
        //ปกติ
        echo "<tr bgcolor=\"#ffffff\">";
    }elseif($x < 0){
        //ผิดปกติ
        echo "<tr bgcolor=\"#FFC0C0\">";
    }
    
    $m1 = $monthly*$notpaid;
    $m2 = $monthly*$countfr;
    $m3 = $m1-$m2;
    
    $s1 += $m1;
    $s2 += $m2;
    $s3 += $m3;
?>
      <td><?php echo "<span title=\"Notpaid $notpaid | Count $countfr | AccDate $acclosedate\">$idno</span>"; ?></td>
      <td><?php echo "$full_name"; ?></td>
      <td align="right"><?php echo number_format($m1,2); ?></td>
      <td align="right"><?php echo number_format($m2,2); ?></td>
      <td align="right"><?php echo number_format($m3,2); ?></td>
   </tr>
<?php
}

if($qry_num == 0){
    echo "<tr><td colspan=5 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    echo "<tr style=\"font-weight:bold\">
    <td align=left><a href=\"check_debbalance_pdf.php?date=$date&&yy=$yy\" target=\"_blank\"><u>พิมพ์รายงาน PDF</u></a></td>
    <td align=right>ทั้งหมด $qry_num รายการ</td>
    <td align=right>".number_format($s1,2)."</td>
    <td align=right>".number_format($s2,2)."</td>
    <td align=right>".number_format($s3,2)."</td>
    </tr>";
}
?>
</table>