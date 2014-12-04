<?php
	$Doc_ID_Receive =  pg_escape_string($_GET['Doc_ID']);
	$Sql_Show_cr_0046_by_Doc_ID = Create_Sql_Cmd_For_Show_Style_Full_From_cr0046($Doc_ID_Receive);
	$Result = pg_query($Sql_Show_cr_0046_by_Doc_ID);
	$Data = pg_fetch_array($Result);
	include('Set_Up_Value_To_Display_cr0046.php');
	$Chk_Data = Create_String_Of_Checker_Data($Doc_ID_Receive);
?>
<script>
	document.getElementById('Show_Checker').innerHTML = '<?php echo $Chk_Data; ?>';
	
</script>