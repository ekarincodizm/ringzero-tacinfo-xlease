<?php 
include("../config/config.php");
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>     
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="header"><h1>รายงานค้างค่าประกัน</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>ประกันภัยภาคบังคับ (พรบ.)</b></legend>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">บริษัทประกัน</td>
        <td align="center">IDNO</td>
        <td align="center">วันที่คุ้มครอง</td>
        <td align="center">เลขที่ภายใน</td>
        <td align="center">เลขที่ของกรมธรรม์</td>
        <td align="center">ชื่อ/สกุล</td>
        <td align="center">เลขทะเบียน</td>
        <td align="center">สีรถ</td>
        <td align="center">เบี้ยประกันทั้งหมด</td>
        <td align="center">Outstanding</td>
    </tr>
   
<?php
$old_company = "";
$qry_inf=pg_query("select * from insure.\"VInsForceDetail\" WHERE \"outstanding\" >= '0.01' ORDER BY \"Company\",\"StartDate\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $nub+=1;
    $nub_all+=1;
    $Company = $res_inf["Company"];
    $IDNO = $res_inf["IDNO"];
    $StartDate = $res_inf["StartDate"];
    $InsFIDNO = $res_inf["InsFIDNO"];
    $InsID = $res_inf["InsID"];
    $full_name = $res_inf["full_name"];
    $C_REGIS = $res_inf["C_REGIS"];
    $C_COLOR = $res_inf["C_COLOR"];
    $Premium = $res_inf["Premium"]; $Premium = round($Premium,2);
    $outstanding = $res_inf["outstanding"]; $outstanding = round($outstanding,2);
    
    $sum_premium += $Premium;
    $sum_premium_all += $Premium;
    $sum_outstanding += $outstanding;
    $sum_outstanding_all += $outstanding;
    
    if(($Company != $old_company) && $nub!=1){
        echo "<tr bgcolor=#FFC0C0> <td align=left><b>ทั้งหมด $nub รายการ</b></td> <td colspan=7 align=right><b>ผลรวม</b></td> <td align=right><b>".number_format($sum_premium,2)."</b></td> <td align=right><b>".number_format($sum_outstanding,2)."</b></td></tr>";
        $sum_premium = 0;
        $sum_outstanding = 0;
        $nub = 0;
        $old_company = $Company;
    }else{
        $old_company = $Company;
  
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo "$Company"; ?></td>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="center"><?php echo "$StartDate"; ?></td>
        <td align="center"><?php echo "$InsFIDNO"; ?></td>
        <td align="center"><?php echo "$InsID"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$C_REGIS"; ?></td>
        <td align="left"><?php echo "$C_COLOR"; ?></td>
        <td align="right"><?php echo number_format($Premium,2); ?></td>
        <td align="right"><?php echo number_format($outstanding,2); ?></td>
    </tr>

<?php
    }
}

echo "<tr bgcolor=#FFC0C0> <td align=left><b>ทั้งหมด $nub รายการ</b></td> <td colspan=7 align=right><b>ผลรวม</b></td> <td align=right><b>".number_format($sum_premium,2)."</b></td> <td align=right><b>".number_format($sum_outstanding,2)."</b></td></tr>";
    
echo "<tr bgcolor=#79BCFF> <td align=left><b>รวมทั้งหมด $nub_all รายการ</b></td>  <td align=center><b><a href=\"frm_f_report_pdf.php\" target=_blank><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\"> พิมพ์</a></b></td> <td colspan=6 align=right><b>ผลรวมทั้งสิ้น</b></td> <td align=right><b>".number_format($sum_premium_all,2)."</b></td> <td align=right><b>".number_format($sum_outstanding_all,2)."</b></td></tr>";
?>

</table>

</div>
		</td>
	</tr>
</table>

</body>
</html>