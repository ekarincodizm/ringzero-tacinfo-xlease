<?php
include("../../config/config.php");
include("../../GenCusID.php");

$prefix = $_POST['prefix'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$id_card = $_POST['id_card'];

$cusID = GenCus();
$date = date("Y-m-d H:i:s");
$doerID = $_SESSION['av_iduser'];
$statusapp = 1;
$edittime = 0;
$n_card = "ประชาชน";
$n_san = "ไทย";
$n_state = 0;

$qr = pg_query("select * from \"Customer_Temp\" where \"N_IDCARD\"='$id_card'");
if($qr)
{
	$row = pg_num_rows($qr);
	if($row==0)
	{
		$status = 0;
		
		pg_query("BEGIN");

		$qr = pg_query("insert into \"Customer_Temp\"(\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"N_SAN\",\"N_CARD\",\"N_IDCARD\",\"N_STATE\") values('$cusID','$doerID','$date','$doerID','$date','$statusapp','$edittime','$prefix','$fname','$lname','$n_san','$n_card','$id_card','$n_state')");
		if(!$qr)
		{
			$status++;
		}
		
		$qr1 = pg_query("insert into \"Fa1\"(\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"Approved\") values('$cusID','$prefix','$fname','$lname',true)");
		if(!$qr1)
		{
			$status++;
		}
		
		$qr2 = pg_query("insert into \"Fn\"(\"CusID\",\"N_STATE\",\"N_SAN\",\"N_CARD\",\"N_IDCARD\") values('$cusID','$n_state','$n_san','บัตรประชาชน','$id_card')");
		if(!$qr2)
		{
			$status++;
		}
		if($status==0)
		{
			pg_query("COMMIT");
			echo 1;
		}
		else
		{
			pg_query("ROLLBACK");
			echo 0;
		}
	}
	else
	{
		echo 0;
	}
}
else
{
	echo 0;
}
?>