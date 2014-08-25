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
<input name="button" type="button" onclick="window.location='table_time_2.php?p=1&yy=<?php echo $yy; ?>'" value="แสดงเป็นรายบุคคล" <?php if($p==1) { echo "disabled"; }?> />
<input name="button" type="button" onclick="window.location='table_time_2_s.php?p=2&yy=<?php echo $yy; ?>'" value="แสดงเป็นสรุปประจำปีลูกหนี้" <?php if($p==2) { echo "disabled"; }?> />
</div>
<div style="clear:both"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center" rowspan="2">Policy<br>Number</td>
        <td align="center" rowspan="2">Name</td>
        <td align="center" rowspan="2">งวดค้าง</td>
        <td align="center" rowspan="2">Security</td>
        <td align="center" colspan="4">A/C Receivable After security deducted</td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">Over Due</td>
        <td align="center">Next Year</td>
        <td align="center">Over 1 Year</td>
        <td align="center">Total</td>
    </tr>

<?php
if( empty($yy) ){
    echo "<tr><td align=\"center\" colspan=20>- กรุณาเลือกปี -</td></tr>";
}else{
$j=0;
$t_custyear = 0;
$select_date = $yy."-12-31";
$qry_name=pg_query("SELECT * FROM account.\"effsoyaddcom\" where \"acclosedate\"='$select_date' ORDER BY custyear,\"idno\" ASC ");
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
    $monthly = $res_name["monthly"]; $monthly = round($monthly,2);
    $aroverdue = $res_name["aroverdue"]; $aroverdue = round($aroverdue,2);
    $arnextydue = $res_name["arnextydue"]; $arnextydue = round($arnextydue,2);
    $arotherydue = $res_name["arotherydue"]; $arotherydue = round($arotherydue,2);
    $artotal = $res_name["artotal"]; $artotal = round($artotal,2);
    $aroutstanding = $res_name["aroutstanding"]; $aroutstanding = round($aroutstanding,2);
    
    $uroverdue = $res_name["uroverdue"]; $uroverdue = round($uroverdue,2);
    $urnexty = $res_name["urnexty"]; $urnexty = round($urnexty,2);
    $urothery = $res_name["urothery"]; $urothery = round($urothery,2);
    $urtotal = $res_name["urtotal"]; $urtotal = round($urtotal,2);
    
    
    if($overdue<=6) $security = 0.2;
    elseif($overdue>6) $security = 1;
    
    $qry_fullname=pg_query("SELECT full_name FROM \"VContact\" where  \"IDNO\"='$idno'");
    if($res_fullname=pg_fetch_array($qry_fullname)){
        $full_name = $res_fullname['full_name'];
    }
    
    
if($t_custyear != $custyear AND $j != 1){
?>
<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td colspan="4"></td>
    <td align="right"><?php echo number_format($s_aroverdue,2); ?></td>
    <td align="right"><?php echo number_format($s_arnextydue,2); ?></td>
    <td align="right"><?php echo number_format($s_arotherydue,2); ?></td>
    <td align="right"><?php echo number_format($s_artotal,2); ?></td>
</tr>
<?php
    $s_aroverdue=0;
    $s_arnextydue=0;
    $s_arotherydue=0;
    $s_artotal=0;
    $s_aroutstanding=0;
}
    
    $s_aroverdue += ($aroverdue-$uroverdue)*$security;
    $s_arnextydue += ($arnextydue-$urnexty)*$security;
    $s_arotherydue += ($arotherydue-$urothery)*$security;
    $s_artotal += ($artotal-$urtotal)*$security;
    $s_aroutstanding += ($aroutstanding-$urtotal)*$security;

    $s_aroverdue_all += ($aroverdue-$uroverdue)*$security;
    $s_arnextydue_all += ($arnextydue-$urnexty)*$security;
    $s_arotherydue_all += ($arotherydue-$urothery)*$security;
    $s_artotal_all += ($artotal-$urtotal)*$security;
    $s_aroutstanding_all += ($aroutstanding-$urtotal)*$security;
    
    if($t_custyear != $custyear){
        echo "<tr bgcolor=\"#ffffff\" style=\"font-size:11px; font-weight:bold;\">
        <td></td>
        <td colspan=11>ลูกหนี้ปี $custyear</td>
        </tr>";
    }

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
    
?>
        <td align="center"><?php echo $idno; ?></td>
        <td align="left"><?php echo $full_name; ?></td>
        <td align="center"><?php echo $overdue; ?></td>
        <td align="center"><?php echo $security; ?></td>
        <td align="right"><?php echo number_format(($aroverdue-$uroverdue)*$security,2); ?></td>
        <td align="right"><?php echo number_format(($arnextydue-$urnexty)*$security,2); ?></td>
        <td align="right"><?php echo number_format(($arotherydue-$urothery)*$security,2); ?></td>
        <td align="right"><?php echo number_format(($artotal-$urtotal)*$security,2); ?></td>
    </tr>

</tr>
<?php
$t_custyear = $custyear;
}

if($rows>0){
?>
<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td colspan="4"></td>
    <td align="right"><?php echo number_format($s_aroverdue,2); ?></td>
    <td align="right"><?php echo number_format($s_arnextydue,2); ?></td>
    <td align="right"><?php echo number_format($s_arotherydue,2); ?></td>
    <td align="right"><?php echo number_format($s_artotal,2); ?></td>
</tr>
<?php
    $s_aroverdue=0;
    $s_arnextydue=0;
    $s_arotherydue=0;
    $s_artotal=0;
    $s_aroutstanding=0;
}else{
    echo "<tr><td colspan=12 align=center>- ไม่พบข้อมูล -</td></tr>";
}
?>

<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td align="left" colspan="2"><a href="table_time_2_print.php?yy=<?php echo $yy; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14"> พิมพ์รายงาน</a></td>
    <td  align="right" colspan="2">รวมทั้งสิ้น</td>
    <td align="right"><?php echo number_format($s_aroverdue_all,2); ?></td>
    <td align="right"><?php echo number_format($s_arnextydue_all,2); ?></td>
    <td align="right"><?php echo number_format($s_arotherydue_all,2); ?></td>
    <td align="right"><?php echo number_format($s_artotal_all,2); ?></td>
</tr>

<?php
}
?>

</table>

</fieldset>

</body>
</html>