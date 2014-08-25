<?php
include("../../config/config.php");
$fromChannel = pg_escape_string($_POST['fromChannel']);
echo "<option value=\"\">-- กรุณาเลือกประเภทข้อมูล--</option>";
$qr =  pg_query("select \"BChannelRefVoucherChannelType\" from \"BankInt\" where \"BID\"='$fromChannel'");
if($qr)
{
	$row = pg_num_rows($qr);
	
	if($row!=0)
	{
		$rs = pg_fetch_array($qr);
		$RefChannelType = $rs['BChannelRefVoucherChannelType'];
		$qry_GenType = pg_query("select * from \"thcap_temp_voucher_reftype\" where \"voucher_reftype_id\"= '$RefChannelType'");
		$res_gentype=pg_fetch_array($qry_GenType);
		$reftype_id = $res_gentype["voucher_reftype_id"];
		$reftype_name= $res_gentype["voucher_reftype_name"];
		?>
		<option value="<?php echo "$reftype_id".'#'."$reftype_name"; ?>" >
					  <?php echo "$reftype_id".'#'."$reftype_name"; ?></option>
	<?php }
	else { ?>
		<option value="<?php echo ""; ?>" >
					  <?php echo "----------"; ?></option>
	<?php }
	
}
else { ?>
		<option value="<?php echo ""; ?>" >
					  <?php echo "----------"; ?></option>
	<?php }

?>