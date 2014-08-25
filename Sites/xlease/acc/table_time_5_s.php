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
<input name="button" type="button" onclick="window.location='table_time_5.php?p=1&yy=<?php echo $yy; ?>'" value="แสดงเป็นรายบุคคล" <?php if($p==1) { echo "disabled"; }?> />
<input name="button" type="button" onclick="window.location='table_time_5_s.php?p=2&yy=<?php echo $yy; ?>'" value="แสดงเป็นสรุปประจำปีลูกหนี้" <?php if($p==2) { echo "disabled"; }?> />
</div>
<div style="clear:both"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center" rowspan="2">Name</td>
        <td align="center" colspan="4">Net Account Receivable</td>
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
$qry_name=pg_query("SELECT * FROM account.\"effsoyaddcom\" where \"acclosedate\"='$select_date' ORDER BY overdue,custyear ASC ");
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
    $aroutstanding = $res_name["aroutstanding"]; $aroutstanding = round($aroutstanding,2);
    
    $uroverdue = $res_name["uroverdue"]; $uroverdue = round($uroverdue,2);
    $urnexty = $res_name["urnexty"]; $urnexty = round($urnexty,2);
    $urothery = $res_name["urothery"]; $urothery = round($urothery,2);
    $urtotal = $res_name["urtotal"]; $urtotal = round($urtotal,2);
    
    if($overdue<=6) $security = 0.2;
    elseif($overdue>6) $security = 1;
    
    if($overdue<=1) $reserve = 0.01;
    elseif($overdue<=3) $reserve = 0.02;
    elseif($overdue<=6) $reserve = 0.2;
    elseif($overdue<=12) $reserve = 0.5;
    elseif($overdue>12) $reserve = 1;
    
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
 
$t_custyear = $custyear;
$tt_custyear = $custyear;

if($t_overdue != $overdue AND $j != 1){
?>
<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td align="left">สรุป ลูกหนี้ที่ค้างจำนวนงวด <?php echo $t_overdue; ?></td>
    <td align="right"><?php echo number_format($st_aroverdue,2); ?></td>
    <td align="right"><?php echo number_format($st_arnextydue,2); ?></td>
    <td align="right"><?php echo number_format($st_arotherydue,2); ?></td>
    <td align="right"><?php echo number_format($st_artotal,2); ?></td>
</tr>
<?php
$st_aroverdue=0;
$st_arnextydue=0;
$st_arotherydue=0;
$st_artotal=0;
$st_aroutstanding=0;
$t_custyear = "";
}
       
    $s_aroverdue += ($aroverdue-$uroverdue)-((($aroverdue-$uroverdue)*$security)*$reserve);
    $s_arnextydue += ($arnextydue-$urnexty)-((($arnextydue-$urnexty)*$security)*$reserve);
    $s_arotherydue += ($arotherydue-$urothery)-((($arotherydue-$urothery)*$security)*$reserve);
    $s_artotal += ($artotal-$urtotal)-((($artotal-$urtotal)*$security)*$reserve);
    $s_aroutstanding += ($aroutstanding-$urtotal)-((($aroutstanding-$urtotal)*$security)*$reserve);

    $s_aroverdue_all += ($aroverdue-$uroverdue)-((($aroverdue-$uroverdue)*$security)*$reserve);
    $s_arnextydue_all += ($arnextydue-$urnexty)-((($arnextydue-$urnexty)*$security)*$reserve);
    $s_arotherydue_all += ($arotherydue-$urothery)-((($arotherydue-$urothery)*$security)*$reserve);
    $s_artotal_all += ($artotal-$urtotal)-((($artotal-$urtotal)*$security)*$reserve);
    $s_aroutstanding_all += ($aroutstanding-$urtotal)-((($aroutstanding-$urtotal)*$security)*$reserve);
    
    $st_aroverdue += ($aroverdue-$uroverdue)-((($aroverdue-$uroverdue)*$security)*$reserve);
    $st_arnextydue += ($arnextydue-$urnexty)-((($arnextydue-$urnexty)*$security)*$reserve);
    $st_arotherydue += ($arotherydue-$urothery)-((($arotherydue-$urothery)*$security)*$reserve);
    $st_artotal += ($artotal-$urtotal)-((($artotal-$urtotal)*$security)*$reserve);
    $st_aroutstanding += ($aroutstanding-$urtotal)-((($aroutstanding-$urtotal)*$security)*$reserve);
    
    $t_overdue = $overdue;
}

   
?>


<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td align="left">สรุป ลูกหนี้ที่ค้างจำนวนงวด <?php echo $t_overdue; ?></td>
    <td align="right"><?php echo number_format($st_aroverdue,2); ?></td>
    <td align="right"><?php echo number_format($st_arnextydue,2); ?></td>
    <td align="right"><?php echo number_format($st_arotherydue,2); ?></td>
    <td align="right"><?php echo number_format($st_artotal,2); ?></td>
</tr>

<tr bgcolor="#ffffff" style="font-size:11px; font-weight:bold;">
    <td align="right"><a href="table_time_5_s_print.php?yy=<?php echo $yy; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14"> พิมพ์รายงาน</a> | รวมทั้งสิ้น</td>
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