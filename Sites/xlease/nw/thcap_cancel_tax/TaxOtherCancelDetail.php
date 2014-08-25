<?php
session_start();
include("../../config/config.php");
$receiptID=$_GET['receiptID'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>รายละเอียดใบเสร็จที่ถูกยกเลิก</title>
</head>
<body>
<div style="text-align:center"><h2>รายละเอียดใบเสร็จที่ถูกยกเลิก</h2></div>
<div><b>ใบเสร็จที่ยกเิลิก : <font color="red"><?php echo $receiptID; ?></font></b></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
	<th width="400">รายการในใบเสร็จ</th>
	<th>จำนวนเงินที่จ่าย</th>
</tr>
<?php

$qrycheck=pg_query("select a.\"receiptID\",a.\"debtAmt\",a.\"tpDesc\",a.\"tpFullDesc\",a.\"typePayRefValue\",f.\"fullname\" as useradd,
g.\"fullname\" as requestuser,c.\"requestDate\",h.\"fullname\" as appuser,c.\"approveDate\" 
from thcap_v_receipt_otherpay_cancel a
left join (		select \"doerID\",\"receiptID\"			
				from \"thcap_v_receipt_details_cancel\"
				GROUP BY \"doerID\",\"receiptID\"
		  ) b on a.\"receiptID\"=b.\"receiptID\"
left join (
				select * 
				from \"thcap_temp_receipt_cancel\"
				where \"approveStatus\" = '1' 
		  ) c on a.\"receiptID\"=c.\"receiptID\"
left join thcap_temp_otherpay_debt d on a.\"debtID\"=d.\"debtID\"		  
left join account.\"thcap_typePay\" e on d.\"typePayID\"=e.\"tpID\"
left join \"Vfuser\" f on b.\"doerID\"=f.\"username\"
left join \"Vfuser\" g on c.\"requestUser\"=g.\"id_user\"
left join \"Vfuser\" h on c.\"approveUser\"=h.\"id_user\"
where c.\"contractID\" is not null and a.\"receiptID\"='$receiptID'  ");
$i=0;
$sumamt=0;
while($result=pg_fetch_array($qrycheck)){
	list($receiptID,$debtAmt,$tpDesc,$tpFullDesc,$typePayRefValue,$useradd,$requestuser,$requestDate,$appuser,$approveDate)=$result;
	if($i%2==0){
		$color="class=\"odd\"";
	}else{
		$color="class=\"even\"";
	}
	echo "
		<tr $color>
			<td>$tpDesc $tpFullDesc $typePayRefValue</td>
			<td align=right>".number_format($debtAmt,2)."</td>
		</tr>
	";
	$i++;
	$sumamt+=$debtAmt;
}
?>
<tr align="right" style="font-weight:bold;" valign="top" bgcolor="#FFC0C0"><td>รวมเงินในใบเสร็จ</td><td><?php echo number_format($sumamt,2);?></td></tr>
</table><br>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#E8E8E8" align="center">
<tr bgcolor="#EEEEEE">
    <td height="25" align="right" width="150"><b>ผู้ออกใบเสร็จ : </td><td bgcolor="#FFFFFF"><?php echo $useradd; ?></b></td>
</tr>
<tr bgcolor="#EEEEEE">
    <td height="25" align="right"><b>ผู้ขอยกเลิก : </td><td bgcolor="#FFFFFF"><?php echo $requestuser; ?></b></td>
</tr>
<tr bgcolor="#EEEEEE">
    <td height="25" align="right"><b>วันเวลาที่ขอยกเลิก : </td><td bgcolor="#FFFFFF"><?php echo $requestDate; ?></b></td>
</tr>
<tr bgcolor="#EEEEEE">
    <td height="25" align="right"><b>ผู้อนุมัติยกเลิก : </td><td bgcolor="#FFFFFF"><?php echo $appuser; ?></b></td>
</tr>
<tr bgcolor="#EEEEEE">
    <td height="25" align="right"><b>วันเวลาที่อนุมัติยกเลิก : </td><td bgcolor="#FFFFFF"><?php echo $approveDate; ?></b></td>
</tr>
</table><br>
<div style="text-align:center;"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</body>
</html>