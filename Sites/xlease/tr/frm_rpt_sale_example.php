<?php
session_start();
include("../config/config.php");
$mm = $_POST['mm'];
$yy = $_POST['yy'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div align="center">
<div class="header"><h1><?php echo $_SESSION["session_company_name"]; ?></h1></div>

<div class="wrapper">

<input name="button" type="button" onclick="window.location='frm_rpt_sale.php'" value="กลับหน้าพิมพ์รายงาน" />
<input name="button" type="button" onClick="window.open('frm_print_rpt_sale.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','mywindow','')" value="           Print           " />
<input name="button" type="button" onclick="window.location='../list_menu.php'" value="      กลับเมนูหลัก      " />
<br><br>
<table width="1024" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF">
      <td align="center">วันที่</td>
      <td align="center">เลขที่สัญญา</td>
      <td align="center">ชื่อผู้เช่าซื้อ</td>
      <td align="center">ยี่ห้อรถยนต์</td>
      <td align="center">ทะเบียน<br>รถยนต์</td>
      <td align="center">เงินดาวน์</td>
      <td align="center">ยอดจัด</td>
      <td align="center">ดอกผล<br>รอตัด</td>
      <td align="center">ยอดเช่าซื้อ<br>ไม่รวม VAT</td>
      <td align="center">ยอด VAT รวม</td>
      <td align="center">ยอดเช่าซื้อ<br>รวม VAT</td>
   </tr>

<?php
$j = 0;
$qry_in=pg_query("SELECT * FROM \"VRptSale\" where EXTRACT(MONTH FROM \"P_STDATE\")='$mm' AND EXTRACT(YEAR FROM \"P_STDATE\")='$yy' ORDER BY \"P_STDATE\" ");
while($res_in=pg_fetch_array($qry_in)){
    if(substr($res_in["IDNO"],6,2) != 22){
    $j+=1;
    $P_STDATE = $res_in["P_STDATE"];
    $IDNO = $res_in["IDNO"]; 
    $fullname = $res_in["fullname"];
    $asset_name = $res_in["asset_name"];
    $asset_regis = $res_in["asset_regis"];
    $P_DOWN = $res_in["P_DOWN"];
    $P_BEGINX = $res_in["P_BEGINX"];
    $intall = $res_in["intall"];
    $hpnonvat = $res_in["hpnonvat"];
    $vatall = $res_in["vatall"];
    $hpall = $res_in["hpall"];
    
    $a_P_DOWN = number_format($P_DOWN,2);
    $a_P_BEGINX = number_format($P_BEGINX,2);
    $a_intall = number_format($intall,2);
    $a_hpnonvat = number_format($hpnonvat,2);
    $a_vatall = number_format($vatall,2);
    $a_hpall = number_format($hpall,2);

    $strDate = date("d",strtotime($P_STDATE));
    
    $sum_down = $P_DOWN+$sum_down;
    $sum_begin = $P_BEGINX+$sum_begin;
    $sum_intall = $intall+$sum_intall;
    $sum_hpnonvat = $hpnonvat+$sum_hpnonvat;
    $sum_vatall = $vatall+$sum_vatall;
    $sum_hpall = $hpall+$sum_hpall;
    
    $b_sum_down = number_format($sum_down,2);
    $b_sum_begin = number_format($sum_begin,2);
    $b_sum_intall = number_format($sum_intall,2);
    $b_sum_hpnonvat = number_format($sum_hpnonvat,2);
    $b_sum_vatall = number_format($sum_vatall,2);
    $b_sum_hpall = number_format($sum_hpall,2);
    
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
      <td align="center"><?php echo $strDate; ?></td>
      <td align="center"><?php echo $IDNO; ?></td>
      <td align="left"><?php echo $fullname; ?></td>
      <td align="left"><?php echo $asset_name; ?></td>
      <td align="left"><?php echo $asset_regis; ?></td>
      <td align="right"><?php echo $a_P_DOWN; ?></td>
      <td align="right"><?php echo $a_P_BEGINX; ?></td>
      <td align="right"><?php echo $a_intall; ?></td>
      <td align="right"><?php echo $a_hpnonvat; ?></td>
      <td align="right"><?php echo $a_vatall; ?></td>
      <td align="right"><?php echo $a_hpall; ?></td>
   </tr>
<?php
} // IF
} // WHILE
?>

<tr bgcolor="#79BCFF" style="font-size:11px; font-weight:bold;">
      <td align="right" colspan="5">มีลูกค้าทั้งหมด (ราย) : <?php echo $j; ?></td>
      <td align="right"><?php echo $b_sum_down; ?></td>
      <td align="right"><?php echo $b_sum_begin; ?></td>
      <td align="right"><?php echo $b_sum_intall; ?></td>
      <td align="right"><?php echo $b_sum_hpnonvat; ?></td>
      <td align="right"><?php echo $b_sum_vatall; ?></td>
      <td align="right"><?php echo $b_sum_hpall; ?></td>
   </tr>
   
</table>

</div>
</div>

<div align="center">
<br> 

<input name="button" type="button" onclick="window.location='frm_rpt_sale.php'" value="กลับหน้าพิมพ์รายงาน" />
<input name="button" type="button" onClick="window.open('frm_print_rpt_sale.php?mm=<?php echo $mm; ?>&yy=<?php echo $yy; ?>','mywindow','')" value="           Print           " />
<input name="button" type="button" onclick="window.location='../list_menu.php'" value="      กลับเมนูหลัก      " />
</div>

</body>
</html>