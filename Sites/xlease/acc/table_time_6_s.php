<?php 
session_start();
include("../config/config.php"); 
$yy = pg_escape_string($_REQUEST['yy']);
$p = pg_escape_string($_GET['p']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
</head>
<body>
 
<fieldset><legend><b>ตารางลูกหนี้แยกตามระยะเวลาครบกำหนดชำระ ณ สิ้นปี <?php echo $yy; ?></b></legend>

<div style="float:left">
<form method="post" action="?p=1" name="f_list" id="f_list">
<b>ณ สิ้นปี</b>
<select name="yy">
<?php
if(empty($yy)){
    $nowyear = date("Y");
}else{
    $nowyear = $yy;
}
$year_a = $nowyear + 3; 
$year_b =  $nowyear - 3;

$s_b = $year_b+543;

while($year_b <= $year_a){
    if($nowyear != $year_b){
        echo "<option value=\"$year_b\">$s_b</option>";
    }else{
        echo "<option value=\"$year_b\" selected>$s_b</option>";
    }
    $year_b += 1;
    $s_b +=1;
}

?>
</select><input type="submit" name="submit" value="ค้นหา">
</form>
</div>
<div style="float:right">
<input name="button" type="button" onclick="window.location='table_time_6.php?p=1&yy=<?php echo $yy; ?>'" value="แสดงเป็นรายบุคคล" <?php if($p==1) { echo "disabled"; }?> />
<input name="button" type="button" onclick="window.location='table_time_6_s.php?p=2&yy=<?php echo $yy; ?>'" value="แสดงเป็นสรุปประจำปีลูกหนี้" <?php if($p==2) { echo "disabled"; }?> />
</div>
<div style="clear:both"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center" rowspan="2">Name</td>
        <td align="center" colspan="6">Realized</td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">Previous Year</td>
        <td align="center">ต้องชำระ</td>
        <td align="center">รับรู้ไม่เกิน3</td>
        <td align="center">Next Year</td>
        <td align="center">ดอกผลทั้งหมด</td>
        <td align="center">รับรู้ปีนี้</td>
    </tr>
   
<?php
if( empty($yy) ){
    echo "<tr><td align=\"center\" colspan=20>- กรุณาเลือกปี -</td></tr>";
}else{

$j=0;
$t_custyear = 0;
$select_date = $yy."-12-31";
$qry_name=pg_query("SELECT * FROM account.\"effsoyaddcom\" where  \"acclosedate\"='$select_date' ORDER BY overdue,custyear ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $j+=1;
    $idno = $res_name["idno"];
    $cusid = $res_name["cusid"];
    $custyear = $res_name["custyear"];
    $paid = $res_name["paid"];
    $overdue = $res_name["overdue"];
    $nextydue = $res_name["nextydue"];
    $otherydue = $res_name["otherydue"];
    $totaldue = $res_name["totaldue"];
    $monthly = $res_name["monthly"];
    $aroverdue = $res_name["aroverdue"]; $aroverdue = round($aroverdue,2);
    $arnextydue = $res_name["arnextydue"]; $arnextydue = round($arnextydue,2);
    $arotherydue = $res_name["arotherydue"]; $arotherydue = round($arotherydue,2);
    $artotal = $res_name["artotal"]; $artotal = round($artotal,2);
    
    $rlpreviousy = $res_name["rlpreviousy"]; $rlpreviousy = round($rlpreviousy,2);
    $rltothisy = $res_name["rltothisy"]; $rltothisy = round($rltothisy,2);
    $rlpayreal = $res_name["rlpayreal"]; $rlpayreal = round($rlpayreal,2);
    $rlnexty = $res_name["rlnexty"]; $rlnexty = round($rlnexty,2);
    $rlall = $res_name["rlall"]; $rlall = round($rlall,2);
    $rlthisy = $res_name["rlthisy"]; $rlthisy = round($rlthisy,2);
    
    $qry_fullname=pg_query("SELECT full_name FROM \"VContact\" where \"IDNO\"='$idno'");
    if($res_fullname=pg_fetch_array($qry_fullname)){
        $full_name = $res_fullname['full_name'];
    }
    


if($t_custyear != $custyear AND $j != 1){
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }    

?>
    <td style="padding-left:30px;">ลูกค้าประจำปี <?php echo $tt_custyear; ?></td>
    <td align="right"><?php echo number_format($s_rlpreviousy,2); ?></td>
    <td align="right"><?php echo number_format($s_rltothisy,2); ?></td>
    <td align="right"><?php echo number_format($s_rlpayreal,2); ?></td>
    <td align="right"><?php echo number_format($s_rlnexty,2); ?></td>
    <td align="right"><?php echo number_format($s_rlall,2); ?></td>
    <td align="right"><?php echo number_format($s_rlthisy,2); ?></td>
</tr>
<?php



    $s_rlpreviousy=0;
    $s_rltothisy=0;
    $s_rlpayreal=0;
    $s_rlnexty=0;
    $s_rlall=0;
    $s_rlthisy=0;
}

$t_custyear = $custyear;
$tt_custyear = $custyear;

    if($t_overdue != $overdue AND $j != 1){
    ?>
    <tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
        <td align="left">สรุป ลูกหนี้ที่ค้างจำนวนงวด <?php echo $t_overdue; ?></td>
        <td align="right"><?php echo number_format($st_rlpreviousy,2); ?></td>
        <td align="right"><?php echo number_format($st_rltothisy,2); ?></td>
        <td align="right"><?php echo number_format($st_rlpayreal,2); ?></td>
        <td align="right"><?php echo number_format($st_rlnexty,2); ?></td>
        <td align="right"><?php echo number_format($st_rlall,2); ?></td>
        <td align="right"><?php echo number_format($st_rlthisy,2); ?></td>
    </tr>
    <?php
    $st_rlpreviousy=0;
    $st_rltothisy=0;
    $st_rlpayreal=0;
    $st_rlnexty=0;
    $st_rlall=0;
    $st_rlthisy=0;
    $t_custyear="";
    }
    
    $s_rlpreviousy += $rlpreviousy;
    $s_rltothisy += $rltothisy;
    $s_rlpayreal += $rlpayreal;
    $s_rlnexty += $rlnexty;
    $s_rlall += $rlall;
    $s_rlthisy += $rlthisy;

    $st_rlpreviousy += $rlpreviousy;
    $st_rltothisy += $rltothisy;
    $st_rlpayreal += $rlpayreal;
    $st_rlnexty += $rlnexty;
    $st_rlall += $rlall;
    $st_rlthisy += $rlthisy;
    
    $s_rlpreviousy_all += $rlpreviousy;
    $s_rltothisy_all += $rltothisy;
    $s_rlpayreal_all += $rlpayreal;
    $s_rlnexty_all += $rlnexty;
    $s_rlall_all += $rlall;
    $s_rlthisy_all += $rlthisy;

    /*
    $s_aroverdue += $aroverdue;
    $s_arnextydue += $arnextydue;
    $s_arotherydue += $arotherydue;
    $s_artotal += $artotal;
    
    $st_aroverdue += $aroverdue;
    $st_arnextydue += $arnextydue;
    $st_arotherydue += $arotherydue;
    $st_artotal += $artotal;
    
    $s_aroverdue_all += $aroverdue;
    $s_arnextydue_all += $arnextydue;
    $s_arotherydue_all += $arotherydue;
    $s_artotal_all += $artotal;
    */
    
    $t_overdue = $overdue;
}
  
?>

<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td align="left">สรุป ลูกหนี้ที่ค้างจำนวนงวด <?php echo $t_overdue; ?></td>
    <td align="right"><?php echo number_format($st_rlpreviousy,2); ?></td>
    <td align="right"><?php echo number_format($st_rltothisy,2); ?></td>
    <td align="right"><?php echo number_format($st_rlpayreal,2); ?></td>
    <td align="right"><?php echo number_format($st_rlnexty,2); ?></td>
    <td align="right"><?php echo number_format($st_rlall,2); ?></td>
    <td align="right"><?php echo number_format($st_rlthisy,2); ?></td>
</tr>

<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td align="right"><a href="table_time_6_s_print.php?yy=<?php echo $yy; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14"> พิมพ์รายงาน</a> | รวมทั้งสิ้น</td>
    <td align="right"><?php echo number_format($s_rlpreviousy_all,2); ?></td>
    <td align="right"><?php echo number_format($s_rltothisy_all,2); ?></td>
    <td align="right"><?php echo number_format($s_rlpayreal_all,2); ?></td>
    <td align="right"><?php echo number_format($s_rlnexty_all,2); ?></td>
    <td align="right"><?php echo number_format($s_rlall_all,2); ?></td>
    <td align="right"><?php echo number_format($s_rlthisy_all,2); ?></td>
</tr>

<?php
}
?>

</table>

</fieldset>

</body>
</html>