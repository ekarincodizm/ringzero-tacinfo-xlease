<?php
include("../config/config.php");
$yy = $_GET['yy'];
$show_yy = $yy+543;
$nowyear = date('Y');
$yearlater = 10;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<style type="text/css">
.divlist {
    background-color: #F0F0F0;
}
.divlist:hover{
    background-color: #D0D0D0;
}
</style>

</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input name="button" type="button" onclick="window.location='frm_discount.php'" value="กลับหน้าพิมพ์รายงาน" /></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>

<fieldset><legend><B>รายงานส่วนลดจ่าย</B></legend>

<div style="float:left; margin:3px; font-weight:bold"><?php echo "ประจำปี $show_yy"; ?></div>
<div style="float:right"><input name="button" type="button" onClick="window.open('frm_discount_ten_pdf.php?yy=<?php echo $yy; ?>','dh48425fhb5b2v8s5ssd8gv8t52tr','')" value="พิมพ์รายงาน PDF" /></div>
<div style="clear:both"></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#E0E0E0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
  <td align="center">เดือน</td>
<?php
for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
?>
    <td align="center">ลูกหนี้ปี <?php echo ($i+543); ?></td>
<?php
}
?>
    <td align="center">รวม/เดือน</td>
</tr>
<?php 
$qry_in=pg_query("SELECT * FROM \"Fp\" where \"P_SL\" <> '0' AND EXTRACT(YEAR FROM \"P_CLDATE\")='$yy' ORDER BY \"IDNO\" ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $IDNO = $res_in["IDNO"];
    $P_CLDATE = $res_in["P_CLDATE"];
    $P_CustByYear = $res_in["P_CustByYear"];
    $P_SL = $res_in["P_SL"];

    list($n_year,$n_month,$n_day) = split('-',$P_CLDATE);
    $n_month = number_format($n_month);
    $sum[$P_CustByYear][$n_month] += $P_SL;
}//end while

for($d=1; $d<=12; $d++){
    echo "<tr class=\"divlist\">";
    echo "<td align=\"center\">$d</td>";
    for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
        $money = number_format($sum[$i][$d],2);
        $sum_vertical[$i] += $sum[$i][$d];
        $sum_horizontal += $sum[$i][$d];
        echo "<td align=\"right\">$money</td>";
    }
    $sum_fm_horizontal = number_format($sum_horizontal,2);
    echo "<td align=\"right\"><b>$sum_fm_horizontal</b></td>";
    echo "</tr>";
    $sum_horizontal = 0;
}
?>

<tr bgcolor="#FFFFCE">
    <td align="center"><b>รวม/ปี</b></td>
<?php
    $sum_all = array_sum($sum_vertical);
    for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
        $sum_fm_vertical = number_format($sum_vertical[$i],2);
        echo "<td align=\"right\"><b>$sum_fm_vertical</b></td>";
    }
?>
    <td align="right"><u><b><?php echo number_format($sum_all,2); ?></b></u></td>
</tr>

</table>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>