<?php 
include("../config/config.php"); 

if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>


<fieldset><legend><B>รายงานสรุปรายเดือน</B></legend>

<form method="post" action="" name="f_list" id="f_list">
<div align="right">
<b>เดือน</b>
<select name="mm">
<?php
if(empty($mm)){
    $nowmonth = date("m");
}else{
    $nowmonth = $mm;
}
$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
for($i=0; $i<12; $i++){
    $a+=1;
    if($a > 0 AND $a <10) $a = "0".$a;
    if($nowmonth != $a){
        echo "<option value=\"$a\">$month[$i]</option>";
    }else{
        echo "<option value=\"$a\" selected>$month[$i]</option>";
    }
    
}
?>    
</select>
<b>ปี</b> 
<select name="yy">
<?php
if(empty($yy)){
    $nowyear = date("Y");
}else{
    $nowyear = $yy;
}
$year_a = $nowyear + 5; 
$year_b =  $nowyear - 5;

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
</div>
</form>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ID</td>
        <td align="center">IDNO</td>
        <td align="center">วันที่</td>
        <td align="center">บริษัท</td>
        <td align="center">รุ่น/ประเภท</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ใบกำกับ</td>
        <td align="center">ราคาทุน</td>
        <td align="center">Vat</td>
        <td align="center">ผลรวม</td>
    </tr>
<?php

$qry_com=pg_query("select coid,coname FROM gas.\"Company\" ORDER BY \"coid\" ASC ");
while($res_com=pg_fetch_array($qry_com)){
    $id = $res_com["coid"];
    $name = $res_com["coname"];

$sum_cost = 0;    
$sum_vat = 0;
$rows = 0;

$qry=pg_query("SELECT * FROM gas.\"PoGas\" where bill is not null AND status_pay = 't' AND idcompany = '$id' AND EXTRACT(MONTH FROM \"podate\")='$nowmonth' AND EXTRACT(YEAR FROM \"podate\")='$nowyear' ORDER BY \"idno\" ASC ");
$rows = pg_num_rows($qry);
if($rows > 0){
while($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"];
    $date = $res["podate"];
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $bill = $res["bill"]; if(empty($bill)) $bill = "-";
    $invoice = $res["invoice"]; if(empty($invoice)) $invoice = "-";
    
    $payid = $res["payid"];
    
    $qry_payid=pg_query("select \"Cancel\" FROM gas.\"PayToGas\" WHERE payid='$payid'");
    if($res_payid=pg_fetch_array($qry_payid)){
        $Cancel = $res_payid["Cancel"];
    }
    
    if($Cancel != 't'){
    
    $sum_cost += $costofgas;
    $sum_vat += $vatofcost;
    $sum_a += $costofgas+$vatofcost;
    
    $qry_com2=pg_query("select modelname FROM gas.\"Model\" WHERE modelid='$idmodel'");
    if($res_com2=pg_fetch_array($qry_com2)){
        $modelname = $res_com2["modelname"];
    }
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><a href="#" onclick="javascript:popU('frm_gs_detail_view.php?id=<?php echo "$id"; ?>','<?php echo "frm_gs_detail_view".$id;?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=200')"><u><?php echo "$id"; ?></u></a></td>
        <td align="center"><?php echo "$idno"; ?></td>
        <td align="center"><?php echo "$date"; ?></td>
        <td align="center"><?php echo "$idcompany"; ?></td>
        <td align="center"><?php echo "$modelname"; ?></td>
        <td align="center"><?php echo "$bill"; ?></td>
        <td align="center"><?php echo "$invoice"; ?></td>
        <td align="right"><?php echo number_format($costofgas,2); ?></td>
        <td align="right"><?php echo number_format($vatofcost,2); ?></td>
        <td align="right"><?php echo number_format($costofgas+$vatofcost,2); ?></td>
    </tr>
<?php
}
}
?>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="right" colspan=7>รวม</td>
        <td align="right"><?php echo number_format($sum_cost,2); ?></td>
        <td align="right"><?php echo number_format($sum_vat,2); ?></td>
        <td align="right"><?php echo number_format($sum_a,2); ?></td>
    </tr>

<?php
}
}
?>
    <tr>
        <td colspan="20" align="right"><img src="icoPrint.png" border="0" width="17" height="14"> <a href="frm_gs_month_print.php?mm=<?php echo $nowmonth; ?>&yy=<?php echo $nowyear; ?>" target="_blank">พิมพ์รายงาน</a> </td>
    </tr>
</table>
</fieldset>


        </td>
    </tr>
</table>

</body>
</html>