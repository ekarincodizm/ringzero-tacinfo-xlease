<?php 
	if($voucherID !=""){
		$qry_chk = pg_query("select \"autoID\",\"contractID\" from \"thcap_temp_voucher_tag\" where \"voucherID\" ='$voucherID' ");
		$nub_rows = pg_num_rows($qry_chk);	
		if($nub_rows==0){$textshow="ยังไม่ถูกเชื่อมกับเลขทีสัญญา";}
		else if($nub_rows==1){
			$contractID = pg_fetch_result($qry_chk,1);
			//หมายเหตุ       format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
			$contract_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/';
			$contract_popup = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4\5'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4\5'."</u></font></span>";		
			$contractID = preg_replace($contract_format,$contract_popup,$contractID);	
			$textshow=" มีการเชื่อม 1 เลขที่สัญญา  คือ   ";			
			}
		else if($nub_rows>1){$textshow="มีการเชื่อม หลาย เลขที่สัญญา";} ?>
		<span onclick="javascript:popU('<?php echo $rootpath."nw/thcap_appv/frm_listdata.php?voucherID=$voucherID"?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')" style="cursor:pointer;"><font color="blue"><u><?php echo $textshow?></u></font></span><?php echo $contractID;?>
		<br>
		<input type="button"  value="เพิ่ม TAG ใบสำคัญ" onclick="javascript:popU('<?php echo $rootpath."nw/thcap_appv/frm_tag.php?voucherID=$voucherID"?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=300')"/>
	<?php }
?>

