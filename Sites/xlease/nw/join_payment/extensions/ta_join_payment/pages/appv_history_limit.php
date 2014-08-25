<?php
echo "<div style=\"margin-top:15px;\"></div>";
$realpath = redirect($_SERVER['PHP_SELF'],'');
?>
<style type="text/css">
#tb_approved tr {
	height:25px;
}
</style>
<center>
<fieldset style="width:99%">
	<legend>
		<font color="black"><b>
			ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('appv_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>
	</legend>
<br>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<td align="center">ลำดับ</td>
        <td align="center">เลขที่สัญญา</td>
        <td align="center">เลขทะเบียนรถ</td>
        <td align="center">ชื่อลูกค้า</td>
        <td align="center">จำนวนเงินที่ขอ</td>
        <td align="center">ผู้ขออนุมัติ</td>
        <td align="center">วันเวลาที่ขอ</td>
        <td align="center">ผู้ทำรายการอนุมัติ</td>
        <td align="center">เวลาที่ทำรายการอนุมัติ</td>
        <td align="center">ผลการอนุมัติ</td>
        <td align="center">ข้อมูลเข้าร่วม</td>
        <td align="center">เหตุผล</td>
	</tr>
    <?php
	$qry_fr=pg_query("SELECT m.car_license,f.\"O_RECEIPT\",f.\"O_MONEY\",cpro_name,m.idno,m.id,f.approve_status,f.create_by,f.create_datetime,f.\"O_memo\",f.\"approver\",f.\"approve_dt\" FROM \"FOtherpayDiscount\" f left join \"VJoinMain\" m on m.idno = f.\"IDNO\" WHERE m.deleted ='0' and m.car_license_seq = 0
	 and f.approve_status<>0 order by f.approve_dt desc limit 30 ");
	$nub=pg_num_rows($qry_fr);
	$i = 0;
	while($sql_row4=pg_fetch_array($qry_fr)){
		$cpro_name = $sql_row4['cpro_name'];
		$O_RECEIPT = $sql_row4['O_RECEIPT'];
		$car_license = $sql_row4['car_license'];
		$create_datetime =$sql_row4['create_datetime']; 
		$reason =$sql_row4['O_memo']; 
		$approve_status = $sql_row4['approve_status'];
		$O_MONEY =$sql_row4['O_MONEY']; 
		$create_by = $sql_row4['create_by'];//เอาไปหา id_card ด้วย
		$idno = trim($sql_row4['idno']);
		$id = trim($sql_row4['id']);
		$approver = $sql_row4['approver'];
		$approve_dt = $sql_row4['approve_dt'];


		$dt = $create_datetime;
		$by = $create_by;

		if($approve_status=='1')
		{
			$appv_state = "อนุมัติ";
		}
		else if($approve_status=='2')
		{
			$appv_state = "ไม่อนุมัติ";
		}

		$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
		$res_userprofile=pg_fetch_array($res_profile);
		$by = $res_userprofile["fullname"];
		
		$qr_appv = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$approver'");
		$rs_appv = pg_fetch_array($qr_appv);
		$appv_user = $rs_appv['fullname'];

		$i+=1;
		if($i%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
	?>
		<td align="center"><?php echo $i; ?></td>
		<td><span onclick="javascript:popU('<?php echo $realpath; ?>post/frm_viewcuspayment.php?idno_names=<?php echo $idno; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $idno;?></u></font></span></td>				
		<td align="left"><?php echo $car_license; ?></td>
		<td align="left"><?php echo $cpro_name; ?></td>
		<td align="right"><?php echo number_format($O_MONEY); ?></td>
		<td align="center"><?php echo $by; ?></td>
		<td align="center"><?php echo $dt; ?></td>
        <td align="center"><?php echo $appv_user; ?></td>
        <td align="center"><?php echo $approve_dt; ?></td>
        <td align="center"><?php echo $appv_state; ?></td>
		<td align="center">
			<img src="../images/open.png" width="16" height="16" onclick="javascript:popU('<?php echo $realpath; ?>nw/join_payment/extensions/ta_join_payment/pages/ta_join_payment_view_new.php?id=<?php echo $id; ?>&readonly=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1050,height=600')" style="cursor:pointer">
        </td>
		<td align="left"><?php echo $reason; ?></td>
		
	</tr>
	<?php
	}
	if($nub == 0){
		echo "<tr><td colspan=12 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
	}
	else
	{
		echo "<tr bgcolor=\"#CDC5BF\" height=30><td colspan=\"12\" align=\"right\"><b>ข้อมูลทั้งหมด $i รายการ</b></td></tr>";
	}
	?>
</table>
</fieldset>
</center>