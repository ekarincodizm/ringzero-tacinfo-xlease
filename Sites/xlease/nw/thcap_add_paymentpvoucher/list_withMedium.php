<?php
include("../../config/config.php");
$voucherPurpose = $_POST['voucherPurpose'];

$qr =  pg_query("select \"withMedium\" from \"thcap_temp_voucher_purpose_channel\" where \"voucherPurpose\"='$voucherPurpose'");
if($qr)
{
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		$rs = pg_fetch_array($qr);
		$withMediumid = $rs['withMedium'];
		$qry_GenType = pg_query("select * from \"thcap_temp_voucher_mediumcategory\" where \"mediumcategory_id\"= '$withMediumid'");
			$res_gentype=pg_fetch_array($qry_GenType);
			$mediumcategoryid = $res_gentype["mediumcategory_id"];
			$mediumcategoryname = $res_gentype["mediumcategory_name"];
			?>
			<option value="<?php echo "$mediumcategoryid".'#'."$mediumcategoryname"; ?>">
							<?php echo "$mediumcategoryid".'#'."$mediumcategoryname"; ?></option>
	<?php }

}

?>