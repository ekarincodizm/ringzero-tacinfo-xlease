<?php 
set_time_limit(0);
include("../config/config.php"); 
$date_check = pg_escape_string($_POST['date_check']);

if(empty($date_check)){
    $show_cdate = date("Y-m-d");   
}else{
    $show_cdate = $date_check;
}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
</head>
<body>

<?php include("menu.php"); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>
 
<fieldset><legend><b>แสดงรายการ ค่าใช้จ่าย</b></legend>

<form method="post" action="" name="f_list" id="f_list">
<div align="left">
<b>เลือกวันที่</b>
<input name="date_check" id="date_check" type="text" readonly="true" size="11" value="<?php echo $show_cdate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.f_list.date_check,'yyyy-mm-dd',this)" value="ปฏิทิน"/><input type="submit" name="submit" value="ค้นหา">
</div>
</form>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">IDCarTax</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">ประเภทบริการ</td>
        <td align="center">เลขที่ใบเสร็จ</td>
        <td align="center">ยอดชำระ</td>
    </tr>
   
<?php
if( isset($date_check) ){

$qry_name=pg_query("select A.\"IDCarTax\",A.\"IDNO\",\"CusAmt\",\"TypeDep\",\"ApointmentDate\",\"TaxDueDate\",\"TaxValue\",\"BillNumber\",\"TypePay\"  
from carregis.\"CarTaxDue\" A LEFT OUTER JOIN carregis.\"DetailCarTax\" B on A.\"IDCarTax\" = B.\"IDCarTax\" 
    where (\"CoPayDate\" = '$date_check') ORDER BY \"IDNO\" ASC ");

//$qry_name=pg_query("select * from carregis.\"CarTaxDue\" where (\"TaxDueDate\" = '$date_check') AND \"BookIn\"='true' ORDER BY \"IDNO\" ASC ");  
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDCarTax = $res_name["IDCarTax"];
    $IDNO = $res_name["IDNO"];
    $CusAmt = $res_name["CusAmt"];
    $TypeDep = $res_name["TypeDep"];
    $ApointmentDate = $res_name["ApointmentDate"]; if(empty($ApointmentDate)) $ApointmentDate = "-";
    $TaxDueDate = $res_name["TaxDueDate"];
        $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
        
    $TaxValue = $res_name["TaxValue"];
    $BillNumber = $res_name["BillNumber"];
    $TypePay = $res_name["TypePay"];
    
	$qry_name2=pg_query("select a.\"CarID\" as asset_id,a.\"C_REGIS\",b.\"asset_type\",c.\"full_name\" from \"Carregis_temp\" a
	left join \"Fp\" b on a.\"IDNO\"=b.\"IDNO\"
	left join \"Fa1_FAST\" c on b.\"CusID\"=c.\"CusID\"
	WHERE a.\"IDNO\"='$IDNO' order by \"auto_id\" DESC limit 1 ");
	$num_cartemp=pg_num_rows($qry_name2);
	if($num_cartemp==0){
		//กรณีเป็น Gas 
		$qry_name2=pg_query("SELECT a.\"asset_id\",b.\"car_regis\",a.\"asset_type\",c.\"full_name\" FROM \"Fp\" a
		LEFT JOIN \"FGas\" b ON a.asset_id = b.\"GasID\"
		LEFT JOIN \"Fa1_FAST\" c ON a.\"CusID\" = c.\"CusID\"
		WHERE \"IDNO\"='$IDNO' ");
	}	
    //$qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_name2=pg_fetch_array($qry_name2)){
        $asset_id = $res_name2["asset_id"];
        $full_name = $res_name2["full_name"];
        $asset_type = $res_name2["asset_type"];
        $C_REGIS = $res_name2["C_REGIS"];
        $car_regis = $res_name2["car_regis"];
        if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
    }else{
        $full_name = "ไม่พบข้อมูล";
        $show_regis = "ไม่พบข้อมูล";
    }
  
    $qry_name4=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay' ");
    if($res_name4=pg_fetch_array($qry_name4)){
        $TName = $res_name4["TName"];
    }
    
    $summary = $TaxValue;
    $sum_TaxValue+=$TaxValue;
    $sum_CusAmt+=$CusAmt;
    $sum_summary+=$summary;
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\" valign=\"top\">";
    }else{
        echo "<tr class=\"even\" valign=\"top\">";
    }
 ?>

    <td align="center"><?php echo $IDNO; ?></td>
    <td align="center"><?php echo $IDCarTax; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $show_regis; ?></td>
    <td align="left"><?php echo "$TName"; ?></td>
    <td align="left"><?php echo "$BillNumber"; ?></td>
    <td align="right"><?php echo number_format($TaxValue,2); ?></td>
</tr>

<?php            
}
}

if($rows > 0){

 ?>
    <tr bgcolor="#79BCFF" style="font-size:11px; font-weight:bold;">
        <td align="right" colspan="5">รวมยอดเงิน</td>
        <td></td>
        <td align="right"><?php echo number_format($sum_TaxValue,2); ?></td>
    </tr>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td align="right" colspan="14">
        <a href="frm_all_show_detail_print.php?d=<?php echo "$show_cdate"; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์รายงายเรียงตาม IDCarTax</b></a> | 
        <a href="frm_all_show_detail_type_print.php?d=<?php echo "$show_cdate"; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> <b>สั่งพิมพ์รายงายเรียงตาม ประเภทบริการ</b></a></td>
    </tr>                                                                      
<?php } ?>
</table>

</fieldset>

        </td>
	</tr>
</table>

</body>
</html>