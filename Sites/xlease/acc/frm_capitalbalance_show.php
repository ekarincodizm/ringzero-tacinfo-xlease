<?php
include("../config/config.php");
set_time_limit(120);
$date = pg_escape_string($_GET['date']);
$search = pg_escape_string($_GET['search']);
if($search==2){
	$years = pg_escape_string($_GET['years']);
	$condition="and \"custyear\"='$years'";
}else{
	$condition="";
}

$rs=pg_query("select account.\"CreateDebtBalance\"('$date')");
$rt1=pg_fetch_result($rs,0);
if(!$rt1){
    echo "ไม่สามารถสร้าง View ได้";
    exit;
}
?> 


<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
    <td align="center">เลขที่สัญญา</td>
    <td align="center">ชื่อผู้เช่าซื้อ</td>
    <td align="center">เงินต้นคงเหลือ</td>
</tr>

<?php
$qry_name=pg_query("SELECT * FROM account.\"VDebtBalance\" where \"acclosedate\" = '$date' $condition ORDER BY custyear,\"idno\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $nub_all+=1;
    $idno = $res_name["idno"];
    $custyear = $res_name["custyear"];
    $customer_name = $res_name["customer_name"];
    $remain2 = $res_name["remain"];
    $vatpayready = $res_name["vatpayready"];
	
	$RL = $remain2 - 0.01;
	$RR = $remain2 + 0.01;
	
	/* หาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
	$qry_name2=pg_query("SELECT * FROM public.\"VAccPayment\" where \"IDNO\" = '$idno' and \"Remine\" >= '$RL' and \"Remine\" <= '$RR' ");
	$rows2 = pg_num_rows($qry_name2);
	while($res_name2=pg_fetch_array($qry_name2))
	{
		$remain = $res_name2["Priciple"];
	}
	
	if($rows2 == 0)
	{
		$qry_name3=pg_query("SELECT * FROM public.\"Fp\" where \"IDNO\" = '$idno' ");
		while($res_name3=pg_fetch_array($qry_name3))
		{
			$remain = $res_name3["P_BEGINX"]; // เงินต้นทางบัญชี
		}
	}
	*/ //จบการหาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
	
	//หาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
	$qry_name2=pg_query("SELECT * FROM public.\"VCusPayment\" where \"IDNO\" = '$idno' and \"Remine\" >= '$RL' and \"Remine\" <= '$RR' ");
	$rows2 = pg_num_rows($qry_name2);
	while($res_name2=pg_fetch_array($qry_name2))
	{
		$remain = $res_name2["Priciple"];
	}
	
	if($rows2 == 0)
	{
		$qry_name3=pg_query("SELECT * FROM public.\"Fp\" where \"IDNO\" = '$idno' ");
		while($res_name3=pg_fetch_array($qry_name3))
		{
			$remain = $res_name3["P_BEGIN"]; // เงินต้นทางลูกค้า
		}
	}
	//จบการหาจำนวนเงินต้นคงเหลือ ซึ่งนำตัวเลขมาจาก "เงินต้น" ในเมนู "ตารางลูกหนี้ ณ สิ้นปี"
    
    if($nub_all == 1){
        echo "<tr><td colapan=4><b>ลูกหนี้ปี $custyear</b></td></tr>";
    }
    
    if($nub_all != 1 && $custyear != $old_custyear){
        echo "<tr>
        <td align=right colspan=2><b>ทั้งหมด $nubyear ราย | รวมเงิน</b></td>
        <td align=right><b>".number_format($sum_remain_year,2)."</b></td>
        </tr>
        
        <tr><td colapan=4><b>ลูกหนี้ปี $custyear</b></td></tr>
        ";
        $nubyear = 0;
        $sum_remain_year = 0;
        $sum_vatpayready_year = 0;
    }
    
    $nubyear+=1;
    $sum_remain_year+=$remain;
    $sum_vatpayready_year+=$vatpayready;
    
    $s_remain += $remain;
    $s_vatpayready += $vatpayready;
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\" style=\"font-size: 12px\">";
    }else{
        echo "<tr class=\"even\" style=\"font-size: 12px\">";
    }
?>
    <td align="center"><?php echo "$idno"; ?></a></td>
    <td align="left"><?php echo "$customer_name"; ?></td>
    <td align="right"><?php echo number_format($remain,2); ?></td>
</tr>
<?php
$old_custyear = $custyear;
}

if($rows == 0){
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    
     echo "<tr>
        <td align=right colspan=2><b>ทั้งหมด $nubyear ราย | รวมเงิน</b></td>
        <td align=right><b>".number_format($sum_remain_year,2)."</b></td>
        </tr>
     ";
    
    echo '<tr>
    <td align=left><b><img src="icoPrint.png" border="0" width="17" height="14"> <a href="frm_capitalbalance_pdf.php?search='.$search.'&years='.$years.'&date='.$date.'" target=_blank>พิมพ์รายงาน</a></b></td>
    <td align=right><b>จำนวนลูกหนี้ทั้งหมด '.$nub_all.' ราย | รวมยอดเงิน</b></td>
    <td align=right><b>'.number_format($s_remain,2).'</b></td>
    </tr>';
}
?>

</table>