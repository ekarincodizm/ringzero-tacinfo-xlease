<?php
	$Contract_Type = "'".$Data['conType']."'"; // รับค่าประเภทสัญญา
	$Doc_ID = pg_escape_string($_GET['Doc_ID']);  // รับค่าเลขที่เอกสาร
	
	// Load Data For Set To Show กรณี Element Type เป็น Radio
	$Str_Query = "
					SELECT 
							\"Element_ID\" 
       				FROM 
       						thcap_audit_docs_detail
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID."') And 
  							(\"Element_Type\" = 'radio')
				
				";
	$Result = pg_query($Str_Query);
	while($Data = pg_fetch_array($Result))
	{
		?>
		<script type="text/javascript">
			var Elm_ID = "<?php echo $Data[0]; ?>";
			document.getElementById(Elm_ID).checked = true;
		</script>
		<?php
		
	}
	
	// Load Data For Set To Show กรณี Element Type เป็น text
	$Str_Query = "
					SELECT 
							\"Element_ID\",\"Value\" 
       				FROM 
       						thcap_audit_docs_detail
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID."') And 
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
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID."') And 
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
	
	// Load Data For Set To Show กรณี Element Type เป็น select-one
	$Str_Query = "
					SELECT 
							\"Element_ID\",\"Value\" 
       				FROM 
       						thcap_audit_docs_detail
  					WHERE 	(\"Docs_ID\" = '".$Doc_ID."') And 
  							(\"Element_Type\" = 'select-one')
				
				";
				
	$Result = pg_query($Str_Query);
	while($Data = pg_fetch_array($Result))
	{
		?>
		<script type="text/javascript">
			var Elm_ID = "<?php echo $Data[0]; ?>";
			document.getElementById(Elm_ID).value = '<?php echo $Data[1]; ?>'
		</script>
		<?php
		
	}
	
	// Load Data(ประเภทสัญญา,เลขที่สัญญา,ตรวจสอบครั้งที่) For Set To Show
	$Str_Query = 	
					"
						SELECT 
								\"thcap_audit_docs_main\".\"Contract_ID\",
								\"thcap_audit_docs_main\".\"Con_Type\",
								\"thcap_audit_docs_main\".\"AppvTime\"
						FROM 	
								\"thcap_audit_docs_main\",
								\"thcap_audit_docs_detail\"
						WHERE
								(\"thcap_audit_docs_main\".\"auto_ID\" = \"thcap_audit_docs_detail\".\"main_autoID\") AND 	
								(\"thcap_audit_docs_detail\".\"Docs_ID\" = '".$Doc_ID."')
						Group By  								
								\"thcap_audit_docs_main\".\"Contract_ID\",
								\"thcap_audit_docs_main\".\"Con_Type\",
								\"thcap_audit_docs_main\".\"AppvTime\"    			 			
				 	" ;	
	$Result = pg_query($Str_Query);
	$Data = pg_fetch_array($Result);
	// กำหนดการแสดงข้อมูลให้กับ ประเภทสัญญา  เลขที่สัญญา ตรวจสอบครั้งที่
	?>
	<script type="text/javascript">
		document.getElementById('Type_Contract_Select_Input').value = '<?php echo  $Data['Con_Type']; ?>'
		document.getElementById('Contract_ID').value = '<?php echo  $Data['Contract_ID']; ?>'
		document.getElementById('Check_Time').value = '<?php echo  $Data['AppvTime']; ?>'
	</script>
	<?php
?>





