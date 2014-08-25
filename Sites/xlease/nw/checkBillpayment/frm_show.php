<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
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
<div align="right"><a href="showcheckbill_pdf.php?date=<?php echo "$datepicker"; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์รายงาน)</span></a></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>ธนาคาร</td>
    <td>วันที่โอน</td>
    <td>เวลาที่โอน</td>
    <td>terminal_id</td>
	<td>ref_idno</td>
    <td>ref1</td>
    <td>ref2</td>
    <td>ref_name</td>
    <td>post_to_idno</td>
    <td>จำนวนเงิน</td>
	<td>สถานะการตรวจ</td>
</tr>

<?php
$nub = 0;
$query=pg_query("select * from \"TranPay\" WHERE \"post_on_date\"='$datepicker' ORDER BY \"bank_no\",\"terminal_id\",\"tr_date\",\"tr_time\" ASC");
while($resvc=pg_fetch_array($query)){
    $n++;
    $bank_no = $resvc['bank_no'];
    $tr_date = $resvc['tr_date'];
    $tr_time = $resvc['tr_time'];
    $terminal_id = $resvc['terminal_id'];
    $ref1 = trim($resvc['ref1']);
    $ref2 = trim($resvc['ref2']);
    $ref_name = $resvc['ref_name'];
    $post_to_idno = $resvc['post_to_idno'];
    $amt = $resvc['amt'];
	$id_tranpay=$resvc['id_tranpay'];
	
	//หาค่า ref_idno
	if($ref1 == "" and $ref2 ==""){
		$ref_idno="-";
	}else{
		$qry_refidno=pg_query("SELECT * FROM \"Fp\" where \"TranIDRef1\"='$ref1' and  \"TranIDRef2\"='$ref2'");
		$num_refidno=pg_num_rows($qry_refidno);
		if($res_refidno=pg_fetch_array($qry_refidno)){
			$ref_idno=$res_refidno["IDNO"];
		}
	}
    
    if(($old_bank != $bank_no) && $n!=1){
        echo "<tr><td colspan=\"9\" align=\"right\"><b>ธนาคาร $old_bank_name รวม $nub รายการ</b></td></tr>";
        $nub = 0;
    }
    
    $bankname = "";
    $query2=pg_query("select \"bankname\" from \"bankofcompany\" WHERE \"bankno\"='$bank_no' ");
    if($resvc2=pg_fetch_array($query2)){
        $bankname = $resvc2['bankname'];
    }

        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\" align=\"left\">";
        }else{
            echo "<tr class=\"even\" align=\"left\">";
        }
?>
        <td><?php echo $bankname; ?></td>
        <td align="center"><?php echo $tr_date; ?></td>
        <td align="center"><?php echo $tr_time; ?></td>
        <td><?php echo $terminal_id; ?></td>
		<td align="center"><?php echo $ref_idno; ?></td>
        <td align="center"><?php echo $ref1; ?></td>
        <td align="center"><?php echo $ref2; ?></td>
        <td><?php echo $ref_name; ?></td>
        <td><?php echo $post_to_idno; if($ref_idno != $post_to_idno and $ref_idno !="-") echo " <font color=red><b>*</b></font>";?></td>
        <td align="right"><?php echo number_format($amt,2); ?></td>
		<td align="center">
		<?php
			$qry_check=pg_query("select * from \"TranPay_audit\" where id_tranpay='$id_tranpay'");
			$numrowcheck=pg_num_rows($qry_check);
			if($numrowcheck==0){
			?>
				<span onclick="javascript:popU('frm_checkbill.php?id_tranpay=<?php echo $id_tranpay;?>&datepicker=<?php echo $datepicker;?>&ref1=<?php echo $ref1;?>&ref2=<?php echo $ref2;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=450')" style="cursor: pointer;" title="ยังไม่ตรวจ"><font color="red"><u>ยังไม่ตรวจ</u></font></span>
			<?php
			}else{
				if($rescheck=pg_fetch_array($qry_check)){
					$result=$rescheck["result"];
				}
				if($result==1){
					$txtcheck="ผ่าน";
				}else if($result==9){
					$txtcheck="ผิดปกติ";
				}
				?>
					<span onclick="javascript:popU('frm_showcheckbill.php?id_tranpay=<?php echo $id_tranpay;?>&datepicker=<?php echo $datepicker;?>&ref1=<?php echo $ref1;?>&ref2=<?php echo $ref2;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=450')" style="cursor: pointer;" title="รายละเอียด"><u><?php echo $txtcheck;?></u></span>
				<?php
			}
		?>
			
		</td>
    </tr>
<?php
$nub++;
$old_bank = $bank_no;
$old_bank_name = $bankname;
$sum_bank += $amt;
$sum_all_bank += $amt;
}

if($n>0){
    echo "<tr><td colspan=\"11\" align=\"right\"><b>ธนาคาร $old_bank_name รวม $nub รายการ</b></td></tr>";
	echo "<tr><td colspan=11 align=right><b>ธนาคาร $old_bank_name ยอดรวม ".number_format($sum_bank,2)." บาท</b></td></tr>";
	echo "<tr><td colspan=11 align=right><b>ยอดรวมทั้งหมด ".number_format($sum_all_bank,2)." บาท</b></td></tr>";
}else{
    echo "<tr><td colspan=\"11\" align=\"center\">- ไม่พบข้อมูล -</td></tr>";
}
?>
</table>