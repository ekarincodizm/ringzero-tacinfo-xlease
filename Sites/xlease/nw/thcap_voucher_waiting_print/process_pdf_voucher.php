<?php
session_start();
include("../../config/config.php");
//----------------- รับข้อมูล ----------------------------------
$doer = $_SESSION['av_iduser'];//ผู้ทำรายการ
$select_printchk = $_POST["select_print"];
$datenow = nowDateTime();//วันเวลาที่ทำรายการ

$status = 0;
pg_query("Begin");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
//ACTIONLOG  เก็บประวัติการใช้เมนู
if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$doer', '(THCAP) ใบสำคัญรอพิมพ์ส่ง', LOCALTIMESTAMP(0))"));
else $status++;
//ACTIONLOG---
?>
<form name ="frm1" action="../thcap_payment_voucher/pdf_payment_voucher.php" method="post"  target="_blank">
<?php
for($i=0;$i<count($select_printchk);$i++){
	
	$voucherID[$i]=$select_printchk[$i];	
	$sql_chktype = pg_query("select \"voucherType\" from thcap_temp_voucher_details 
	where \"voucherID\"='$voucherID[$i]'");
	$res_detail = pg_fetch_array($sql_chktype);
	$vouchertype= $res_detail['voucherType'];
	if($vouchertype=='1'){ //pv 
		$npv ++;
		//บันทึกข้อมูล		
		/*if($sql_reprint = pg_query("INSERT INTO \"thcap_temp_voucher_details_reprint\"(\"voucherID\", \"id_user\",\"printStamp\") 
			VALUES ('$voucherID[$i]', '$doer', '$datenow')"));		
		else $status++;	*/
		echo "<input name=\"select_print[]\" id=\"select_print$i\" value=\"$voucherID[$i]\" hidden></td>"; ?>
		
	
	<?php }
} //end for frm1
?>
<!--มีไว้เพื่อ ใช้เมนู (THCAP) ใบสำคัญรอพิมพ์ส่ง-->
<input name="reprint"  value="reprint" hidden />
<input name="print" type="submit" value="พิมพ์" hidden />
</form>	
<?php if($npv == count($select_printchk)){ }
else {?>
	<form name ="frm2" action="../thcap_journal_voucher/pdf_journal_voucher.php" method="post"  target="_blank">
	<?php
	for($j=0;$j<count($select_printchk);$j++){
	
		$voucherID[$j]=$select_printchk[$j];	
		$sql_chktype = pg_query("select \"voucherType\" from thcap_temp_voucher_details 
			where \"voucherID\"='$voucherID[$j]'");
		$res_detail = pg_fetch_array($sql_chktype);
		$vouchertype= $res_detail['voucherType'];
		if($vouchertype=='3'){//jv 
			$njv ++;
			//บันทึกข้อมูล		
			/*if($sql_reprint = pg_query("INSERT INTO \"thcap_temp_voucher_details_reprint\"(\"voucherID\", \"id_user\",\"printStamp\") 
				VALUES ('$voucherID[$j]', '$doer', '$datenow')"));		
			else $status++;	*/
			echo "<input name=\"select_print[]\" id=\"select_print$j\" value=\"$voucherID[$j]\" hidden></td>"; ?>		
				
	<?php }
	} //end for frm2
	?>
	<!--มีไว้เพื่อ ใช้เมนู (THCAP) ใบสำคัญรรอพิมพ์ส่ง-->
	<input name="reprint"  value="reprint" hidden />
	<input name="print" type="submit" value="พิมพ์" hidden />
	</form>
	<?php if(($npv+$njv) !=count($select_printchk)){ //rv?>
		<form name ="frm3" action="../thcap_receive_voucher/pdf_receive_voucher.php" method="post"  target="_blank">
		<?php
			for($j=0;$j<count($select_printchk);$j++){
	
				$voucherID[$j]=$select_printchk[$j];	
				$sql_chktype = pg_query("select \"voucherType\" from thcap_temp_voucher_details 
				where \"voucherID\"='$voucherID[$j]'");
				$res_detail = pg_fetch_array($sql_chktype);
				$vouchertype= $res_detail['voucherType'];
				if($vouchertype=='2'){//rv
					$nrv ++;
					//บันทึกข้อมูล		
					/*if($sql_reprint = pg_query("INSERT INTO \"thcap_temp_voucher_details_reprint\"(\"voucherID\", \"id_user\",\"printStamp\") 
						VALUES ('$voucherID[$j]', '$doer', '$datenow')"));		
					else $status++;	*/
				echo "<input name=\"select_print[]\" id=\"select_print$j\" value=\"$voucherID[$j]\" hidden></td>"; ?>	
		<?php }
			} //end for frm3 ?>
		<!--มีไว้เพื่อ ใช้เมนู (THCAP) ใบสำคัญรรอพิมพ์ส่ง-->
		<input name="reprint"  value="reprint" hidden />
		<input name="print" type="submit" value="พิมพ์" hidden />
		</form>		
	<?php }
}
echo $status;
if($status == 0){
	pg_query("COMMIT");	
	echo "<script type=\"text/javascript\">";	
	if($npv > 0){//กรณีที่เป็น   Payment voucher
		echo "document.forms['frm1'].print.click();";
	}
	if($njv > 0) {//กรณีที่เป็น    Journal voucher
		echo "document.forms['frm2'].print.click();";
	}
	if($nrv > 0) {//กรณีที่เป็น    Receive voucher
		echo "document.forms['frm3'].print.click();";
	}
	//reload ก่อนหน้า
	echo "opener.location.reload(true);
				self.close();";
	echo "</script>"; 
}else{
	pg_query("ROLLBACK");		
}
?>