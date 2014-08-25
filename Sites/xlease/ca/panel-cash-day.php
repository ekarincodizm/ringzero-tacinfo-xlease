<?php
include("../config/config.php");
$datepicker = pg_escape_string($_GET['datepicker']);

if(!empty($datepicker)){
?>

<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>

<div align="right"><a href="cash_day_pdf.php?date=<?php echo "$datepicker"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงานทั้งหมด)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>เลขที่ใบเสร็จ</td>
    <td>IDNO</td>
    <td>ชื่อลูกค้า</td>
    <td>ทะเบียน</td>
    <td>TypePay</td>
    <td>TName</td>
	<td>เวลารับชำระ <br>(ชั่วโมง:นาที)</td>
    <td>จำนวนเงิน</td>
</tr>

<?php
$old_UserIDAccept = 0;
$query=pg_query("select * from \"VUserReceiptCash\" WHERE \"PostDate\"='$datepicker' ORDER BY \"UserIDAccept\",\"refreceipt\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $UserIDAccept = "";
    $UserIDAccept = $resvc['UserIDAccept'];
    $refreceipt = $resvc['refreceipt'];
    $IDNO = $resvc['IDNO'];
    $A_NAME = trim($resvc['A_NAME']);
    $A_SIRNAME = trim($resvc['A_SIRNAME']);
    $TypePay = $resvc['TypePay'];
    $TName = $resvc['TName'];
    $AmtPay = $resvc['AmtPay'];
	$PostTime = $resvc['PostTime'];
	if($PostTime ==""){
		$PostTime="-";
	}else{
		$PostTime=substr($PostTime,0,5);
	}
    
    $query_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO'");
	$num_Vcontact=pg_num_rows($query_VContact);
	if($num_Vcontact==0){
		$qry_radio=pg_query("select * from \"RadioContract\" where \"COID\"='$IDNO'");
		$res_radio=pg_fetch_array($qry_radio);
		$regis = $res_radio["RadioCar"];
	}else{
		if($res_VContact=pg_fetch_array($query_VContact)){
			$asset_type = $res_VContact['asset_type'];
			$C_REGIS = $res_VContact['C_REGIS'];
			$car_regis = $res_VContact['car_regis'];
			if($asset_type == 1){
				$regis = $C_REGIS;
			}else{
				$regis = $car_regis;
			}
		}
	}
    
    
    if(($UserIDAccept != $old_UserIDAccept) && $nub != 1){
        echo "<tr><td class=\"sum\" align=\"center\"><a href=\"cash_day_user_pdf.php?date=$datepicker&id=$old_UserIDAccept\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
        <td colspan=3 class=\"sum\"><b>รวม N: ".number_format($n_sum,2)." | รวม R: ".number_format($r_sum,2)." | รวม K: ".number_format($k_sum,2)."</b></td>
        <td colspan=3 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td></tr>";
        $sum_amt = 0;
        $n_sum = 0;
        $r_sum = 0;
        $k_sum = 0;
    }
    
    if($UserIDAccept != $old_UserIDAccept){
        $query1=pg_query("select * from \"Vfuser\" WHERE \"id_user\"='$UserIDAccept'");
        if($resvc1=pg_fetch_array($query1)){
            $fullname = $resvc1['fullname'];
        }
        echo "<tr><td colspan=7><b>ผู้รับเงิน : $fullname ($UserIDAccept)</b></td></tr>";
    }
    
    $sum_amt+=$AmtPay;
    $sum_amt_all+=$AmtPay;
    
    $typecode = "";
    $typecode = $refreceipt[2];
    if($typecode == "N"){
        $n_sum += $AmtPay;
    }elseif($typecode == "R"){
        $r_sum += $AmtPay;
    }elseif($typecode == "K"){
        $k_sum += $AmtPay;
    }

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo "$refreceipt"; ?></td>
        <td align="center"><?php echo $IDNO; ?></td>
        <td><?php echo $A_NAME." ".$A_SIRNAME; ?></td>
        <td align="left"><?php echo $regis; ?></td>
        <td align="center"><?php echo $TypePay; ?></td>
        <td><?php echo $TName; ?></td>
		<td align="center"><?php echo $PostTime; ?></td>
        <td align="right"><?php echo number_format($AmtPay,2); ?></td>
    </tr>
    
<?php
    $old_UserIDAccept = $UserIDAccept;
}

echo "<tr><td class=\"sum\" align=\"center\"><a href=\"cash_day_user_pdf.php?date=$datepicker&id=$old_UserIDAccept\" target=\"_blank\">(พิมพ์รายงาน)</a></td>
<td colspan=3 class=\"sum\"><b>รวม N: ".number_format($n_sum,2)." | รวม R: ".number_format($r_sum,2)." | รวม K: ".number_format($k_sum,2)."</b></td>
<td colspan=3 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td></tr>";

echo "<tr>
<td colspan=7 class=\"sumall\" align=right><b>รวมเงินทั้งหมด</b></td>
<td align=right class=\"sumall\"><b>".number_format($sum_amt_all,2)."</b></td></tr>";

if($num_row==0){
?>
<tr>
    <td colspan="7" align="center">- ไม่พบข้อมูล -</td>
</tr>
<?php
}
?>

</table>

<?php
}
?>