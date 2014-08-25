<?php
include("../../config/config.php");
$datepicker = $_GET['datepicker'];

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
</style>

<div align="right"><a href="tac_all_pdf.php?datepicker=<?php echo "$datepicker"; ?>&mm=01&yy=2012&ty=1" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์ชุดใบเสร็จ TAC)</span></a></div>

<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่ใบเสร็จ</td>
    <td>เลขที่ใบเสร็จ</td>
    <td>IDNO</td>
    <td>ชื่อลูกค้า</td>
    <td>ทะเบียน</td>
    <td>TypePay</td>
    <td>TName</td>
    <td>จำนวนเงิน</td>
    <td>PayType</td>
</tr>

<?php
$query=pg_query("select * from \"FOtherpay\" WHERE \"O_PRNDATE\"='$datepicker' AND (\"O_Type\"='165' OR \"O_Type\"='307') AND \"Cancel\"='FALSE' AND (\"PayType\"='TCQ' OR \"PayType\"='TTR') ORDER BY \"PayType\",\"O_DATE\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $IDNO = $resvc['IDNO'];
    $O_DATE = $resvc['O_DATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $O_MONEY = $resvc['O_MONEY'];
    $O_Type = $resvc['O_Type'];
    $O_PRNDATE = $resvc['O_PRNDATE'];
    $PayType = $resvc['PayType'];
    
    $TName="";
    $query_type=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$O_Type'");
    if($res_type=pg_fetch_array($query_type)){
        $TName = $res_type['TName'];
    }
    
    $full_name="";
    $C_REGIS="";
    $car_regis="";
    $regis="";
    $query_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO'");
    if($res_VContact=pg_fetch_array($query_VContact)){
        $full_name = $res_VContact['full_name'];
        $asset_type = $res_VContact['asset_type'];
        $C_REGIS = $res_VContact['C_REGIS'];
        $car_regis = $res_VContact['car_regis'];
        if($asset_type == 1){
            $regis = $C_REGIS;
        }else{
            $regis = $car_regis;
        }
    }

    $sum_amt+=$O_MONEY;
    $sum_amt_all+=$O_MONEY;

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
	
	if($PayType=="TCQ")
	{
		$query_new=pg_query("select * from public.\"FTACCheque\" WHERE \"COID\"='$IDNO' and \"refreceipt\"='$O_RECEIPT' order by \"auto_id\" ");
		while($result_new=pg_fetch_array($query_new))
		{
				$vtid=$result_new["vtid"];
				$full_name=$result_new["fullname"];
				$regis=$result_new["carregis"];
		}
	}
	
	if($PayType=="TTR")
	{
		$query_new=pg_query("select * from public.\"FTACTran\" WHERE \"COID\"='$IDNO' and \"refreceipt\"='$O_RECEIPT' order by \"auto_id\" ");
		while($result_new=pg_fetch_array($query_new))
		{
				$vtid=$result_new["vtid"];
				$full_name=$result_new["fullname"];
				$regis=$result_new["carregis"];
		}
	}
?>
        <td align="center"><?php echo $O_DATE; ?></td>
        <td align="center"><?php echo $O_RECEIPT; ?></td>
        <td align="center"><?php echo $IDNO; ?></td>
        <td><?php echo $full_name; ?></td>
        <td align="left"><?php echo $regis; ?></td>
        <td align="center"><?php echo $O_Type; ?></td>
        <td><?php echo $TName; ?></td>
        <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
        <td align="center"><?php echo $PayType; ?></td>
    </tr>
    
<?php
}


if($num_row>0){
    echo "<tr><td colspan=7 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td><td class=\"sum\"></td></tr>";
}

//=============================================//
$num_row = 0;
$sum_amt = 0;
$query=pg_query("select * from \"FOtherpay\" WHERE \"O_PRNDATE\"='$datepicker' AND \"O_Type\"='109' AND (\"PayType\"='TCQ' OR \"PayType\"='TTR') AND \"Cancel\"='FALSE' ORDER BY \"PayType\",\"O_DATE\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $IDNO = $resvc['IDNO'];
    $O_DATE = $resvc['O_DATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $O_MONEY = $resvc['O_MONEY'];
    $O_Type = $resvc['O_Type'];
    $O_PRNDATE = $resvc['O_PRNDATE'];
    $PayType = $resvc['PayType'];
    
    $TName="";
    $query_type=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$O_Type'");
    if($res_type=pg_fetch_array($query_type)){
        $TName = $res_type['TName'];
    }
    
    $full_name="";
    $C_REGIS="";
    $car_regis="";
    $regis="";
    $query_VContact=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO'");
    if($res_VContact=pg_fetch_array($query_VContact)){
        $full_name = $res_VContact['full_name'];
        $asset_type = $res_VContact['asset_type'];
        $C_REGIS = $res_VContact['C_REGIS'];
        $car_regis = $res_VContact['car_regis'];
        if($asset_type == 1){
            $regis = $C_REGIS;
        }else{
            $regis = $car_regis;
        }
    }

    $sum_amt+=$O_MONEY;
    $sum_amt_all+=$O_MONEY;

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\" align=\"left\">";
    }else{
        echo "<tr class=\"even\" align=\"left\">";
    }
?>
        <td align="center"><?php echo $O_DATE; ?></td>
        <td align="center"><?php echo $O_RECEIPT; ?></td>
        <td align="center"><?php echo $IDNO; ?></td>
        <td><?php echo $full_name; ?></td>
        <td align="left"><?php echo $regis; ?></td>
        <td align="center"><?php echo $O_Type; ?></td>
        <td><?php echo $TName; ?></td>
        <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
        <td align="center"><?php echo $PayType; ?></td>
    </tr>
    
<?php
}

if($num_row>0){
    echo "<tr><td class=\"sum\" align=center><a href=\"tac_all_pdf.php?date=$datepicker&type=2\" target=\"_blank\"><span style=\"font-size:15px; color:#0000FF;\">พิมพ์รายงาน</span></a></td><td colspan=6 class=\"sum\" align=right><b>รวมเงิน</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt,2)."</b></td><td class=\"sum\"></td></tr>";
}

echo "<tr><td colspan=7 class=\"sum\" align=right><b>รวมเงินทั้งสิ้น</b></td><td align=right class=\"sum\"><b>".number_format($sum_amt_all,2)."</b></td><td class=\"sum\"></td></tr>";
?>

</table>

<?php
}
?>