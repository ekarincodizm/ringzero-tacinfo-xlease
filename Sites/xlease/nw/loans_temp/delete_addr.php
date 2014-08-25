<?php
include("../../config/config.php");

$assetid = $_POST['assetid'];

$qr_chk1 = pg_query("select \"contractID\" from \"thcap_contract_asset\" where \"assetAddress\"='$assetid'");
if($qr_chk1)
{
	$row_chk1 = pg_num_rows($qr_chk1);
	if($row_chk1==0)
	{
		$qr_chk2 = pg_query("select \"autoID\" from \"thcap_contract_asset_temp\" where \"assetAddress\"='$assetid'");
		if($qr_chk2)
		{
			$row_chk2 = pg_num_rows($qr_chk2);
			if($row_chk2==0)
			{
				$qr_delete = pg_query("delete from \"thcap_contract_asset_address\" where \"asset_addressID\" = '$assetid'");
				if($qr_delete)
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
			}
			else
			{
				echo 2;
			}
		}
	}
	else
	{
		echo 2;
	}
}
?>