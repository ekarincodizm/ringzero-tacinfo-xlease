<?php
include("../../config/config.php");
include("../function/checknull.php");

$na_room = checknull(trim($_POST['na_room']));
$na_floor = checknull(trim($_POST['na_floor']));
$na_homenumber = checknull(trim($_POST['na_homenumber']));
$na_building = checknull(trim($_POST['na_building']));
$na_moo = checknull(trim($_POST['na_moo']));
$na_village = checknull(trim($_POST['na_village']));
$na_soi = checknull(trim($_POST['na_soi']));
$na_road = checknull(trim($_POST['na_road']));
$na_tambon = checknull(trim($_POST['na_tambon']));
$na_district = checknull(trim($_POST['na_district']));
$na_province = checknull(trim($_POST['na_province']));
$na_zipcode = checknull(trim($_POST['na_zipcode']));
$cusid = checknull(trim($_POST['cusid']));

$doer = $_SESSION['av_iduser'];
$doerStamp = nowDateTime();
$q_chk = str_replace("=null"," is null","select \"asset_addressID\" from \"thcap_contract_asset_address\" where \"Room\"=$na_room and \"Floor\"=$na_floor and \"HomeNumber\"=$na_homenumber and \"Building\"=$na_building and \"Moo\"=$na_moo and \"Village\"=$na_village and \"Soi\"=$na_soi and \"Road\"=$na_road and \"Tambon\"=$na_tambon and \"District\"=$na_district and \"Province\"=$na_province and \"Zipcode\"=$na_zipcode and \"customerID\"=$cusid");
$qr_chk = pg_query($q_chk);
if($qr_chk)
{
	$row_chk = pg_num_rows($qr_chk);
	if($row_chk==0)
	{
		$qr_is = pg_query("insert into \"thcap_contract_asset_address\"(\"Room\",\"Floor\",\"HomeNumber\",\"Building\",\"Moo\",\"Village\",\"Soi\",\"Road\",\"Tambon\",\"District\",\"Province\",\"Zipcode\",\"customerID\",\"doer\",\"doerStamp\") values($na_room,$na_floor,$na_homenumber,$na_building,$na_moo,$na_village,$na_soi,$na_road,$na_tambon,$na_district,$na_province,$na_zipcode,$cusid,'$doer','$doerStamp')");
		if($qr_is)
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
else
{
	echo 0;
}

?>