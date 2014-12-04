<?php
	$Str_Query = "
					SELECT 
							\"Element_ID\" 
       				FROM 
       						thcap_audit_docs_detail
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID_Receive."') And 
  							(\"Element_Type\" = 'radio')
				
				";
	// // Load Data For Set To Show กรณี Element Type เป็น Radio			
	$Result = pg_query($Str_Query);
	while($Data = pg_fetch_array($Result))
	{
		?>
		<script type="text/javascript">
			var Elm_ID = "<?php echo $Data[0]; ?>";
			document.getElementById(Elm_ID).checked = true;
			document.getElementById(Elm_ID).style = "display:none"; // ใช้กรณีที่ไม่ต้องการแสดง ปุ่ม Radio
			document.getElementById('P_'+Elm_ID).innerHTML = '&nbsp;<img src="./images/radioimage1.png" width="12" height="12">&nbsp;&nbsp;';
		</script>
		<?php
			
	}
	
	// Load Data For Set To Show กรณี Element Type เป็น text
	$Str_Query = "
					SELECT 
							\"Element_ID\",\"Value\" 
       				FROM 
       						thcap_audit_docs_detail
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID_Receive."') And 
  							(\"Element_Type\" = 'text')
				
				";
				
	$Result = pg_query($Str_Query); 
	while($Data = pg_fetch_array($Result))
	{
		?>
		<script type="text/javascript">
			var Elm_ID = "<?php echo $Data[0]; ?>";
			var Elm_Val = '<?php echo $Data[1]; ?>';
			document.getElementById(Elm_ID).value = Elm_Val;
		</script>
		<?php
		
	}
	
	// Load Data For Set To Show กรณี Element Type เป็น textarea
	$Str_Query = "
					SELECT 
							\"Element_ID\",\"Value\" 
       				FROM 
       						thcap_audit_docs_detail
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID_Receive."') And 
  							(\"Element_Type\" = 'textarea')
				
				";
				
	$Result = pg_query($Str_Query);
	while($Data = pg_fetch_array($Result))
	{
		?>
		<script type="text/javascript">
			var Elm_ID = "<?php echo $Data[0]; ?>";
			document.getElementById(Elm_ID).value = <?php echo "'".$Data[1]."'"; ?>
		</script>
		<?php
		
	}	
	
	// Load Data For Set To Show กรณี Element Type เป็น
	$Str_Query = "
					SELECT 
							\"Element_ID\",\"Value\" 
       				FROM 
       						thcap_audit_docs_detail
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID_Receive."') And 
  							(\"Element_Type\" = 'hidden')
				
				";
	
	$Result = pg_query($Str_Query);
	while($Data = pg_fetch_array($Result))
	{
		?>
		<script type="text/javascript">
			var Elm_ID = "<?php echo $Data[0]; ?>";
			document.getElementById(Elm_ID).value = <?php echo "'".$Data[1]."'"; ?>
		</script>
		<?php
	}
	
	
	
	// รับค่าตัวแปรเพื่อเตรียมแสดงชื่อลูกค้า				
    $Str_Customer_Name = pg_escape_string($_GET['Cust_Name1']).'#'.pg_escape_string($_GET['Cust_Name2']);
	
?>

<script type="text/javascript">
	document.getElementById('Cutomer_Name').value = '<?php echo $Str_Customer_Name;  ?>'; 
</script>
