<?php
	$Doc_ID_Receive =  pg_escape_string($_GET['Doc_ID']);
	$Sql_Show_cr_0046_by_Doc_ID = Create_Sql_Cmd_For_Show_Style_Full_From_cr0046($Doc_ID_Receive);
	// echo $Sql_Show_cr_0046_by_Doc_ID;
	$Result = pg_query($Sql_Show_cr_0046_by_Doc_ID);
	$Data = pg_fetch_array($Result);
	print_r($Data);
	include('Set_Up_Value_To_Display_cr0046.php');
?>