<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>Approve Voucher รออนุมัติ</B></legend>
				<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#F0F0F0">
					<tr bgcolor="#6FB7FF" style="font-weight:bold; text-align:center">
						<td>รูปแบบ</td>
						<td>รหัส</td>
						<td>รายละเอียด</td>
						<td>ยอดเงิน</td>
						<td>วันที่ทำรายการ</td>
						<td>JobID</td>
					</tr>
<?php
$i = 0;
$qry=pg_query("select A.*,B.* from account.tal_voucher A LEFT OUTER JOIN account.\"job_voucher\" B on A.\"job_id\" = B.\"job_id\" WHERE A.\"approve_id\" is null ORDER BY A.\"job_id\" DESC");
while($res=pg_fetch_array($qry)){
    $i++;
    $vc_id = $res["vc_id"];
    $vc_detail = $res["vc_detail"];
    $do_date = $res["do_date"];
    $job_id = $res["job_id"];
    $cash_amt = $res["cash_amt"];
    $approve_id = $res["approve_id"];
    $chq_acc_no = $res["chq_acc_no"];
    $chque_no = $res["chque_no"];
    
    $marker_id = $res["marker_id"];
    
    if(empty($chq_acc_no)){
        $chk_cheq = "N";
        $money = $cash_amt;
    }else{
        $chk_cheq = "C";
        $qry_chq=pg_query("select * from account.\"ChequeOfCompany\" WHERE \"AcID\"='$chq_acc_no' AND \"ChqID\"='$chque_no'");
        if($res_chq=pg_fetch_array($qry_chq)){
            $money = $res_chq["Amount"];
        }
    }
   
		if($i%2==0){
			echo "<tr bgcolor=#B2DFEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\" align=center>";
		}else{
			echo "<tr bgcolor=#BFEFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\" align=center>";
		}
?>
						<td align="center"><?php if(empty($chq_acc_no)){ echo "เงินสด"; }else{ echo "เช็ค"; } ?></td>
						<td align="center"><?php echo $vc_id; ?></td>
						<td align="center"><?php echo nl2br($vc_detail); ?></td>
						<td align="right"><?php echo number_format($money,2); ?></td>
						<td align="center"><?php echo $do_date; ?></td>
						<td align="center"><?php echo $job_id; ?></td>
					</tr>
<?php
}
?>
				</table>
			</fieldset>
        </td>
    </tr>
</table>
